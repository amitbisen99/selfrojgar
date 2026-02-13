<div class="row mb-1">
	<div class="col-sm-6">
		<label class="form-label fw-bolder">Name : </label><span class="text-danger">*</span>
		{!! Form::text('name', null, ['class' => 'form-control'.$errors->first('name', ' error'), 'placeholder' => 'Enter Name']) !!}
		@if ($errors->has('name'))
				<span class="text-danger">{{ $errors->first('name') }}</span>
		@endif
	</div>
	<div class="col-sm-6">
		<label class="form-label fw-bolder">States : </label><span class="text-danger">*</span>
		{!! Form::select('states_id', $state, null, ['class' => 'form-control select2']) !!}
	</div>
</div>
<div class="row">
    <div class="col-12 text-center">
        <button type="submit" class="btn btn-success me-1">Submit</button>
        {{-- <a href="{{ route('user.index') }}" class="btn btn-outline-secondary">Cancel</a> --}}
    </div>
</div>