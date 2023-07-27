@extends('layouts.app')

@section('content')

<div class="card-body">
    @if($isAdmin)

        @if (count($queue) > 0)
        <div class="card-body container">
        <h5 class="card-title">{{ __('Encoding queue') }}</h5>
            <ul class="list-group">
                @foreach ($queue as $video)
                <li class="list-group-item d-flex justify-content-between">
                    <em>{{ $video->username }}</em> <span>{{ json_decode($video->data)->original_name }}</span>
                    @if ($video->encoding==1)
                    <span class="badge badge-success">{{ __('Encoding') }}</span>
                    @else
                    <span class="badge badge-primary">{{ __('Waiting') }}</span>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>

        @endif
    @else
        <h2>{{ __('Dashboard') }}</h2>
        <h5>Contenido de bienvenida</h5>
        <small>{{ __('Last access') }}: {{ $last_visit }}</small>
    @endif
</div>


@endsection
