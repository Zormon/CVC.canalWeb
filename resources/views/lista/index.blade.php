@extends('layouts.app')

@section('content')
<div class="card-header">{{ __('Playlists') }}</div>
<div class="card-body p-0">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="list-group list-group-flush">
        @forelse ($playlists as $playlist)
            <a href="{{route('editentry', ['id' => $playlist->id])}}" class="list-group-item d-flex justify-content-between align-items-center">
                <h3 class="font-weight-bold text-primary ml-2">{{ $playlist->name }} <em><small>( {{ $playlist->Screen_W }} x {{ $playlist->Screen_H }} )</small></em></h3>
                <div>
                    <span class="badge badge-success badge-pill">{{ $counter[$playlist->id]['active'] }}</span>
                    <span class="badge badge-secondary badge-pill">{{ $counter[$playlist->id]['noactive'] }}</span>
                    <small>{{ $playlist->updated_at }}</small>
                </div>
            </a>
        @empty
            <a href="{{ env('APP_COMPANY_SUPPORT') }}" class="list-group-item d-flex justify-content-between align-items-center"><h4>{{ __('Please feel free to contact us for activate your lists.') }}</h4></a>
        @endforelse
    </div>
</div>
@endsection
