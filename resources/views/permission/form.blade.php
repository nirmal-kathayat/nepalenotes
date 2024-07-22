@extends('layouts.default')
@section('title',isset($permission) ? 'Update Permission' : 'Create Permission')
@section('content')
<section class="inner-section-wrapper">
    <div class=" bg-white inventory-form">
        <div class="title-wrapper">
            <div class="page-title flex align-items-center">
                <svg class="mr-16" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20 11H7.83L13.42 5.41L12 4L4 12L12 20L13.41 18.59L7.83 13H20V11Z" fill="#333333" />
                </svg>
                {{isset($permission) ? 'Edit' : "Create"}} Permission
            </div>
        </div>
        <div class="container">
            <div class="inventory-form-wrapper">
                <form action="{{ isset($permission) ? route('admin.permission.update', $permission->id) : route('admin.permission.store') }}" method="post" class="form-data">
                    @csrf

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label required">Permission Name</label>
                                <input type="text" name="name" data-validation="required" class="validation-control" value="{{old('name',$permission->name ?? '')}}">
                                @if($errors->has('name'))
                                <span class="text-danger">{{$errors->first('name')}}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        @if($errors->has('access_uri'))
                        <span class="text-danger">{{$errors->first('access_uri')}}</span>
                        @endif
                        <label></label>
                        <div class="permission-lists-wrapper">
                            @foreach($routeLists as $key => $items)
                            <div class="permission-item">
                                <div class="permission-item-header">
                                    <h3>{{preg_replace('/([a-z])([A-Z])/', '$1 $2',ucfirst($key))}}</h3>
                                </div>
                                <div class="permission-body">
                                    <ul>
                                        @foreach($items as $itemKey =>$route)
                                        @if(is_array($route))
                                        @foreach($route as $otherRoute)
                                        @if($otherRoute!=='admin/dashboard')
                                        @php
                                        $arr = explode('/',$otherRoute);

                                        @endphp
                                        <li>
                                            <input type="checkbox" name="access_uri[]" value="{{$otherRoute}}" {{ isset($permission) && is_array($permission->access_uri) && in_array($otherRoute, $permission->access_uri) ? 'checked' : '' }}>
                                            <label>{{ucfirst($arr[2])}} {{ucfirst($key)}}</label>
                                        </li>
                                        @endif
                                        @endforeach
                                        @else
                                        <li>
                                            <input type="checkbox" name="access_uri[]" value="{{$route}}" {{ isset($permission) && is_array($permission->access_uri) && in_array($route, $permission->access_uri) ? 'checked' : '' }}>
                                            <label>{{str_replace('-',' ',ucfirst($itemKey))}} {{$key == 'admin' ? '' :preg_replace('/([a-z])([A-Z])/', '$1 $2',ucfirst($key))}}</label>
                                        </li>
                                        @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('access_uri')
                        <p class="validation-error">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="form-button-wrapper">
                        @if(isset($permission))
                        <a href="{{route('admin.permission.create')}}" class="btn btn-success">Back to create</a>
                        @else
                        <a href="{{route('admin.permission')}}" class="btn-cancel">Cancel</a>
                        @endif
                        <button type="submit" class="btn-action-primary">{{(isset($permission)) ? 'Update Permission' : 'Create Permission'}}</button>

                    </div>
                </form>
            </div>
        </div>

    </div>
</section>
@endsection

@push('scripts')
@include('scripts.validation')
@endpush