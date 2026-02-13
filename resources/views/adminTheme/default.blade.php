<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset($webSetting['favicon_icone']) }}">
    <title>@yield("title")</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css">
    @include("adminTheme.css")
    @yield("style")
</head>

 <body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" style="width:100% !important;" data-col="">
    @include("adminTheme.header")
    @include("adminTheme.sidebar")
    
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
            </div>
            <div class="content-body">
                 @yield("wrapper")
            </div>
        </div>
    </div>
    <div class="sidenav-overlay"></div>
    
    <div class="drag-target"></div>
    
    @include('adminTheme.alert')

    @include('adminTheme.footer')
    
    @include("adminTheme.script")
    
    @yield("script")
    <!-- Include jQuery Toast Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
    <script src="{{ asset('adminTheme/custom/jquery.form.min.js') }}"></script>
    
    <script type="text/javascript">
        var token = $('meta[name="csrf-token"]').attr('content');
    </script>



</body>
</html>