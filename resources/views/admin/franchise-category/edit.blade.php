@extends($adminTheme)

@section("title")
	Franchise Category Edit
@endsection

@section("wrapper")
<section id="multiple-column-form">
	<div class="row">
	    <div class="col-12">
	        <div class="card">
	            <div class="card-header">
	                <div class="col-md-10">
                		<h4 class="card-title">Franchise Category Edit</h4>
            		</div>
            		<div class="col-md-2" style="text-align: right;">
						<a href="{{ route('franchise-category.index') }}" class="btn btn-danger head-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Back"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>                			
            		</div>
	            </div>
	            <div class="card-body">
	              	{!! Form::model($franchiseCategory, ['method' => 'PUT','route' => ["franchise-category.update", $franchiseCategory->id],'files'=>true, 'class'=>'form']) !!} 
	                    
	                    @include('admin.franchise-category.form')
	                    
	    			{!! Form::close() !!}
	            </div>
	        </div>
	    </div>
	</div>
</section>
@endsection