@extends($adminTheme)

@section("title")
    On Demand Service
@endsection

@section("wrapper")
    <section class="app-user-list">
        <div class="card">
            <div class="card-body border-bottom">
                <div class="row" style="margin-bottom: -20px;">
                    <div class="col-md-10">
                        <h4 class="card-title">On Demand Service</h4>
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
                        <td>{{ $onDemandService->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>{{ $onDemandService->serviceProvider->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Service Category</th>
                        <td>{{ $onDemandService->onDemandCategory->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $onDemandService->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $onDemandService->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Amount</th>
                        <td>{{ $onDemandService->amount ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>City</th>
                        <td>{{ $onDemandService->city->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>State</th>
                        <td>{{ $onDemandService->state->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <td>{{ $onDemandService->country->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $onDemandService->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pin Code</th>
                        <td>{{ $onDemandService->pincode ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Images</th>
                        <td>
                            @php
                                $images = [];
                                if (!is_null($onDemandService->images) && !empty($onDemandService->images)) {
                                    $images = explode(' || ', $onDemandService->images);
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