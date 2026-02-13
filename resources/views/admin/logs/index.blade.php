@extends($adminTheme)

@section("title")
    Logs
@endsection

@section('style')
    <style type="text/css">
        pre {
            display: block;
            padding: 9.5px;
            margin: 0 0 10px;
            font-size: 13px;
            line-height: 1.42857143;
            color: #333;
            word-break: break-all;
            word-wrap: break-word;
            background-color: #f5f5f5;   
        }
        .scrollable-container {
            overflow: auto;
            max-height: 750px;
            margin-top: 30px;
        }
        .log-header-position{
            background-color: white;
            margin-bottom: 20px;
            padding: 10px 10px;
        }
    </style>
@endsection

@section("wrapper")
    <section class="app-user-list">
        <div class="card">
            <div class="card-body border-bottom">
                <div class="row" style="margin-bottom: -20px;">
                    <div class="col-md-10">
                        <h4 class="card-title">Logs</h4>
                    </div>
                    <div class="col-md-2" style="text-align:right;">
                        <a href="{{ route('clear.logs') }}" class="btn btn-danger head-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Clear Log"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                    </div>
                </div>
                <div class="scrollable-container">
                    <div>
                        @foreach($arr as $key => $value)
                            @if($value != '.gitignore')
                                <pre>{{ Storage::disk('logs')->get($value) }}</pre>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section("script")
    
@endsection 