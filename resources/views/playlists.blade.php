@extends('layouts.app')

@section('content')

@if ($isAdmin)
    @include('modals.playlistData', [
        'modalAction' => __('Add'),
        'playlistName' => '',
        'musicJsonURL' => '',
        'screenW' => 1280,
        'screenH' => 720,
        'zonaGuardias' => 0
    ])
@endif


<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-10">
                <h1>{{ __('Devices') }}</h1>
            </div>
            @if ($isAdmin)
            <div class="col-2">
                <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#playlistDataModal"><i class="fas fa-plus"></i>&nbsp;&nbsp;{{ __('New playlist') }}</button>
            </div>
            @endif
        </div>
    </div>

    <div class="card-body">
        <div class="list-group">
            @forelse ($playlists as $playlist)
                <a class="list-group-item list-group-item-action" href="{{route('playlist.single', $playlist->id)}}">
                    <div class="row d-flex justify-content-between align-items-center">
                        <div class="col-5">
                            <h3 class="font-weight-bold text-primary">{{ $playlist->name }}</h3>
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
                        <div class="col-2">
                            <span class="badge badge-dark badge-pill">{{ $playlist->nActive }} / {{ $playlist->nNoActive }}</span>
                            <span class="badge badge-light">{{ $playlist->updated_at }}</span>
                        </div>
                    </div>
                </a>
            @empty
                <p class="list-group-item">{{ __('No devices') }}</p>
            @endforelse
        </div>
    </div>
</div>

@if ($isAdmin)
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
@endif

@endsection
