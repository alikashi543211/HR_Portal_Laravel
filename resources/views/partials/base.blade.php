@extends('master')

@section('title', isset($page_title) ? $page_title : '')

@section('page_content')
@php
$per = App\Permission::whereCode(Request::segment(2))->first();
$permissionId = $per ? $per->id : 0;
@endphp


@if(checkPermission($permissionId, WRITE) || checkPermission($permissionId, READ))
@include('partials.' . $partials , ['data' => (isset($data) && !empty($data)) ? $data : ''])
@else
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <center> {{ __('default_label.permission_denied') }} </center>
            </div>
        </div>
    </div>
</div>
@endif


@endsection
