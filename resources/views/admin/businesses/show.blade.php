@extends($adminTheme)

@section("title")
    Businesses
@endsection

@section("wrapper")
    <section class="app-user-list">
        <div class="card">
            <div class="card-body border-bottom">
                <div class="row" style="margin-bottom: -20px;">
                    <div class="col-md-10">
                        <h4 class="card-title">Businesses</h4>
                    </div>
                    <div class="col-md-2" style="text-align:right;">
                            <a href="{{ route('businesses.index') }}" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Create">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            </a>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive p-2 pt-0">
                <table class="user-list-table table">
                    <tr>
                        <th>Name</th>
                        <td>{{ $business->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>{{ $business->owner->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <td>{{ $business->businessCategory->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>City</th>
                        <td>{{ $business->city->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>State</th>
                        <td>{{ $business->state->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <td>{{ $business->country->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>About</th>
                        <td>{{ $business->detail ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $business->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pin Code</th>
                        <td>{{ $business->pincode ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>From Railway</th>
                        <td>{{ $business->open_time ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>From BusStand</th>
                        <td>{{ $business->close_time ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Latitude</th>
                        <td>{{ $business->latitude ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Longitude</th>
                        <td>{{ $business->longitude ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Images</th>
                        <td>
                            @php
                                $images = [];
                                if (!is_null($business->images) && !empty($business->images)) {
                                    $images = explode(' || ', $business->images);
                                }
                            @endphp
                            @if (!empty($images))
                                @foreach ($images as $image)
                                    <img src="{{ asset($image) }}" height="100px" width="100px"> 
                                @endforeach
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </section>
@endsection