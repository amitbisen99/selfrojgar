<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="{{ asset('adminTheme/assets/js/toastr.min.js') }}"></script>

<link rel="icon" href="{{ asset('favicon.ico') }}" type="image/ioc" />
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

<!-- BEGIN: Vendor CSS-->
<!-- <link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/vendors/css/vendors.min.css') }}"> -->
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/vendors/css/vendors2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/vendors/css/charts/apexcharts.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/vendors/css/extensions/toastr.min.css') }}">
<!-- END: Vendor CSS-->

<!-- BEGIN: Theme CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/css/bootstrap.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/css/bootstrap-extended.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/css/colors.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/css/components.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/css/themes/dark-layout.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/css/themes/bordered-layout.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/css/themes/semi-dark-layout.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/css/custom.css') }}">

<!-- BEGIN: Page CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/css/core/menu/menu-types/vertical-menu.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/css/pages/dashboard-ecommerce.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/css/plugins/charts/chart-apex.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/css/plugins/extensions/ext-component-toastr.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/css/pages/ui-feather.min.css') }}">
<!-- END: Page CSS-->

<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/vendors/css/tables/datatable/buttons.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/vendors/css/tables/datatable/rowGroup.bootstrap5.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/css/plugins/forms/form-validation.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/vendors/css/file-uploaders/dropzone.min.css') }}">


<!-- BEGIN: Custom CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/vendors/css/pickers/pickadate/pickadate.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/css/plugins/forms/pickers/form-pickadate.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/app-assets/css/plugins/forms/form-file-uploader.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('adminTheme/custom/coustomeStyle.css') }}">

<!-- END: Custom CSS-->

<!-- Font Owsome -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Bootstrap CSS -->

<link href="{{asset('adminTheme/assets/css/sweetalert2.css') }}" rel="stylesheet">
{{-- <link rel="stylesheet" href="{{ asset('adminTheme/assets/css/toastr.min.css') }}"> --}}
<style type="text/css">
    table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:after {
        background-image: unset !important; 
    }
    table.dataTable thead .sorting:before, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_desc:before {
        background-image: unset !important; 
    }
</style>
<style type="text/css">
	.zoho-search-filter{
		position: relative;
		left: 480px;
	}
	.search-box{
		display: flex;
	}

	.select2-container--default .select2-selection--single .select2-selection__arrow{
		display:none;
	}
</style>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">