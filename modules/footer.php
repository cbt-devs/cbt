<!-- Bootstrap JS bundle -->
<script src="assets/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>

<!-- ChartJS -->
<script src="assets/chartjs/dist/chart.umd.js"></script>

<!-- Your inline script -->
<script>
$(document).ready(function() {
    $('.load-content').on('click', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        $.get(page, function(data) {
            $('.contents').html(data);
            if ($('#example').length) {
                $('#example').DataTable();
            }
        });
    });
});
</script>