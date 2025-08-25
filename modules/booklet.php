<?php
require_once __DIR__ . '/../init.php';
$member_male_r = $member->show(_gender: 'male');
?>

<style>
.nice-select {
    width: 100% !important;
    min-width: 200px;
    /* optional: adjust based on your layout */
    max-width: 100%;
    white-space: nowrap;
}
</style>

<div class="d-flex justify-content-between align-items-start">
    <div>
        <h2>Booklet Management</h2>
        <p>Manage and edit the content of your booklets here.</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa-solid fa-plus"></i> Create booklet
        </button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa-solid fa-book"></i> Show Latest
        </button>
    </div>
</div>

<table id="bookletTable" class="table table-striped" style="width:100%"></table>

<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header pb-0" style="border-bottom: none;">
                <h5 class="modal-title" id="addModalLabel">Create Booklet</h5>
            </div>

            <form id="addForm">
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="1" selected>Weekly</option>
                                <option value="2">Birthday</option>
                                <option value="3">Wedding</option>
                                <option value="4">Funeral</option>
                            </select>
                        </div>
                    </div>

                    <!-- SmartWizard Steps -->
                    <div id="smartwizard">
                        <ul class="nav">
                            <li><a class="nav-link" href="#step-1">Front Page</a></li>
                            <li><a class="nav-link" href="#step-2">First Page</a></li>
                            <li><a class="nav-link" href="#step-3">Second Page</a></li>
                            <li><a class="nav-link" href="#step-4">Third Page</a></li>
                        </ul>

                        <div class="tab-content mt-3">
                            <!-- Step 1 -->
                            <div id="step-1" class="tab-pane" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control" id="title" name="title" value=""
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="monthly_theme" class="form-label">Monthly Theme</label>
                                        <input type="text" class="form-control" id="monthly_theme" name="monthly_theme"
                                            value="">
                                    </div>
                                </div>
                                <div class="row g-3 mt-2">
                                    <div class="col-md-3">
                                        <select id="bookSelect">
                                            <option value="">Select Book</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select id="chapterSelect">
                                            <option value="">Select Chapter</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select id="verseSelect">
                                            <option value="">Select Verse</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row g-3 mt-2">
                                    <div class="col-md-4">
                                        <label for="date" class="form-label">Date</label>
                                        <input type="date" class="form-control" id="date" name="date"
                                            value="<?php echo date('Y-m-d', strtotime('next sunday')); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div id="step-2" class="tab-pane" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="weTheChurch" class="form-label">We the church</label>
                                        <textarea class="form-control" id="weTheChurch" name="weTheChurch" rows="10 "
                                            required>That we henceforth be no more children, tossed to and fro, and carried about with every wind of doctrine, by the sleight of men, and cunning craftiness, whereby they lie in wait to deceive But speaking the truth in love, may grow up into him in all things, which is the head, even Christ: From whom the whole body fitly joined together and compacted by that which every joint supplieth, according to the effectual working in the measure of every part, maketh increase of the body unto the edifying of itself in love.
                                        </textarea>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="believes" class="form-label">CBT Believes</label>
                                        <textarea class="form-control" id="believes" name="believes" rows="5" required>
In the absolute inspiration, infallibility, and inerrancy of the Holy Scripture, the Trinity - FATHER, SON and HOLY SPIRIT - three independent persons and yet one person, the One true God. The incarnation, virgin birth, sinless life, death, burial and resurrection of Jesus Christ, salvation by faith, based upon Christ's atoning work on the cross of Calvary
    </textarea>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="goal" class="form-label">CBT Goal</label>
                                        <textarea class="form-control" id="goal" name="goal" rows="3" required>
