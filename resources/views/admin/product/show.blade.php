@extends($adminTheme)

@section("title")
    Products
@endsection

@section("wrapper")
    <section class="app-user-list">
        <div class="card">
            <div class="card-body border-bottom">
                <div class="row" style="margin-bottom: -20px;">
                    <div class="col-md-10">
                        <h4 class="card-title">Products</h4>
                    </div>
                    <div class="col-md-2" style="text-align:right;">
                            <a href="{{ route('product.index') }}" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Create">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            </a>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive p-2 pt-0">
                <table class="user-list-table table">
                    <tr>
                        <th>Name</th>
                        <td>{{ $product->product_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>{{ $product->getUser->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Product Category</th>
                        <td>{{ $product->getProductCategory->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Brand Name</th>
                        <td>{{ $product->brand_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $product->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <td>{{ $product->price ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Price For</th>
                        <td>{{ $product->price_for ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>City</th>
                        <td>{{ $product->city->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>State</th>
                        <td>{{ $product->state->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <td>{{ $product->country->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $product->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pin Code</th>
                        <td>{{ $product->pincode ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Images</th>
                        <td>
                            @php
                                $images = [];
                                if (!is_null($product->images) && !empty($product->images)) {
                                    $images = explode(' || ', $product->images);
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