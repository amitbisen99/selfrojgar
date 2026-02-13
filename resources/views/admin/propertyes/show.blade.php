@extends($adminTheme)

@section("title")
    property
@endsection

@section("wrapper")
    <section class="app-user-list">
        <div class="card">
            <div class="card-body border-bottom">
                <div class="row" style="margin-bottom: -20px;">
                    <div class="col-md-10">
                        <h4 class="card-title">property</h4>
                    </div>
                    <div class="col-md-2" style="text-align:right;">
                            <a href="{{ route('propertyes.index') }}" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Create">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            </a>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive p-2 pt-0">
                <table class="user-list-table table">
                    <tr>
                        <th>Name</th>
                        <td>{{ $property->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>{{ $property->owner->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Category</th>
                        <td>{{ $property->propertyCategory->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Contact No.</th>
                        <td>{{ $property->contactno ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Property for</th>
                        <td>{{ $property->property_for ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Area Value</th>
                        <td>{{ $property->area_value ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Area for</th>
                        <td>{{ $property->area_for ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Area Price For</th>
                        <td>{{ $property->area_price_for ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Area Price</th>
                        <td>{{ $property->area_price ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Total Price</th>
                        <td>{{ $property->total_price ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Rent Price</th>
                        <td>{{ $property->rent_price ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Rent Price Type</th>
                        <td>{{ $property->rent_price_type ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>City</th>
                        <td>{{ $property->city->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>State</th>
                        <td>{{ $property->state->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <td>{{ $property->country->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $property->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pin Code</th>
                        <td>{{ $property->pincode ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Dimension</th>
                        <td>{{ $property->dimension ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Latitude</th>
                        <td>{{ $property->latitude ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Longitude</th>
                        <td>{{ $property->longitude ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Images</th>
                        <td>
                            @php
                                $images = [];
                                if (!is_null($property->images) && !empty($property->images)) {
                                    $images = explode(' || ', $property->images);
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