Praying and aiming for 500 faithful members in year 2030
    </textarea>
                                    </div>

                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div id="step-3" class="tab-pane" role="tabpanel">
                                <h5>Morning Divine Worship Service</h5>
                                <div class="row g-3">
                                    <!-- I. Song Leader -->
                                    <div class="col-md-6">
                                        <label for="song_leader" class="form-label">Song Leader</label>
                                        <select class="form-control nice-select2" id="morning_song_leader"
                                            name="morning_song_leader">
                                            <option value="">Select member</option>
                                            <?php if (!empty($member_male_r)) : ?>
                                            <?php foreach ($member_male_r as $member) : ?>
                                            <option value="<?= htmlspecialchars($member['id']) ?>">
                                                <?= htmlspecialchars($member['name']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php else : ?>
                                            <option value="">No members found</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <!-- II. Welcome Remarks -->
                                    <div class="col-md-6">
                                        <label for="welcome_remarks" class="form-label">I. Welcome Remarks</label>
                                        <select class="form-control nice-select2" id="morning_welcome_remarks"
                                            name="morning_welcome_remarks">
                                            <option value="">Select member</option>
                                            <?php if (!empty($member_male_r)) : ?>
                                            <?php foreach ($member_male_r as $member) : ?>
                                            <option value="<?= htmlspecialchars($member['id']) ?>">
                                                <?= htmlspecialchars($member['name']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php else : ?>
                                            <option value="">No members found</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3 g-3">
                                    <!-- III. Bible Reading -->
                                    <div class="col-md-6">
                                        <label for="morning_bible_reading" class="form-label">II. Bible Reading</label>
                                        <select class="form-control nice-select2" id="morning_bible_reading"
                                            name="morning_bible_reading">
                                            <option value="">Select member</option>
                                            <?php if (!empty($member_male_r)) : ?>
                                            <?php foreach ($member_male_r as $member) : ?>
                                            <option value="<?= htmlspecialchars($member['id']) ?>">
                                                <?= htmlspecialchars($member['name']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php else : ?>
                                            <option value="">No members found</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <!-- IV. Invocation -->
                                    <div class="col-md-6">
                                        <label for="morning_invocation" class="form-label">III. Invocation</label>
                                        <select class="form-control nice-select2" id="morning_invocation"
                                            name="morning_invocation">
                                            <option value="">Select member</option>
                                            <?php if (!empty($member_male_r)) : ?>
                                            <?php foreach ($member_male_r as $member) : ?>
                                            <option value="<?= htmlspecialchars($member['id']) ?>">
                                                <?= htmlspecialchars($member['name']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php else : ?>
                                            <option value="">No members found</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3 g-3">
                                    <!-- V. Statement of Faith -->
                                    <div class="col-md-6">
                                        <label for="morning_statement_of_faith" class="form-label">IV. Statement of
                                            Faith</label>
                                        <select class="form-control nice-select2" id="morning_statement_of_faith"
                                            name="morning_statement_of_faith">
                                            <option value="">Select member</option>
                                            <?php if (!empty($member_male_r)) : ?>
                                            <?php foreach ($member_male_r as $member) : ?>
                                            <option value="<?= htmlspecialchars($member['id']) ?>">
                                                <?= htmlspecialchars($member['name']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php else : ?>
                                            <option value="">No members found</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <!-- VI. Hymnals -->
                                    <div class="col-md-6">
                                        <label class="form-label">V. Hymnals</label>
                                        <?php for ($i = 1; $i <= 2; $i++): ?>
                                        <input type="text" class="form-control mb-2" id="morning_hymnals<?= $i ?>"
                                            name="morning_hymnals[]" placeholder="Hymnal <?= $i ?>" required>
                                        <?php endfor; ?>
                                    </div>
                                </div>

                                <div class="row mt-3 g-3">
                                    <!-- VII. Second Statement of Faith -->
                                    <div class="col-md-6">
                                        <label for="morning_statement_of_faith" class="form-label">VI. Statement of
                                            Faith</label>
                                        <select class="form-control nice-select2" id="morning_statement_of_faith"
                                            name="morning_statement_of_faith">
                                            <option value="">Select member</option>
                                            <?php if (!empty($member_male_r)) : ?>
                                            <?php foreach ($member_male_r as $member) : ?>
                                            <option value="<?= htmlspecialchars($member['id']) ?>">
                                                <?= htmlspecialchars($member['name']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php else : ?>
                                            <option value="">No members found</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <!-- VIII. Special Song of Praise -->
                                    <div class="col-md-6">
                                        <label for="morning_special_song" class="form-label">VII. Special Song of
                                            Praise</label>
                                        <select class="form-control nice-select2" id="morning_special_song"
                                            name="morning_special_song">
                                            <option value="">Select member</option>
                                            <?php if (!empty($member_male_r)) : ?>
                                            <?php foreach ($member_male_r as $member) : ?>
                                            <option value="<?= htmlspecialchars($member['id']) ?>">
                                                <?= htmlspecialchars($member['name']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php else : ?>
                                            <option value="">No members found</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3 g-3">
                                    <!-- IX. God's Messenger -->
                                    <div class="col-md-6">
                                        <label for="morning_Gods_messenger" class="form-label">VIII. God's
                                            Messenger</label>
                                        <select class="form-control nice-select2" id="morning_Gods_messenger"
                                            name="morning_Gods_messenger">
                                            <option value="">Select member</option>
                                            <?php if (!empty($member_male_r)) : ?>
                                            <?php foreach ($member_male_r as $member) : ?>
                                            <option value="<?= htmlspecialchars($member['id']) ?>">
                                                <?= htmlspecialchars($member['name']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php else : ?>
                                            <option value="">No members found</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <!-- X. Offertory Prayer -->
                                    <div class="col-md-6">
                                        <label for="morning_offertory_prayer" class="form-label">IX. Offertory
                                            Prayer</label>
                                        <select class="form-control nice-select2" id="morning_offertory_prayer"
                                            name="morning_offertory_prayer">
                                            <option value="">Select member</option>
                                            <?php if (!empty($member_male_r)) : ?>
                                            <?php foreach ($member_male_r as $member) : ?>
                                            <option value="<?= htmlspecialchars($member['id']) ?>">
                                                <?= htmlspecialchars($member['name']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php else : ?>
                                            <option value="">No members found</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3 g-3">
                                    <!-- XI. Announcement -->
                                    <div class="col-md-6">
                                        <label for="announcement" class="form-label">X. Announcement</label>
                                    </div>

                                    <!-- XII. Benediction/Postlude -->
                                    <div class="col-md-6">
                                        <label for="benediction" class="form-label">XI. Benediction/Postlude</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 4 -->
                            <div id="step-4" class="tab-pane" role="tabpanel">
                                <h5>Afternoon Divine Worship Service</h5>
                                <div class="row g-3">
                                    <!-- I. Song Leader -->
                                    <div class="col-md-6">
                                        <label for="afternoon_song_leader" class="form-label">Song Leader</label>
                                        <select class="form-control nice-select2" id="afternoon_song_leader"
                                            name="afternoon_song_leader">
                                            <option value="">Select member</option>
                                            <?php if (!empty($member_male_r)) : ?>
                                            <?php foreach ($member_male_r as $member) : ?>
                                            <option value="<?= htmlspecialchars($member['id']) ?>">
                                                <?= htmlspecialchars($member['name']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php else : ?>
                                            <option value="">No members found</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <!-- II. Welcome Remarks -->
                                    <div class="col-md-6">
                                        <label for="afternoon_welcome_remarks" class="form-label">I. Welcome
                                            Remarks</label>
                                        <select class="form-control nice-select2" id="afternoon_welcome_remarks"
                                            name="afternoon_welcome_remarks">
                                            <option value="">Select member</option>
                                            <?php if (!empty($member_male_r)) : ?>
                                            <?php foreach ($member_male_r as $member) : ?>
                                            <option value="<?= htmlspecialchars($member['id']) ?>">
                                                <?= htmlspecialchars($member['name']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php else : ?>
                                            <option value="">No members found</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3 g-3">
                                    <!-- III. Bible Reading -->
                                    <div class="col-md-6">
                                        <label for="afternoon_bible_reading" class="form-label">II. Bible
                                            Reading</label>
                                        <select class="form-control nice-select2" id="afternoon_bible_reading"
                                            name="afternoon_bible_reading">
                                            <option value="">Select member</option>
                                            <?php if (!empty($member_male_r)) : ?>
                                            <?php foreach ($member_male_r as $member) : ?>
                                            <option value="<?= htmlspecialchars($member['id']) ?>">
                                                <?= htmlspecialchars($member['name']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php else : ?>
                                            <option value="">No members found</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <!-- IV. Invocation -->
                                    <div class="col-md-6">
                                        <label for="afternoon_invocation" class="form-label">III. Invocation</label>
                                        <select class="form-control nice-select2" id="afternoon_invocation"
                                            name="afternoon_invocation">
                                            <option value="">Select member</option>
                                            <?php if (!empty($member_male_r)) : ?>
                                            <?php foreach ($member_male_r as $member) : ?>
                                            <option value="<?= htmlspecialchars($member['id']) ?>">
                                                <?= htmlspecialchars($member['name']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php else : ?>
                                            <option value="">No members found</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3 g-3">
                                    <!-- V. Statement of Faith -->
                                    <div class="col-md-6">
                                        <label for="afternoon_statement_of_faith" class="form-label">IV. Statement of
                                            Faith</label>
                                        <select class="form-control nice-select2" id="afternoon_statement_of_faith"
                                            name="afternoon_statement_of_faith">
                                            <option value="">Select member</option>
                                            <?php if (!empty($member_male_r)) : ?>
                                            <?php foreach ($member_male_r as $member) : ?>
                                            <option value="<?= htmlspecialchars($member['id']) ?>">
                                                <?= htmlspecialchars($member['name']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php else : ?>
                                            <option value="">No members found</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <!-- VI. Hymnals -->
                                    <div class="col-md-6">
                                        <label class="form-label">V. Hymnals</label>
                                        <?php for ($i = 1; $i <= 2; $i++): ?>
                                        <input type="text" class="form-control mb-2" id="afternoon_hymnals<?= $i ?>"
                                            name="afternoon_hymnals[]" placeholder="Hymnal <?= $i ?>" required>
                                        <?php endfor; ?>
                                    </div>
                                </div>

                                <div class="row mt-3 g-3">
                                    <!-- VII. Second Statement of Faith -->
                                    <div class="col-md-6">
                                        <label for="afternoon_statement_of_faith" class="form-label">VI. Statement of
                                            Faith</label>
                                        <select class="form-control nice-select2" id="afternoon_statement_of_faith"
                                            name="afternoon_statement_of_faith">
                                            <option value="">Select member</option>
                                            <?php if (!empty($member_male_r)) : ?>
                                            <?php foreach ($member_male_r as $member) : ?>
                                            <option value="<?= htmlspecialchars($member['id']) ?>">
                                                <?= htmlspecialchars($member['name']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php else : ?>
                                            <option value="">No members found</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <!-- VIII. Special Song of Praise -->
                                    <div class="col-md-6">
                                        <label for="afternoon_special_song" class="form-label">VII. Special Song of
                                            Praise</label>
                                        <select class="form-control nice-select2" id="afternoon_special_song"
                                            name="afternoon_special_song">
                                            <option value="">Select member</option>
                                            <?php if (!empty($member_male_r)) : ?>
                                            <?php foreach ($member_male_r as $member) : ?>
                                            <option value="<?= htmlspecialchars($member['id']) ?>">
                                                <?= htmlspecialchars($member['name']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php else : ?>
                                            <option value="">No members found</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3 g-3">
                                    <!-- IX. God's Messenger -->
                                    <div class="col-md-6">
                                        <label for="afternoon_Gods_messenger" class="form-label">VIII. God's
                                            Messenger</label>
                                        <select class="form-control nice-select2" id="afternoon_Gods_messenger"
                                            name="afternoon_Gods_messenger">
                                            <option value="">Select member</option>
                                            <?php if (!empty($member_male_r)) : ?>
                                            <?php foreach ($member_male_r as $member) : ?>
                                            <option value="<?= htmlspecialchars($member['id']) ?>">
                                                <?= htmlspecialchars($member['name']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php else : ?>
                                            <option value="">No members found</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <!-- X. Offertory Prayer -->
                                    <div class="col-md-6">
                                        <label for="afternoon_offertory_prayer" class="form-label">IX. Offertory
                                            Prayer</label>
                                        <select class="form-control nice-select2" id="afternoon_offertory_prayer"
                                            name="afternoon_offertory_prayer">
                                            <option value="">Select member</option>
                                            <?php if (!empty($member_male_r)) : ?>
                                            <?php foreach ($member_male_r as $member) : ?>
                                            <option value="<?= htmlspecialchars($member['id']) ?>">
                                                <?= htmlspecialchars($member['name']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                            <?php else : ?>
                                            <option value="">No members found</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mt-3 g-3">
                                    <!-- XI. Announcement -->
                                    <div class="col-md-6">
                                        <label for="announcement" class="form-label">X. Announcement</label>
                                    </div>

                                    <!-- XII. Benediction/Postlude -->
                                    <div class="col-md-6">
                                        <label for="benediction" class="form-label">XI. Benediction/Postlude</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wizard buttons -->
                <div class="modal-footer d-flex justify-content-between" style="border-top: none;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <div>
                        <button type="button" id="prevBtn" class="btn btn-outline-primary">Previous</button>
                        <button type="button" id="nextBtn" class="btn btn-outline-primary">Next</button>
                        <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
var bookletTable = {
    bibleData: {},
    init: function() {
        this.show();
        this.bindEvents();

        this.fetchBibleData();

    },

    show: function() {
        $.ajax({
            type: "POST",
            url: "controller/main.php",
            data: {
                action: "show",
                type: "booklet"
            },
            success: function(response) {
                const data = response.data;

                if ($.fn.dataTable.isDataTable('#bookletTable')) {
                    $('#bookletTable').DataTable().clear().destroy();
                }

                $('#bookletTable').DataTable({
                    data: data,
                    columns: [{
                            data: 'type',
                            title: 'Type'
                        },
                        {
                            data: 'date',
                            title: 'Date'
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return `
                                        <button class="btn btn-warning btn-sm edit-btn" data-id="${row.id}">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    `;
                            },
                            orderable: false,
                            searchable: false,
                            title: 'Actions'
                        }
                    ],
                    initComplete: function() {
                        JsLoadingOverlay.hide();
                    }
                });
            }
        });
    },

    bindEvents: function() {
        $(document).ready(function() {
            $('#smartwizard').smartWizard({
                selected: 0,
                theme: 'arrows',
                justified: true,
                autoAdjustHeight: true,
                backButtonSupport: true,
                transition: {
                    animation: 'slide-horizontal'
                },
                toolbar: {
                    showNextButton: false,
                    showPreviousButton: false
                }
            }).on("showStep", function(e, anchorObject, stepIndex, stepDirection, stepPosition) {
                const $nextBtn = $('#nextBtn');
                const $prevBtn = $('#prevBtn');
                const $submitBtn = $('#submitBtn');

                // Hide all buttons first
                $prevBtn.hide();
                $nextBtn.hide();
                $submitBtn.hide();

                if (stepPosition === 'first') {
                    $nextBtn.show();
                } else if (stepPosition === 'middle') {
                    $prevBtn.show();
                    $nextBtn.show();
                } else if (stepPosition === 'last') {
                    $prevBtn.show();
                    $submitBtn.show();
                    bookletTable.populateReview();
                }
            });

            // Manual wizard navigation
            $('#nextBtn').on('click', function() {
                // const step = bookletTable.validate();
                // if (!step) return;
                $('#smartwizard').smartWizard("next");
            });

            $('#prevBtn').on('click', function() {
                $('#smartwizard').smartWizard("prev");
            });

            $('#addModal').on('hidden.bs.modal', function() {
                $('#smartwizard').smartWizard("reset"); // reset to first step
            });
        });
    },

    validate: function() {
        // Get current step index from SmartWizard
        let stepIndex = $('#smartwizard').smartWizard("getStepIndex"); // <-- returns number
        let currentStep = stepIndex + 1;
        let valid = true;

        $('#step-' + currentStep + ' :input[required]').each(function() {
            if (!this.value) {
                $(this).addClass("is-invalid");
                valid = false;
            } else {
                $(this).removeClass("is-invalid");
            }
        });

        return valid;
    },

    populateReview: function() {
        $("#reviewSummary").html(`
                <p><strong>Title:</strong> ${$("#title").val()}</p>
                <p><strong>Theme:</strong> ${$("#monthly_theme").val()}</p>
                <p><strong>Bible Verse:</strong> ${$("#bible_verse").val()}</p>
                <p><strong>Date:</strong> ${$("#date").val()}</p>
                <p><strong>Address:</strong> ${$("#addressLine").val()}, ${$("#city").val()}, ${$("#state").val()} ${$("#postalCode").val()}</p>
            `);
    },

    fetchBibleData: function() {
        fetch("assets/kjvbible.txt")
            .then(response => response.text())
            .then(text => {
                const booksJson = text.split(/(?=\{"book":)/).map(obj => JSON.parse(obj));
                console.log(booksJson);

                booksJson.forEach(book => {
                    const bookName = book.book;
                    bookletTable.bibleData[bookName] = {};
                    book.chapters.forEach(chap => {
                        const chapNum = chap.chapter;
                        bookletTable.bibleData[bookName][chapNum] = {};
                        chap.verses.forEach(v => {
                            bookletTable.bibleData[bookName][chapNum][v.verse] = v
                                .text;
                        });
                    });
                });
                bookletTable.populateBooks();
            });
    },

    populateBooks: function() {
        const bookSelect = document.getElementById("bookSelect");
        bookSelect.innerHTML = `<option value="">Select Book</option>`;
        const books = Object.keys(bookletTable.bibleData);

        books.forEach(book => {
            bookSelect.innerHTML += `<option value="${book}">${book}</option>`;
        });

        bookletTable.refreshNiceSelect(bookSelect);

        if (books.length > 0) {
            bookSelect.value = books[0];
            bookletTable.populateChapters(books[0]);
        }

        bookSelect.addEventListener("change", () => {
            bookletTable.populateChapters(bookSelect.value);
        });
    },

    populateChapters: function(book) {
        const chapterSelect = document.getElementById("chapterSelect");
        chapterSelect.innerHTML = `<option value="">Select Chapter</option>`;
        const chapters = Object.keys(bookletTable.bibleData[book]);

        chapters.forEach(chapter => {
            chapterSelect.innerHTML += `<option value="${chapter}">${chapter}</option>`;
        });

        bookletTable.refreshNiceSelect(chapterSelect);

        if (chapters.length > 0) {
            chapterSelect.value = chapters[0];
            bookletTable.populateVerses(book, chapters[0]);
        }

        chapterSelect.addEventListener("change", () => {
            bookletTable.populateVerses(book, chapterSelect.value);
        });
    },

    populateVerses: function(book, chapter) {
        const verseSelect = document.getElementById("verseSelect");
        verseSelect.innerHTML = `<option value="">Select Verse</option>`;
        const verses = Object.keys(bookletTable.bibleData[book][chapter]);

        verses.forEach(verse => {
            verseSelect.innerHTML += `<option value="${verse}">${verse}</option>`;
        });

        bookletTable.refreshNiceSelect(verseSelect);

        if (verses.length > 0) {
            verseSelect.value = verses[0];
        }
    },

    refreshNiceSelect: function(selectElement) {
        const wrapper = selectElement.nextElementSibling;
        if (wrapper && wrapper.classList.contains("nice-select")) wrapper.remove();
        selectElement.style.display = "";
        NiceSelect.bind(selectElement, {
            searchable: true
        });
    },
}

$(document).ready(function() {
    bookletTable.init();
});
</script>