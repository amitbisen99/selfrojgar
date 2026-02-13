<script src="{{ asset('adminTheme/app-assets/vendors/js/vendors.min.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/vendors/js/tables/datatable/jszip.min.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/vendors/js/forms/cleave/cleave.min.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/vendors/js/forms/cleave/addons/cleave-phone.us.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/js/scripts/extensions/ext-component-toastr.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/js/core/app-menu.min.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/js/core/app.min.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/js/scripts/customizer.min.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('adminTheme/app-assets/vendors/js/file-uploaders/dropzone.min.js') }}"></script>
<script src="{{ asset('adminTheme/assets/js/counter.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('adminTheme/app-assets/js/scripts/pickers/dateTime/pick-a-datetime.js') }}"></script>
<script src="{{ asset('adminTheme/custom/coustomeScript.js') }}"></script>

<script>
    $(window).on('load',  function(){
        if (feather) {
            feather.replace({ width: 14, height: 14 });
        }
    })

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

<!-- script for ticket module -->
<script type="text/javascript">
    // Opne all Advance Fillter
    $(document).ready(function() {
        $('body').find('.collapse').addClass('show');
    });

    $('body').on('change', '.status-switch', function(){
        var id = $(this).attr('data-id');
        var url = $(this).attr('data-action');
        
        if ($(this).is(':checked')) {
            var status = 1;
        }
        else {
            var status = 0;
        }

        $.ajax({
            url: url,
            type: "POST",
            data: {
                status:status,
                id,id,
            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                location.reload();
            }
        });

    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>

<script src="{{ asset('adminTheme/assets/js/sweetalert2.js') }}"></script>
<script src="{{ asset('adminTheme/assets/js/delete.js') }}"></script>