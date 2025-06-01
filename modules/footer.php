<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css" rel="stylesheet">

<script>
$(document).ready(function() {
    // Handle dynamic page loading
    $('.load-content').on('click', function(e) {
        e.preventDefault();

        var page = $(this).data('page');

        $.get(page, function(data) {
            $('.contents').html(data);

            // Re-initialize DataTable if the table exists in loaded content
            if ($('#example').length) {
                $('#example').DataTable();
            }
        });
    });
});
</script>
