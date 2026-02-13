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


<section id="user-posts">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">User Posts</h4>
                </div>
                <div class="card-body">
                    @php
                        $tabs = [
                            ['id' => 'property', 'label' => 'Properties', 'relation' => 'properties', 'destroy_route' => 'propertyes.destroy','route' => 'propertyes.show', 'title' => 'name'],
                            ['id' => 'job', 'label' => 'Jobs', 'relation' => 'jobs', 'destroy_route' => 'job.destroy','route' => 'job.show', 'title' => 'role'],
                            ['id' => 'product', 'label' => 'Products', 'relation' => 'products', 'destroy_route' => 'product.destroy','route' => 'product.show', 'title' => 'product_name'],
                            ['id' => 'wholesell', 'label' => 'Whole Sell', 'relation' => 'wholeSellProducts', 'destroy_route' => 'whole-sell-product.destroy','route' => 'whole-sell-product.show', 'title' => 'name'],
                            ['id' => 'ondemand', 'label' => 'On Demand', 'relation' => 'onDemandServices', 'destroy_route' => 'on-demand-service.destroy','route' => 'on-demand-service.show', 'title' => 'name'],
                            ['id' => 'advertisement', 'label' => 'Ads', 'relation' => 'advertisements', 'destroy_route' => 'advertisement.destroy','route' => 'advertisement.show', 'title' => 'offer_name'],
                            ['id' => 'tourism', 'label' => 'Tourism', 'relation' => 'tourisms', 'destroy_route' => 'tourism-business.destroy','route' => 'tourism-business.show', 'title' => 'name'],
                            ['id' => 'franchise', 'label' => 'Franchise', 'relation' => 'franchiseBusinesses', 'destroy_route' => 'franchise-business.destroy','route' => 'franchise-business.show', 'title' => 'name'],
                            ['id' => 'business', 'label' => 'Business', 'relation' => 'businesses', 'destroy_route' => 'businesses.destroy','route' => 'businesses.show', 'title' => 'name'],
                        ];

                        $activeTab = null;
                        foreach($tabs as $key => $tab) {
                            $tabs[$key]['count'] = $user->{$tab['relation']}->count();
                            if(is_null($activeTab) && $tabs[$key]['count'] > 0){
                                $activeTab = $tab['id'];
                            }
                        }
                    @endphp

                    @if(!is_null($activeTab))
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            @foreach($tabs as $tab)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $activeTab == $tab['id'] ? 'active' : '' }}" id="{{ $tab['id'] }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $tab['id'] }}" type="button" role="tab" aria-controls="{{ $tab['id'] }}" aria-selected="{{ $activeTab == $tab['id'] ? 'true' : 'false' }}">
                                        {{ $tab['label'] }} @if($tab['count'] > 0) <span class="badge bg-secondary ms-1">{{ $tab['count'] }}</span> @endif
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            @foreach($tabs as $tab)
                                    <div class="tab-pane fade {{ $activeTab == $tab['id'] ? 'show active' : '' }}" id="{{ $tab['id'] }}" role="tabpanel" aria-labelledby="{{ $tab['id'] }}-tab">
                                        @include('admin.user.partials.post_table', ['posts' => $user->{$tab['relation']}, 'route' => $tab['route'], 'destroy_route' => $tab['destroy_route'], 'title' => $tab['title']])
                                    </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info p-2">No posts found for this user.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
