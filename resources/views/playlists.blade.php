@extends('layouts.app')

@section('content')

@include('modals.playlistData', [
    'modalAction' => __('Add'),
    'playlistName' => '',
    'musicJsonURL' => '',
    'screenW' => 1280,
    'screenH' => 720,
    'zonaGuardias' => 0
    ])


<div class="card-header">
    {{ __('Devices') }}
    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#playlistDataModal">{{ __('New playlist') }}</button>
</div>
<div class="card-body p-0">
    @if (session('status'))
        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
    @endif

    <div class="list-group list-group-flush">
        @forelse ($playlists as $playlist)
            <a href="{{route('playlist.single', ['id' => $playlist->id])}}" class="list-group-item d-flex justify-content-between align-items-center">
                <div class="col-4">
                    <h3 class="font-weight-bold text-primary ml-2">
                        {{ $playlist->name }}</em>
                    </h3>
                </div>
                <div class="col-2">
                    {{ $playlist->screenW }} x {{ $playlist->screenH }}
                </div>
                <div class="col-3">
                    @if($playlist->zonaGuardias!=0)
                        <span class="badge badge-success">Guardias</span>
                    @endif
                    @if(!!$playlist->musicURL)
                        <span class="badge badge-info">Música</span>
                    @endif
                </div>
                <div>
                    <span class="badge badge-dark badge-pill">{{ $playlist->nActive }} / {{ $playlist->nNoActive }}</span>
                    <span class="badge badge-light">{{ $playlist->updated_at }}</span>
                </div>
            </a>
        @empty
            <a href="{{ env('APP_COMPANY_SUPPORT') }}" class="list-group-item d-flex justify-content-between align-items-center"><h4>{{ __('You have no devices registered to your account.') }}</h4></a>
        @endforelse
    </div>
</div>

<script type="module">
    import {_$} from "{{ asset('js/exports.js') }}";
    var fetchOptions = {
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    }

    const form = _$('playlistDataForm')

    form.onsubmit = (e)=> {
        e.preventDefault()
        form.reportValidity()

        const formData = new FormData(form)
        fetchOptions.method = 'POST';
        fetchOptions.body = JSON.stringify( Object.fromEntries(formData) )

        fetch("{{ route('playlist') }}", fetchOptions).then( (resp)=> {
            if (resp.status == 200) {
                location.reload()
            } else {
                alert('Error. HTTP CODE: ' + resp.status)
            }
        })
    }
</script>

@endsection
