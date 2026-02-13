@extends($adminTheme)

@section("title")
	User Details
@endsection

@section("wrapper")
<section id="multiple-column-form">
	<div class="row">
	    <div class="col-12">
	        <div class="card">
	            <div class="card-header">
	                <div class="col-md-10">
                		<h4 class="card-title">User Details</h4>
            		</div>
            		<div class="col-md-2" style="text-align: right;">
						<a href="{{ route('user.index') }}" class="btn btn-danger head-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Back"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>                			
            		</div>
	            </div>
	            <div class="card-datatable table-responsive p-2 pt-0">
	                <table class="user-list-table table">
	                    <tr>
	                        <th>Name</th>
	                        <td>{{ $user->name ?? '-' }}</td>
	                    </tr>
	                    <tr>
	                        <th>Email</th>
	                        <td>{{ $user->email ?? '-' }}</td>
	                    </tr>
	                    <tr>
	                        <th>type</th>
	                        <td>
	                        	@if ($user->type == 1)
	                        		<span class="badge bg-primary">Admin</span>
	                        	@else
	                        		<span class="badge bg-success">User</span>
	                        	@endif
	                        </td>
	                    </tr>
	                    <tr>
	                        <th>status</th>
	                        <td>
	                        	@if ($user->status == 1)
	                        		<span class="badge bg-primary">active</span>
	                        	@else
	                        		<span class="badge bg-danger">inactive</span>
	                        	@endif
	                        </td>
	                    </tr>
	                    <tr>
	                        <th>profile_pic</th>
	                        <td>{{ $user->profile_pic ?? '-' }}</td>
	                    </tr>
	                    <tr>
	                        <th>profession</th>
	                        <td>{{ $user->profession ?? '-' }}</td>
	                    </tr>
	                    <tr>
	                        <th>about</th>
	                        <td>{{ $user->about ?? '-' }}</td>
	                    </tr>
	                    <tr>
	                        <th>contact_number</th>
	                        <td>{{ $user->contact_number ?? '-' }}</td>
	                    </tr>
	                    <tr>
	                        <th>City</th>
	                        <td>{{ $user->city_name ?? '-' }}</td>
	                    </tr>
	                    <tr>
	                        <th>State</th>
	                        <td>{{ $user->state_name ?? '-' }}</td>
	                    </tr>
	                    <tr>
	                        <th>Country</th>
	                        <td>{{ $user->country_name ?? '-' }}</td>
	                    </tr>
	                    <tr>
	                        <th>latitude</th>
	                        <td>{{ $user->latitude ?? '-' }}</td>
	                    </tr>
	                    <tr>
	                        <th>longitude</th>
	                        <td>{{ $user->longitude ?? '-' }}</td>
	                    </tr>
	                </table>
	            </div>
	        </div>
	    </div>
	</div>
</section>
@endsection