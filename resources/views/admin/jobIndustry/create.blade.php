@extends($adminTheme)

@section("title")
    Job Industry Create
@endsection

@section("wrapper")
<section id="multiple-column-form">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
            		<div class="col-md-10">
                		<h4 class="card-title">Job Industry Create</h4>
            		</div>
            		<div class="col-md-2" style="text-align: right;">
						<a href="{{ route('industry-job.index') }}" class="btn btn-danger head-btn" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="Back"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>                			
            		</div>
                </div>
                <div class="card-body mt-2">
                  {!! Form::open(array('route' => 'industry-job.store','method'=>'POST','files'=>	'true','class'=>'form')) !!}

						@include('admin.jobIndustry.form')

				   {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section("script")
<script type="text/javascript">
    $(document).ready(function() {

        if($('#department').find(":checked").text() ==='Sales'){
            $('.production_rep_div').removeClass('d-none');
        }else{
            $('.production_rep_div').addClass('d-none');
        }
        $('#department').on('click', function() {
            if($(this).find(":checked").text() ==='Sales'){
                $('.production_rep_div').removeClass('d-none');
            }else{
                $('.production_rep_div').addClass('d-none');
            }
        });
    });
</script>
@endsection