@extends('layouts.app')

@section('content')

<div class="card-header">{{ __("User's list") }}</div>
<div class="card-body">
    @if (session('status'))
        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
    @endif

    <ul class="list-group">
        @foreach ($users as $user)
        <li class="list-group-item list-group-item-action">
            <div class="row">
                <div class="col">{{ $user->name }}</div>
                <div class="col"><small>{{ $user->username }}</small></div>
                <div class="col">
                    <a class="btn btn-success" href="/user/{{ $user->id }}"><i class="fas fa-edit"></i></a>
                    <a class="btn btn-info" href="/playlists/{{ $user->id }}"><i class="fas fa-video"></i></a>
                </div>
            </div>
        </li>
        @endforeach
    </ul>
</div>

@endsection

