@extends('layouts.app')

@section('content')

@include('modals.deleteMedia')
@include('modals.uploadMedia')

@if ($isAdmin)
    @include('modals.playlistData', [
        'modalAction' => __('Edit'),
        'playlistName' => $playlist->name,
        'musicJsonURL' => $playlist->musicURL,
        'screenW' => $playlist->screenW,
        'screenH' => $playlist->screenH,
        'zonaGuardias' => $playlist->zonaGuardias
    ])
    @include('modals.deletePlaylist')
@endif

<div class="card">

    <div class="card-header">
        <div class="row">
            <div class="col-9">
                <h1>{{ $playlist->name }} <small>({{$playlist->screenW}} x {{$playlist->screenH}}@if($playlist->zonaGuardias!=0) | guardias @endif @if(!!$playlist->musicURL)| musica @endif)</small></h1>
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#uploadMediaModal" title="{{ __('Upload') }}"><i class="ni ni-cloud-upload-96"></i></button>
            </div>
            @if ($isAdmin)
            <div class="col-1">
                <button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#playlistDataModal" title="{{ __('Edit Playlist') }}"><i class="fa fa-edit"></i></button>
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-block btn-warning" data-toggle="modal" data-target="#deletePlaylistModal" title="{{ __('Delete Playlist') }}"><i class="fa fa-trash"></i></button>
            </div>
            @endif
        </div>
    </div>

    @if (count($queue) > 0)
        <div class="card-body container">
            <ul class="list-group">
            @foreach ($queue as $video)
            <li class="list-group-item d-flex justify-content-between">
                {{ json_decode($video->data)->original_name }}
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

    @if (count($media) > 0)
    <div class="card-body">
        <div id="playlist" class="list-group list-group-sortable-connected">
            @foreach ($media as $video)
                <div data-id="{{ $video->id }}" data-position="{{ $video->position }}" class="list-group-item list-group-item-action media-item">
                    <div class="row">
                        <div class="col-md-2">
                            <a href="{{ '/storage/media/'.$video->filename }}" style="max-height:250px;overflow:hidden;">
                                <img src="{{ '/storage/thumbs/'.pathinfo($video->filename,PATHINFO_FILENAME).'.webp' }}" class="card-img-top img-fluid" alt="ID #{{ $video->id }}">
                            </a>
                        </div>
                        
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-1">
                                    <label class="small">{{ __('Active') }}</label>
                                    <input type="checkbox" class="form-control mediaValue" name="active" @if ($video->active == 1) checked @endif>
                                </div>
                                <div class="col-4">
                                    <label class="small">{{ __('Title') }}</label>
                                    <input type="text" class="card-title form-control mediaValue" name="title" value="{{ $video->title }}">
                                </div>
                                <div class="col-3">
                                    <label class="small">{{ __('Transition') }}</label>
                                    <select class="custom-select transition mediaValue" name="transition">
                                        <option value="none" <?=$video->transition=='none'?'selected':''?>>none</option>
                                        <option value="fade" <?=$video->transition=='fade'?'selected':''?>>fade</option>
                                        <option value="zoom-in" <?=$video->transition=='zoom-in'?'selected':''?>>zoom-in</option>
                                        <option value="zoom-out" <?=$video->transition=='zoom-out'?'selected':''?>>zoom-out</option>
                                        <option value="slide-left" <?=$video->transition=='slide-left'?'selected':''?>>slide-left</option>
                                        <option value="slide-up" <?=$video->transition=='slide-up'?'selected':''?>>slide-up</option>
                                        <option value="slide-right" <?=$video->transition=='slide-right'?'selected':''?>>slide-right</option>
                                        <option value="slide-down" <?=$video->transition=='slide-down'?'selected':''?>>slide-down</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label class="small">{{ __('Volume') }}</label>
                                    <input type="number" min="0" max="10" name="volume" value="{{ $video->volume }}" class="card-title form-control mediaValue">
                                </div>
                                <div class="col-2">
                                    <label class="small">{{ __('Duration') }}</label>
                                    <input type="number" name="duration" value="{{ $video->duration }}" class="card-title form-control mediaValue">
                                </div>
                                <div class="col-3">
                                    <label class="small">{{ __('DateFrom') }}</label>
                                    <input type="date" name="dateFrom" value="{{ $video->dateFrom?date('Y-m-d',strtotime($video->dateFrom)):'' }}" class="card-title form-control mediaValue">
                                </div>
                                <div class="col-3">
                                    <label class="small">{{ __('DateTo') }}</label>
                                    <input type="date" name="dateTo" value="{{ $video->dateTo?date('Y-m-d',strtotime($video->dateTo)):'' }}" class="card-title form-control mediaValue">
                                </div>
                                <div class="col-2">
                                    <label class="small">{{ __('Time From') }}</label>
                                    <input type="time" name="timeFrom" value="{{ $video->timeFrom?date('H:i',strtotime($video->timeFrom)):'' }}" class="card-title form-control mediaValue">
                                </div>
                                <div class="col-2">
                                    <label class="small">{{ __('Time To') }}</label>
                                    <input type="time" name="timeTo" value="{{ $video->timeTo?date('H:i',strtotime($video->timeTo)):'' }}" class="card-title form-control mediaValue">
                                </div>
                                <div class="col-2">
                                    <label for="">Eliminar</label>
                                    <button type="button" class="btn btn-block btn-warning" data-toggle="modal" data-target="#deleteMediaModal" title="{{ __('Delete') }}"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

