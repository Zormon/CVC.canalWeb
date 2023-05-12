@extends('layouts.app')

@section('content')

                <div class="card-header">{{ __("User's Playlist") }}</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if(Auth::user()->hasRole('admin'))

                        <div class="list-group">
                            @foreach ($users as $usr)

                        <a href="{{ route("userplaylists",[$usr->id]) }}" class="list-group-item list-group-item-action">
                                        <div class="row">
                                            <div class="col-1">#{{ $usr->id }}</div>
                                            <div class="col-3">{{ $usr->name }}</div>
                                            <div class="col-8">{{ $usr->email }}</div>
                                        </div>
                                    </a>

                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

@endsection
