<div class="row mb-1">
	<div class="col-sm-6">
		<label class="form-label fw-bolder">User Name : </label><span class="text-danger">*</span>
		{!! Form::text('name', null, ['class' => 'form-control'.$errors->first('name', ' error'), 'placeholder' => 'Enter Name']) !!}
		@if ($errors->has('name'))
				<span class="text-danger">{{ $errors->first('name') }}</span>
		@endif
	</div>
	<div class="col-sm-6">
		<label class="form-label fw-bolder">Email : </label><span class="text-danger">*</span>
		{!! Form::text('email', null, ['class' => 'form-control'.$errors->first('email', ' error'), 'placeholder' => 'Enter email']) !!}
		@if ($errors->has('email'))
				<span class="text-danger">{{ $errors->first('email') }}</span>
		@endif
	</div>
</div>
<div class="row mb-1">
	<div class="col-sm-6">
		<label class="form-label fw-bolder">Password : </label><span class="text-danger">*</span>
		{!! Form::password('password', ['class' => 'form-control'.$errors->first('password', ' error'), 'placeholder' => 'Enter Password']) !!}
		@if ($errors->has('password'))
				<span class="text-danger">{{ $errors->first('password') }}</span>
		@endif
	</div>
	<div class="col-sm-6">
		<label class="form-label fw-bolder">Confirm Password : </label><span class="text-danger">*</span>
		{!! Form::password('confirm_password', ['class' => 'form-control'.$errors->first('confirm_password', ' error'), 'placeholder' => 'Enter Confirm Password']) !!}
		@if ($errors->has('confirm_password'))
				<span class="text-danger">{{ $errors->first('confirm_password') }}</span>
		@endif
	</div>
</div>

<div class="row">
    <div class="col-12 text-center">
        <button type="submit" class="btn btn-success me-1">Submit</button>
        {{-- <a href="{{ route('user.index') }}" class="btn btn-outline-secondary">Cancel</a> --}}
    </div>
</div>