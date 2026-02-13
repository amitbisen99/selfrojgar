@extends($adminTheme)

@section("title")
    Website Settings
@endsection

@section("wrapper")
    <section class="app-user-list">
        <div class="card">
            <div class="card-body border-bottom">
                <div class="row" style="margin-bottom: -20px;">
                    <div class="col-md-12">
                        <h4 class="card-title">Website Settings</h4>
                    </div>
                </div>
            </div>
            <div class="card-body p-2 pt-0">
                <form action="{{ route('dashboard.setting.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        @foreach ($settings as $setting)
                            <div class="col-6 mt-2">
                                @if ($setting['type'] == 'file')
                                    <label for="{{ $setting['input_id'] }}"><b>{{ $setting['lable'] }}</b></label>
                                    <div class="file-input-group">
                                        <input type="{{ $setting['type'] }}" name="{{ $setting['key'] }}" id="{{ $setting['input_id'] }}" class="form-control image-input">
                                        <img src="{{ asset($setting['value']) }}" class="image-input-preview" alt="Image Preview">
                                    </div>
                                @else    
                                    <label for="{{ $setting['input_id'] }}"><b>{{ $setting['lable'] }}</b></label>
                                    <input type="{{ $setting['type'] }}" name="{{ $setting['key'] }}" value="{{ $setting['value'] }}" id="{{ $setting['input_id'] }}" class="form-control">
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="row">
                        <div class="col-12 text-center">
                            <button class="btn btn-success">Update</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </section>
@endsection