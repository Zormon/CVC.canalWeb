@extends('layouts.app')

@section('content')

<div class="card-header"><h1>{{ __("Userlist") }}</h1></div>

<div class="card-body">
    <ul class="list-group">
        @forelse ($users as $user)
        <li class="list-group-item">
            <div class="row">
                <div class="col-4">
                    <h3 class="font-weight-bold text-primary ">{{ $user->name }}</h3>
                </div>
                <div class="col-4">{{ $user->username }}</div>
                <div class="col-4">
                    <a class="btn btn-success" href="/user/{{ $user->id }}"><i class="fas fa-edit"></i>&nbsp;&nbsp;{{ __('Profile') }}</a>
                    <a class="btn btn-info" href="/playlists/{{ $user->id }}"><i class="fas fa-tv"></i>&nbsp;&nbsp;{{ __('Devices') }}</a>
                </div>
            </div>
        </li>
        @empty
            <li class="list-group-item">{{ __('No users') }}</li>
        @endforelse
    </ul>
</div>

@endsection

