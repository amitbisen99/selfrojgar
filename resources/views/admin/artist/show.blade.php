@extends($adminTheme)

@section("title")
    Artists
@endsection

@section("wrapper")
    <section class="app-user-list">
        <div class="card">
            <div class="card-body border-bottom">
                <div class="row" style="margin-bottom: -20px;">
                    <div class="col-md-10">
                        <h4 class="card-title">Artists</h4>
                    </div>
                    <div class="col-md-2" style="text-align:right;">
                            <a href="{{ route('artist.index') }}" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Create">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            </a>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive p-2 pt-0">
                <table class="user-list-table table">
                    <tr>
                        <th>Name</th>
                        <td>{{ $artist->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>{{ $artist->serviceProvider->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>artist Category</th>
                        <td>{{ $artist->genres->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Facebook</th>
                        <td>{{ $artist->facebook_link ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Instagram</th>
                        <td>{{ $artist->instagram_link ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Youtube</th>
                        <td>{{ $artist->youtube_link ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>About</th>
                        <td>{{ $artist->About ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>contact No.</th>
                        <td>{{ $artist->contact_no ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <td>{{ $artist->price ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>City</th>
                        <td>{{ $artist->city->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>State</th>
                        <td>{{ $artist->state->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Country</th>
                        <td>{{ $artist->country->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $artist->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Pin Code</th>
                        <td>{{ $artist->pincode ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Images</th>
                        <td>
                            @php
                                $images = [];
                                if (!is_null($artist->images) && !empty($artist->images)) {
                                    $images = explode(' || ', $artist->images);
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