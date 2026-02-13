@extends($adminTheme)

@section("title")
    Tourism Business
@endsection

@section("wrapper")
    <section class="app-user-list">
        <div class="card">
            <div class="card-body border-bottom">
                <div class="row" style="margin-bottom: -20px;">
                    <div class="col-md-10">
                        <h4 class="card-title">Tourism Business</h4>
                    </div>
                    <div class="col-md-2" style="text-align:right;">
                            <a href="{{ route('tourism-business.index') }}" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Create">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            </a>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive p-2 pt-0">
                <table class="user-list-table table">
                    <tr>
                        <th>Name</th>
                        <td>{{ $tourism->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>{{ $tourism->owner->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <td>{{ $tourism->tourismCategory->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>City</th>
                        <td>{{ $tourism->city->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>State</th>
                        <td>{{ $tourism->state->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <td>{{ $tourism->country->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>About</th>
                        <td>{{ $tourism->about ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $tourism->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pin Code</th>
                        <td>{{ $tourism->pincode ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>From Railway</th>
                        <td>{{ $tourism->from_railway ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>From BusStand</th>
                        <td>{{ $tourism->from_bus_stand ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Latitude</th>
                        <td>{{ $tourism->latitude ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Longitude</th>
                        <td>{{ $tourism->longitude ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Images</th>
                        <td>
                            @php
                                $images = [];
                                if (!is_null($tourism->images) && !empty($tourism->images)) {
                                    $images = explode(' || ', $tourism->images);
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