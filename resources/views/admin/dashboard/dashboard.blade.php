@extends($adminTheme)

@section("title")
    Dashboard
@endsection

@section("style")
    <style type="text/css">
        .icon{
            font-size: 25px;
        }
    </style>
@endsection
@section("wrapper")
    hi {{ auth()->user()->name }}
@endsection