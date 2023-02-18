@extends('employees.layouts.app')
@section('title')
@endsection

@section('css')
@endsection

@section('content')
    <div class="container-fluid">
        {{-- <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title"> Announcement Detail </h3>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="post-details">
                            <h3 class="mb-2 text-black">{{ $announcement->title }}</h3>
                            <ul class="mb-4 post-meta">
                                <li class="post-author">{{ $announcement->updatedBy->first_name . ' ' . $announcement->updatedBy->last_name }}</li>
                                <li class="post-date"><i class="fa fa-calender"></i>{{ \Carbon\Carbon::parse($announcement->created_at)->format('d M Y') }}</li>
                            </ul>
                            <div class="row mt-2">
                                {!! $announcement->description !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
