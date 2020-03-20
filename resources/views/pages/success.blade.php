@extends('layouts.app')
@section('title', '操作成功')

@section('content')
    <div class="card">
        <div class="card-header">操作成功</div>
        <div class="card-body text-center">
            <h1>{{ $msg }}</h1>
            <a class="btn btn-primary" href="@if($url) {{ $url }} @else{{ route('root') }}@endif">
                @if($op) {{ $op }} @else 返回首页 @endif</a>
        </div>
    </div>
@endsection

@section('cssForPage')
    <style type="text/css">
        .card{
            margin-left: 25%;
            width: 50%;
        }
    </style>
@endsection