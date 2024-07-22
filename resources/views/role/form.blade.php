@extends('layouts.default')
@php
$url=(isset($role))? route('admin.role.update',['id'=>$role['id']]) :route('admin.role.store');
@endphp

@section('content')
<section class="inner-section-wrapper">
  <div class=" bg-white inventory-form">
    <div class="title-wrapper">
      <div class="page-title flex align-items-center">
        <svg class="mr-16" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M20 11H7.83L13.42 5.41L12 4L4 12L12 20L13.41 18.59L7.83 13H20V11Z" fill="#333333" />
        </svg>
        {{isset($role) ? 'Edit' : "Create"}} Role
      </div>
    </div>
    <div class="container">
      <div class="inventory-form-wrapper">
        {!! Form::open(['url' => $url, 'class'=>'form-data']) !!}

        <div class="row">
          <div class="col-6">
            <div class="form-group">
              {!! Form::label('name', 'Role Name',['class' => 'input-label required']) !!}
              {!! Form::text('name',old('name',$role->name ??
              ''),['class'=>'form-control','placeholder'=>'Role Name',
              'data-validation'=>'required']) !!}

              @if($errors->has('name'))
              <span class="text-danger">{{$errors->first('name')}}</span>
              @endif
            </div>
          </div>
          <div class="col-6">
            <div class="form-group">
              <div class='form-group'>
                {!! Form::label('permissions', 'Select Permissions',['class' => 'input-label']) !!}
                {!! Form::select('permissions[]',$permissions->pluck('name','id'),isset($role) ? $role->permissions->pluck('id') :old('permissions'),['class'=>'form-input multiple-select','id'=>'permission-list','multiple'=>'multiple']) !!}
              </div>

            </div>
          </div>
        </div>

        <div class="form-button-wrapper">
          @if(isset($role))
          <a href="{{route('admin.role.create')}}" class="btn btn-success">Back to create</a>
          @else
          <a href="{{route('admin.role')}}" class="btn-cancel">Cancel</a>
          @endif
          <button type="submit" class="btn-action-primary">{{(isset($role)) ? 'Update Role' : 'Create Role'}}</button>

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