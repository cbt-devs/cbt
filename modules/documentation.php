<style>
    .dz-preview .dz-remove {
        display: none;
    }

    #myDropzone {
        border: 2px dashed #D3D3D3 !important;
        border-radius: 10px;
    }

    #drop-zone-wrapper {
        border: none;
        box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        background-color: #f8f9fa;
        transition: background-color 0.2s ease-in-out;
        cursor: pointer;
    }

    #drop-zone-wrapper.dragover {
        background-color: #e9ecef;
    }

    .dropzone .card {
        box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
    }
</style>

<div class="d-flex justify-content-between align-items-start">
    <div>
        <h2>Documentation</h2>
    </div>
    <button type="button" class="btn btn-primary" id="submitBtn">
        <i class="fa-solid fa-plus"></i> Upload
    </button>
</div>

<div class="container py-4">
    <form action="upload.php" class="dropzone" id="myDropzone"></form>
</div>

<table id="documentationTable" class="table" style="width:100%"></table>

<!-- Dropzone Preview Template -->
<div id="preview-template" style="display: none;">
    <div class="card dz-preview mb-2">
        <div class="card-body d-flex align-items-center justify-content-between">
            <div class="flex-grow-1">
                <h6 class="mb-1" data-dz-name></h6>
                <p class="mb-0 text-muted small" data-dz-size></p>
                <div class="progress mt-2">
                    <div class="progress-bar" role="progressbar" style="width: 0%;" data-dz-uploadprogress></div>
                </div>
                <div class="text-danger mt-2 small" data-dz-errormessage></div>
            </div>
            <button class="btn btn-sm btn-outline-danger ms-3" data-dz-remove>Remove</button>
        </div>
    </div>
</div>

<script>
    var documentation = {
        init: function() {
            this.show();
            this.add();
            this.bindEvents();
        },

        show: function() {
            $.ajax({
                type: "POST",
                url: "controller/main.php",
                data: {
                    action: "show",
                    type: "documentation"
                },
                success: function(response) {
                    const data = response.data;

                    if ($.fn.dataTable.isDataTable('#documentationTable')) {
                        $('#documentationTable').DataTable().clear().destroy();
                    }

                    $('#documentationTable').DataTable({
                        data: data,
                        order: [
                            [2, 'desc']
                        ],
                        columns: [{
                                data: 'filename',
                                title: 'Filename',
                                render: function(data, type, row) {
                                    return `
                                            <a href="assets/documents/${encodeURIComponent(data)}" target="_blank">
                                                ${data}
                                            </a>
                                        `;
                                }
                            },
                            {
                                data: 'name',
                                title: 'Added by'
                            },
                            {
                                data: 'created',
                                title: 'Created'
                            },
                            {
                                data: null,
                                render: function(data, type, row) {
                                    return `
                                        <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    `;
                                },
                                orderable: false,
                                searchable: false
                            }
                        ],
                        initComplete: function() {
                            JsLoadingOverlay.hide();
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error (show):", status, error);
                }
            });
        },

        delete: function(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the document.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'controller/main.php',
                        method: 'POST',
                        data: {
                            action: 'delete',
                            type: 'documentation',
                            id: id
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire('Deleted!', response.message, 'success');
                                documentation.show();
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'Failed to delete file.', 'error');
                        }
                    });
                }
            });
        },

        add: function() {
            Dropzone.autoDiscover = false;

            const dz = new Dropzone("#myDropzone", {
                url: "controller/main.php", // updated from upload.php
                autoProcessQueue: false,
                maxFilesize: 5, // in MB
                acceptedFiles: ".pdf,.doc,.docx",
                previewTemplate: document.querySelector('#preview-template').innerHTML,
                addRemoveLinks: false,
                parallelUploads: 5,
                dictDefaultMessage: `
            <div class="text-center" style="color: #A9A9A9;">
                <i class="fa-solid fa-file fa-2x mb-2"></i><br>
                <span>Drag your documents here or select</span>
            </div>
        `,
                init: function() {
                    const submitBtn = document.getElementById("submitBtn");

                    this.on("addedfile", function() {
                        submitBtn.disabled = false;
                    });

                    this.on("removedfile", function() {
                        if (this.files.length === 0) {
                            submitBtn.disabled = true;
                        }
                    });

                    this.on("sending", function(file, xhr, formData) {
                        loader.init();

                        // Append required POST fields for routing
                        formData.append("action", "add");
                        formData.append("type", "documentation");
                        formData.append("accounts_id", 10); // Hardcoded for now
                    });

                    this.on("success", function(file, response) {
                        JsLoadingOverlay.hide();

                        if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                            Swal.fire("Documents Upload", "", "success");
                            documentation.show();
                            this.removeAllFiles(true);
                            submitBtn.disabled = true;
                        }
                    });

                    this.on("successmultiple", function(files, response) {
                        JsLoadingOverlay.hide();
                        Swal.fire("Documents Upload", "", "success");
                        documentation.show();
                        this.removeAllFiles(true);
                        submitBtn.disabled = true;
                    });

                    this.on("error", function(file, errorMessage) {
                        Swal.fire("Upload failed", "", "error");
                        console.error("Upload error:", errorMessage);
                    });

                    submitBtn.addEventListener("click", () => {
                        if (this.files.length > 0) {
                            this.processQueue();
                        }
                    });
                }
            });
        },

        bindEvents: function() {
            const self = this;

            // Delete button handler
            $(document).on('click', '.delete-btn', function() {
                const id = $(this).data('id');
                self.delete(id);
            });
        }

    };

    $(document).ready(function() {
        documentation.init();
    });
</script>