@extends($adminTheme)

@section("title")
    Franchise Business
@endsection

@section("wrapper")
    <section class="app-user-list">
        <div class="card">
            <div class="card-body border-bottom">
                <div class="row" style="margin-bottom: -20px;">
                    <div class="col-md-10">
                        <h4 class="card-title">Franchise Business</h4>
                    </div>
                    <div class="col-md-2" style="text-align:right;">
                            <a href="{{ route('on-demand-service.index') }}" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Create">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            </a>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive p-2 pt-0">
                <table class="user-list-table table">
                    <tr>
                        <th>Name</th>
                        <td>{{ $franchiseBusiness->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>{{ $franchiseBusiness->owner->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Mobile No.</th>
                        <td>{{ $franchiseBusiness->mobile ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Franchise Category</th>
                        <td>{{ $franchiseBusiness->franchiseCategory->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>City</th>
                        <td>{{ $franchiseBusiness->city->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>State</th>
                        <td>{{ $franchiseBusiness->state->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <td>{{ $franchiseBusiness->country->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $franchiseBusiness->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $franchiseBusiness->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pin Code</th>
                        <td>{{ $franchiseBusiness->pin_code ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Industry Experience</th>
                        <td>{{ $franchiseBusiness->industry_experience ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>investment</th>
                        <td>{{ $franchiseBusiness->investment ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>other</th>
                        <td>{{ $franchiseBusiness->other ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Images</th>
                        <td>
                            @php
                                $images = [];
                                if (!is_null($franchiseBusiness->images) && !empty($franchiseBusiness->images)) {
                                    $images = explode(' || ', $franchiseBusiness->images);
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