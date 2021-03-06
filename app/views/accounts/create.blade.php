@extends('layouts.master')

@section('content')
<!-- Page header -->
<div class="page-header">
	<div class="page-title">
		<h3>Accounts <small>Control panel.</small></h3>
	</div>
</div>
<!-- /page header -->
<!-- Breadcrumbs line -->
<div class="breadcrumb-line">
	<ul class="breadcrumb">
		<li>
			<a href="/">Home</a>
		</li>
		<li class="active">
			Accounts
		</li>
	</ul>
</div>
<!-- /breadcrumbs line -->

@include('layouts.notify')

{{Form::open(['url'=>'/accounts/create','method'=>'post','files'=>true,'class'=>'form-horizontal form-bordered','role'=>'form'])}}

<!-- Button trigger modal -->

<div class="panel panel-default">
	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-user-plus2"></i> Create New Account</h6>
		<div class="table-controls pull-right">
			<input type="submit" value="Save" class="btn btn-info">
		</div>
	</div>
	<div class="panel-body">

	    <div class="form-group">
    		<label class="col-sm-2 control-label">Enter Name</label>
    		<div class="col-sm-10">
    			<input name="name" type="text" class="form-control" value="{{Input::old('name')}}">
    		</div>
    	</div>

		<div class="form-group">
			<label class="col-sm-2 control-label">Enter Email</label>
			<div class="col-sm-10">
				<input name="email" type="text" class="form-control" value="{{Input::old('email')}}">
			</div>
		</div>


		<div class="form-group">
			<label class="col-sm-2 control-label">Enter Password</label>
			<div class="col-sm-10">
				<input name="password" type="password" class="form-control" >
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label">Confirm your Password</label>
			<div class="col-sm-10">
				<input name="password_confirmation" type="password" class="form-control" >
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label">Select Avatar</label>
			<div class="col-sm-10">
				<input name="avatar" type="file" class="form-control">
			</div>
		</div>

		<div class="form-group">
        	<label class="col-sm-2 control-label">Birthday</label>
        	<div class="col-sm-10">
        		<input id="birthday" name="birthday" type="text" class="form-control" value="{{Input::old('birthday')}}"/>
        	</div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Bio</label>
            <div class="col-sm-10">
                <textarea name="bio" class="form-control">{{Input::old('bio')}}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Mobile No</label>
            <div class="col-sm-10">
                <input name="mobile_no" class="form-control" value="{{Input::old('mobile_no')}}"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Country</label>
            <div class="col-sm-10">
                <select name="country" class="form-control">
                @foreach($countries as $country)
                    <option {{Input::old("country")==$country->countryName?"selected":""}} value="{{$country->countryName}}">{{$country->countryName}}</option>
                @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Gender</label>
            <div class="col-sm-10">
                <input type="radio" name="gender" value="male" {{Input::old("gender")=="male"?"checked":""}}/> Male
                <input type="radio" name="gender" value="female" {{Input::old("gender")=="female"?"checked":""}}/> Female
            </div>
        </div>

        <div class="form-group">
             <label class="col-sm-2 control-label">Activate Account</label>
             <div class="col-sm-10">
                 <input type="checkbox" {{Input::old("activated")==1?"checked":""}} name="activated" value="1" /> Click here if you want to activate account
             </div>
         </div>

		<div class="form-actions text-right">
			<label class="col-sm-2 control-label"></label>
			<input type="submit" value="Save" class="btn btn-info">
		</div>
	</div>
</div>
{{Form::close()}}

@stop

@section('scripts')
<script type="text/javascript">
	$('#birthday').datepicker({
		format : "dd-mm-yyyy"
	});
</script>
@stop