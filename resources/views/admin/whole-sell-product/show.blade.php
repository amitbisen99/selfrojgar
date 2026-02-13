@extends($adminTheme)

@section("title")
    Whole Sell Product
@endsection

@section("wrapper")
    <section class="app-user-list">
        <div class="card">
            <div class="card-body border-bottom">
                <div class="row" style="margin-bottom: -20px;">
                    <div class="col-md-10">
                        <h4 class="card-title">Whole Sell Product</h4>
                    </div>
                    <div class="col-md-2" style="text-align:right;">
                            <a href="{{ route('whole-sell-product.index') }}" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Create">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            </a>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive p-2 pt-0">
                <table class="user-list-table table">
                    <tr>
                        <th>Name</th>
                        <td>{{ $wholeSellProduct->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>{{ $wholeSellProduct->serviceProvider->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Mobile No.</th>
                        <td>{{ $wholeSellProduct->mobile ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Product Category</th>
                        <td>{{ $wholeSellProduct->wholeSellCategory->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $wholeSellProduct->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Amount</th>
                        <td>{{ $wholeSellProduct->amount ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Price For</th>
                        <td>{{ $wholeSellProduct->price_for ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>End Date</th>
                        <td>{{ $wholeSellProduct->end_date ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Min Qty</th>
                        <td>{{ $wholeSellProduct->min_qty ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>City</th>
                        <td>{{ $wholeSellProduct->city->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>State</th>
                        <td>{{ $wholeSellProduct->state->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <td>{{ $wholeSellProduct->country->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $wholeSellProduct->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pin Code</th>
                        <td>{{ $wholeSellProduct->pin_code ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Images</th>
                        <td>
                            @php
                                $images = [];
                                if (!is_null($wholeSellProduct->images) && !empty($wholeSellProduct->images)) {
                                    $images = explode(' || ', $wholeSellProduct->images);
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