</div>



<script type="module">
    import {_$,_$$,_$$$} from '{{ asset("js/exports.js") }}';

    const mediaEditURL = "{{ route('media.single','') }}/"
    const playlistEditURL = "{{ route('playlist.single', $playlist->id) }}"

    var fetchOptions = {
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    }

    @if (count($media) > 0)
        sortable('.list-group-sortable-connected', {
            forcePlaceholderSize: true,
            placeholderClass: 'ui-state-highlight'
        })

        sortable('.list-group-sortable-connected')[0].addEventListener('sortupdate', (e)=> {
            var positions = {}
            let id

            e.detail.destination.items.forEach( (v, i) => {
                id = v.dataset.id
                positions[id] = i
            })

            fetchOptions.method = 'PATCH'
            fetchOptions.body = JSON.stringify( {positions: positions} )
            fetch("{{ route('playlist.single', $playlist->id) }}", fetchOptions)
        })
    @endif

    $('#deleteMediaModal').on('show.bs.modal', (e)=> {
        const but = e.relatedTarget
        const rowEl = but.closest('.media-item')
        const id = rowEl.dataset.id

        _$('deleteMediaModalLabel').textContent = "{{ __('Are you sure you want to delete this media?') }}"

        _$('deleteMedia').onclick = ()=> {
            fetchOptions.method = 'DELETE'
            fetch(mediaEditURL+id, fetchOptions).then( () => { rowEl.remove() })
        }
    })

    _$$$('.mediaValue').forEach(el => { el.onchange = (e) => {
        const id = e.currentTarget.closest('.media-item').dataset.id
        const param = e.currentTarget.name
        const isCheckbox = e.currentTarget.type == 'checkbox'
        const value = isCheckbox? ( e.currentTarget.checked?1:0 ) : ( e.currentTarget.value || null )

        fetchOptions.method = 'PATCH'
        fetchOptions.body = JSON.stringify( {[param]: value} )
        fetch(mediaEditURL+id, fetchOptions)
    }})


    const upForm = _$('uploadMediaForm')
    const fieldset = _$$('#uploadMediaForm > fieldset')
    const progressBar = _$$('#uploadMediaForm progress')

    upForm.onsubmit = (e)=> {
        e.preventDefault()
        upForm.reportValidity()
        
        let formData = new FormData(e.currentTarget)
        formData.append('plId', {{ $playlist->id }})

        let xhr = new XMLHttpRequest()
        xhr.open('POST', "{{ route('media') }}")
        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}')
        xhr.onload = ()=> {
            if (xhr.status == 200)  { location.reload() }
            else                    { alert('Error. HTTP CODE: ' + xhr.status)}
            fieldset.disabled = false
            progressBar.value = 0
        }
        xhr.upload.onprogress = (e)=> {
            if (e.lengthComputable) {
                let percentComplete = (e.loaded / e.total) * 100
                progressBar.value = percentComplete
            }
        }
        fieldset.disabled = true
        xhr.send(formData)
    }

    
    @if ($isAdmin)
    _$('deletePlaylist').onclick = ()=> {
        fetchOptions.method = 'DELETE'
        fetch("{{ route('playlist.single', $playlist->id) }}", fetchOptions).then( (resp)=> {
            switch (resp.status) {
                case 200:
                    window.location.href = "{{ route('playlists.user', $playlist->userId) }}"    
                    break;
                case 409:
                    alert("{{ __('This playlist has files being encoded. Please wait until it finishes.') }}")
                    break;
                default:
                alert('Error. HTTP CODE: ' + resp.status)
                    break;
            }
        })
    }

    const playlistDataForm = _$('playlistDataForm')
    playlistDataForm.onsubmit = (e)=> {
        e.preventDefault()
        playlistDataForm.reportValidity()

        const formData = new FormData(playlistDataForm)
        fetchOptions.method = 'PUT';
        fetchOptions.body = JSON.stringify( Object.fromEntries(formData) )

        fetch(playlistEditURL, fetchOptions).then( (resp)=> {
            if (resp.status == 200) {
                location.reload()
            } else {
                alert('Error. HTTP CODE: ' + resp.status)
            }
        })
    }
    @endif


   
</script>


@endsection
