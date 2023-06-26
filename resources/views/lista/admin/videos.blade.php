@extends('layouts.app')

@section('content')
                <form method="post" action="{{ url('/lista/images-save') }}" enctype="multipart/form-data" class="dropzone" id="my-dropzone">
                    {{ csrf_field() }}
                    <input type="hidden" name="playlistId" value="{{ $lista->id }}">
                    <div class="row">
                        <div class="col">
                            <!-- Modal -->
                            <div class="modal fade" id="deleteLista" tabindex="-1" role="dialog" aria-labelledby="deleteListaLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteListaLabel">{{ __('Are you sure you want to delete this playlist?') }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <h3 class="parpadea text-danger font-weight-bold">{{ __('WARNING') }}</h3>
                                            <p class="text-danger">{{ __('Remember that deleting this list will also delete all the multimedia files associated with it.') }}</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="deleteLista btn btn-danger" data-id="{{ $lista->id }}">{{__('Delete Playlist')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group">
                                <input type="text" class="listatitle form-control form-control-lg font-weight-bold" data-id="{{ $lista->id }}" value="{{ $lista->name }}">
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteLista"><i class="fas fa-trash"></i></button>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <input type="text" placeholder="{{ __('Music Json URL') }}" class="musicURL form-control" data-id="{{ $lista->id }}" value="{{ $lista->musicURL }}">
                                </div>
                                <div class="col-1">
                                    <input type="number" placeholder="1280" class="widthRes form-control" data-id="{{ $lista->id }}" value="{{ $lista->Screen_W }}">
                                </div>
                                <div class="col-1">
                                    <input type="number" placeholder="720" class="heightRes form-control" data-id="{{ $lista->id }}" value="{{ $lista->Screen_H }}">
                                </div>                                
                                <div class="col-4">
                                    <select name="zonaGuardias" class="zonaGuardias form-control" data-id="{{ $lista->id }}">
                                        <option value="0">Sin guardias</option>
                                        <option value="7">Las Palmas de GC</option>
                                        <option value="17">Telde</option>
                                        <option value="3">Arucas</option>
                                        <option value="5">Gáldar / Guía</option>
                                        <option value="1">Agaete</option>
                                        <option value="2">Aguimes / Ingenio</option>
                                        <option value="4">Firgas</option>
                                        <option value="6">La Aldea de San Nicolás</option>
                                        <option value="8">Mogán</option>
                                        <option value="9">Moya</option>
                                        <option value="10">San Bartolomé de Tirajana</option>
                                        <option value="11">San Bartolomé de Tirajana (Casco)</option>
                                        <option value="12">San Mateo</option>
                                        <option value="13">Santa Brígida</option>
                                        <option value="14">Santa Lucía de Tirajana</option>
                                        <option value="15">Santa Lucía de Tirajana</option>
                                        <option value="16">Tejeda - Artenara</option>
                                        <option value="18">Teror</option>
                                        <option value="19">Valleseco</option>
                                        <option value="20">Valsequillo</option>
                                    </select>
                                </div>
                            </div>
                            

                            <div class="fallback"><p><input type="file" name="file" multiple></p></div>
                            <div id="previewUploader"></div>

                            @if (count($pendientes) > 0)
                                <div class="row">
                                    <div class="col">
                                        <h4>{{ __('Encoding Queue') }}:</h4>
                                        <ul class="list-group">
                                            @foreach ($pendientes as $pendiente)
                                                <li class="list-group-item list-group-item-warning">
                                                    <div class="row">
                                                        <div class="col"><strong>#{{ $pendiente->id }} :</strong> {{ $pendiente->original_name }} <span class="small font-italic">({{ date('d/m/Y h:i:s A',strtotime($pendiente->created_at)) }})</span></div>
                                                        <div class="col text-right">
                                                            @if ($pendiente->encoding==0)
                                                                <button data-id="{{ $pendiente->id }}" type="button" class="pendientcancel btn btn-danger btn-sm">{{ __('Cancel') }}</button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <br />
                            @endif
                        </div>
                    </div>

        @if (count($videos) > 0)
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                    @endif

                    <div id="playlist" class="list-group list-group-sortable-connected">
                        @foreach ($videos as $video)
                            <div class="modal fade" id="deleteModal{{ $video->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">{{ __('Are you sure you want to delete') }} "{{ $video->notes }}"?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body"><p>{{ __('Remember that once it has been deleted it can not be recovered.') }}</p></div>
                                    <div class="modal-footer"><button type="button" class="deleteVideo btn btn-danger" data-id="{{ $video->id }}">{{ __('Delete') }}</button></div>
                                </div>
                                </div>
                            </div>

                            <div data-id="{{ $video->id }}" @if ($video->active == 0) style="background-color:#f4f4f4;" @endif data-position="{{ $video->position }}" href="{{route('editvideos', ['id' => $video->id])}}" class="list-group-item list-group-item-action">
                                <div class="row no-gutters" style="cursor:move;">
                                    <div class="col-md-2">
                                        <div class="card">
                                            <a href="{{ '/storage/'.$video->filename }}" style="cursor:zoom-in;max-height:250px;overflow:hidden;@if ($video->active == 0) filter:saturate(0); @endif">
                                                <img src="{{ '/storage/thumbs/'.$video->resized_name }}" class="card-img-top img-fluid" alt="ID #{{ $video->id }}">
                                            </a>
                                            <div class="card-body p-0 m-0">
                                                <div class="row p-0 m-0">
                                                    <div class="col col-md-4 p-0 m-0">
                                                        <button aria-pressed="true" type="button" class="btn btn-block btn-warning" data-toggle="modal" data-target="#deleteModal{{ $video->id }}" alt="{{ __('Delete') }}" title="{{ __('Delete') }}"><i class="fas fa-trash"></i></button>
                                                    </div>
                                                    <div class="col col-md-8 p-0 m-0">
                                                        @if ($video->active == 1)
                                                            <button aria-pressed="true" type="button" data-id="{{ $video->id }}" value="0" class="mediaActive btn btn-block" alt="{{ __('Stop') }}" title="{{ __('Stop') }}"><i class="fas fa-stop"></i></button>
                                                        @else
                                                            <button aria-pressed="true" type="button" data-id="{{ $video->id }}" value="1" class="mediaActive btn btn-block btn-success" alt="{{ __('Play') }}" title="{{ __('Play') }}"><i class="fas fa-play"></i></button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-10">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-5">
                                                    <label class="small">{{ __('Title') }}</label>
                                                    <input type="text" class="mediatitle card-title form-control" aria-label="Titulo" aria-describedby="inputGroup-sizing-lg" data-id="{{ $video->id }}" value="{{ $video->notes }}">
                                                </div>
                                                <div class="col-3">
                                                    <label class="small">{{ __('Transition') }}</label>
                                                    <select data-id="{{ $video->id }}" class="custom-select transition">
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
                                                    <input data-id="{{ $video->id }}" type="number" min="0" max="10" value="{{ $video->volume }}" class="volume card-title form-control">
                                                </div>
                                                <div class="col-4">
                                                    <label class="small">{{ __('Duration') }}</label>
                                                    <input data-id="{{ $video->id }}" type="number" value="{{ $video->duration }}" class="duration card-title form-control">
                                                </div>
                                                <div class="col-3">
                                                    <label class="small">{{ __('From') }}</label>
                                                    <input aria-describedby="fromHelp" data-id="{{ $video->id }}" type="date" value="{{ $video->broadcast_from? date('Y-m-d', strtotime($video->broadcast_from)) : '' }}" class="broadcastfrom card-title form-control">
                                                </div>
                                                <div class="col-3">
                                                    <label class="small">{{ __('To') }}</label>
                                                    <input aria-describedby="toHelp" data-id="{{ $video->id }}" type="date" value="{{ $video->broadcast_to? date('Y-m-d', strtotime($video->broadcast_to)) : '' }}" class="broadcastto card-title form-control">
                                                </div>
                                                <div class="col-3">
                                                    <label class="small">{{ __('Time From') }}</label>
                                                    <input aria-describedby="timeFromHelp" data-id="{{ $video->id }}" type="time" value="{{ $video->time_from? date('H:i', strtotime($video->time_from)) : '' }}" class="timefrom card-title form-control">
                                                </div>
                                                <div class="col-3">
                                                    <label class="small">{{ __('Time To') }}</label>
                                                    <input aria-describedby="timeToHelp" data-id="{{ $video->id }}" type="time" value="{{ $video->time_to? date('H:i', strtotime($video->time_to)) : '' }}" class="timeto card-title form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    </div>

                </div>
            </div>
            @endif







<style>
  .ui-state-highlight { width:100% !important; background-color: #FFF9E0; border:4px dashed #FFF3BC; padding: 0px; margin: 0px; }
</style>


</form>


<script type="text/javascript">
    var sendData = {
        url: "", data: '',
        type: "POST", dataType: "json",
        headers: { 'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content }
    }

    @if (count($videos) > 0)
        sortable('.list-group-sortable-connected', {
            forcePlaceholderSize: true,
            placeholderClass: 'ui-state-highlight'
        })

        sortable('.list-group-sortable-connected')[0].addEventListener('sortupdate', function(e) {
            var data = new Array()

            e.detail.destination.items.forEach( function(valor, indice, array) {
                data.push({'id':e.detail.destination.items[indice].getAttribute('data-id'), 'position':indice})
            })

            sendData.url = '/usrlist/{{ $id }}/lista/sort'
            sendData.data = JSON.stringify(data)
            $.ajax(sendData)
        })
    @endif



    $('.mediaActive').click(function(e){
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id'), 'active':this.value}) )
        sendData.url = '/usrlist/{{ $id }}/lista/media'
        $.ajax(sendData).done( ()=> { location.reload() })
    })

    $('.mediatitle').change(function(e){
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id'), 'notes':this.value}) )
        sendData.url = '/usrlist/{{ $id }}/lista/media'
        $.ajax(sendData)
    })

    $('.broadcastfrom').change( function(e){
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id'), 'broadcast_from':this.value}) )
        sendData.url = '/usrlist/{{ $id }}/lista/media'
        $.ajax(sendData)
    })
    
    $('.broadcastto').change(function(e){
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id'), 'broadcast_to':this.value}) )
        sendData.url = '/usrlist/{{ $id }}/lista/media'
        $.ajax(sendData)
    })

    $('.timefrom').change( function(e){
        console.log('timeFrom')
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id'), 'time_from':this.value}) )
        sendData.url = '/usrlist/{{ $id }}/lista/media'
        $.ajax(sendData)
    })

    $('.timeto').change(function(e){
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id'), 'time_to':this.value}) )
        sendData.url = '/usrlist/{{ $id }}/lista/media'
        $.ajax(sendData)
    })

    $('.duration').change(function(e) {
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id'), 'duration':this.value}) )
        sendData.url = '/usrlist/{{ $id }}/lista/media'
        $.ajax(sendData)
    })

    $('.transition').change(function(e) {
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id'), 'transition':this.value}) )
        sendData.url = '/usrlist/{{ $id }}/lista/media'
        $.ajax(sendData)
    })

    $('.volume').change(function(e) {
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id'), 'volume':this.value}) )
        sendData.url = '/usrlist/{{ $id }}/lista/media'
        $.ajax(sendData)
    })

    $('.listatitle').change(function(e){
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id'), 'name':this.value}) )
        sendData.url = '/usrlist/{{ $id }}/lista/edit'
        $.ajax(sendData)
    })

    $('.musicURL').change(function(e){
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id'), 'musicURL':this.value}) )
        sendData.url = '/usrlist/{{ $id }}/lista/edit'
        $.ajax(sendData)
    })

    $('.widthRes').change(function(e){
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id'), 'Screen_W':this.value || '1280'}) )
        sendData.url = '/usrlist/{{ $id }}/lista/edit'
        $.ajax(sendData)
    })
    
    $('.heightRes').change(function(e){
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id'), 'Screen_H':this.value || '720'}) )
        sendData.url = '/usrlist/{{ $id }}/lista/edit'
        $.ajax(sendData)
    })

    $('.zonaGuardias').change(function(e){
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id'), 'guardias':this.value}) )
        sendData.url = '/usrlist/{{ $id }}/lista/edit'
        $.ajax(sendData)
    })

    $('.pendientcancel').click(function(e){
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id')}) )
        sendData.url = '/usrlist/{{ $id }}/lista/pendiente/del'
        $.ajax(sendData).done( ()=> { location.reload() })
    })

    $('.deleteVideo').click(function(e){
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id')}) )
        sendData.url = '/usrlist/{{ $id }}/lista/media/del'
        $.ajax(sendData).done( ()=> { location.reload() })
    })

    $('.deleteLista').click(function(e){
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id')}) )
        sendData.url = '/usrlist/{{ $id }}/lista/del'
        $.ajax(sendData).done( ()=> { location.href = "/usrlist/{{ $id }}" })
    })

    $('.zonaGuardias').val('{{ $lista->guardias }}')
</script>

@endsection
