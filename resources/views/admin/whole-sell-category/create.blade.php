@extends($adminTheme)

@section("title")
    Whole Sell Category Create
@endsection

@section("wrapper")
<section id="multiple-column-form">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
            		<div class="col-md-10">
                		<h4 class="card-title">Whole Sell Category Create</h4>
            		</div>
            		<div class="col-md-2" style="text-align: right;">
						<a href="{{ route('whole-sell-category.index') }}" class="btn btn-danger head-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Back"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>                			
            		</div>
                </div>
                <div class="card-body mt-2">
                  {!! Form::open(array('route' => 'whole-sell-category.store','method'=>'POST','files'=>	'true','class'=>'form')) !!}

						@include('admin.whole-sell-category.form')

				   {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection