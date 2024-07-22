@extends('layouts.default')
@php
$url=(isset($user))? route('admin.user.update',['id'=>$user['id']]) :route('admin.user.store');

@endphp

@section('content')
<section class="inner-section-wrapper">
  <div class=" bg-white inventory-form">
    <div class="title-wrapper">
      <div class="page-title flex align-items-center">

        {{isset($user) ? 'Edit' : "Create"}} User
      </div>
    </div>
    <div class="container">
      <div class="inventory-form-wrapper">
        {!! Form::open(['url' => $url, 'class'=>'form-data']) !!}

        <div class="row">
          <div class="col-6">
            <div class="form-group">
              {!! Form::label('name', 'Full Name',['class' => 'input-label required']) !!}
              {!! Form::text('name',old('name',$user['name'] ??
              ''),['class'=>'form-control','placeholder'=>'Name',
              'data-validation'=>'required']) !!}

              @if($errors->has('name'))
              <span class="text-danger">{{$errors->first('name')}}</span>
              @endif
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              {!! Form::label('Username', '',['class' => 'input-label required']) !!}
              {!! Form::text('username',old('username',$user->username ??
              ''),['class'=>'form-control','placeholder'=>'Username',
              'data-validation'=>'required']) !!}

              @if($errors->has('username'))
              <span class="text-danger">{{$errors->first('username')}}</span>
              @endif
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              {!! Form::label('Email', '',['class' => 'input-label required']) !!}
              {!! Form::email('email',old('email',$user->email ??
              ''),['class'=>'form-control','placeholder'=>'Email',
              'data-validation'=>'required']) !!}

              @if($errors->has('email'))
              <span class="text-danger">{{$errors->first('email')}}</span>
              @endif
            </div>
          </div>
          <div class="col-6">
            <div class='form-group'>
              {!! Form::label('roles','Select Role',['class' => 'input-label']) !!}
              {!! Form::select('roles[]',$roles->pluck('name','id'),isset($user) ? $user->roles->pluck('id') :old('roles'),['class'=>'form-input multiple-select','id'=>'role-list','multiple'=>'multiple']) !!}
            </div>
          </div>
          @if(!isset($user))
          <div class="col-6">
            <div class="form-group">
              {!! Form::label('Password', '',['class' => 'input-label required']) !!}
              <input type="password" name="password" placeholder="Password" data-validation="required}}">

              @if($errors->has('password'))
              <span class="text-danger">{{$errors->first('password')}}</span>
              @endif
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              {!! Form::label('Password', '',['class' => 'input-label required']) !!}
              <input type="password" name="password_confirmation" placeholder="Password Confirmation" data-validation="required}}">

              @if($errors->has('password'))
              <span class="text-danger">{{$errors->first('password')}}</span>
              @endif
            </div>
          </div>
          @endif

        </div>

      </div>


      <div class="form-button-wrapper">
        @if(isset($user))
        <a href="{{route('admin.user.create')}}" class="btn btn-success">Back to create</a>
        @else
        <a href="{{route('admin.user')}}" class="btn-cancel">Cancel</a>
        @endif
        <button type="submit" class="btn-action-primary">{{(isset($user)) ? 'Update User' : 'Create User'}}</button>

      </div>


      {!! Form::close() !!}
    </div>
  </div>

  </div>
</section>
@endsection
@push('style')
<link rel="stylesheet" type="text/css" href="{{asset('vendor/select2/css/select2.min.css')}}">
@endpush
@push('scripts')
@include('scripts.validation')
<script src="{{asset('vendor/select2/js/select2.full.min.js')}}"></script>
<script>
  $('.multiple-select').each(function() {
    let id = '#' + $(this).attr('id')
    $(id).select2({
      placeholder: 'Select'
    })

  })
</script>

@endpush