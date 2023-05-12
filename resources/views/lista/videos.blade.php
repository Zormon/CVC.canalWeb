@extends('layouts.app')

@section('content')
<form method="post" action="{{ url('/lista/images-save') }}" enctype="multipart/form-data" class="dropzone" id="my-dropzone">
    {{ csrf_field() }}
    <input type="hidden" name="playlistId" value="{{ $lista->id }}">
    <div class="row">
        <div class="col">
            <div class="input-group input-group-lg">
                <input type="text" class="listatitle form-control form-control-lg font-weight-bold" aria-label="Titulo lista" aria-describedby="listaHelp" data-id="{{ $lista->id }}" value="{{ $lista->name }}" readonly>
            </div>

            <div class="dz-message">
                <div class="message"><p id="listaHelp" class="form-text text-muted">{{ __('Drag up to 3 media files to this box to add to the list') }}</p></div>
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
                            <div class="row no-gutters">
                                <div class="col-md-2">
                                    <div class="card">
                                        <a href="{{ '/storage/'.$video->filename }}" style="max-height:250px;overflow:hidden;@if ($video->active == 0) filter:saturate(0); @endif">
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
                                            <div class="col-4">
                                                <label class="small">{{ __('Title') }}</label>
                                                <input type="text" class="mediatitle card-title form-control" aria-label="Titulo" aria-describedby="inputGroup-sizing-lg" data-id="{{ $video->id }}" value="{{ $video->notes }}">
                                            </div>
                                            <div class="col-2">
                                                <label class="small">{{ __('Duration') }}</label>
                                                <input data-id="{{ $video->id }}" type="number" value="{{ $video->duration }}" class="duration card-title form-control">
                                            </div>
                                            <div class="col-3">
                                                <label class="small">{{ __('From') }}</label>
                                                <input aria-describedby="fromHelp" data-id="{{ $video->id }}" type="date" value="{{ date('Y-m-d', strtotime($video->broadcast_from)) }}" class="broadcastfrom card-title form-control">
                                            </div>
                                            <div class="col-3">
                                                <label class="small">{{ __('To') }}</label>
                                                <input aria-describedby="toHelp" data-id="{{ $video->id }}" type="date" value="{{ date('Y-m-d', strtotime($video->broadcast_to)) }}" class="broadcastto card-title form-control">
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
</form>

<style>.ui-state-highlight { width:100% !important; background-color: #FFF9E0; border:4px dashed #FFF3BC; padding: 0px; margin: 0px; }</style>



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
        });

        sortable('.list-group-sortable-connected')[0].addEventListener('sortupdate', function(e) {
            var data = new Array();

            e.detail.destination.items.forEach( function(valor, indice, array) {
                data.push({'id':e.detail.destination.items[indice].getAttribute('data-id'), 'position':indice});
            });


            $.ajax({
                type: "POST",
                url: "/lista/sort",
                headers: { 'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content },
                data: JSON.stringify(data),
                dataType: "json"
            });

        });
    @endif


    $('.mediaActive').click(function(e){
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id'), 'active':this.value}) )
        sendData.url = '/lista/media'
        $.ajax(sendData).done( ()=> { location.reload() })
    })


    $('.mediatitle').change(function(e){
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id'), 'notes':this.value}) )
        sendData.url = '/lista/media'
        $.ajax(sendData)
    })


    $('.broadcastfrom').change( function(e){
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id'), 'broadcast_from':this.value}) )
        sendData.url = '/lista/media'
        $.ajax(sendData)
    })


    $('.duration').change(function(e) {
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id'), 'duration':this.value}) )
        sendData.url = '/lista/media'
        $.ajax(sendData)
    })


    $('.broadcastto').change(function(e){
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id'), 'broadcast_to':this.value}) )
        sendData.url = '/lista/media'
        $.ajax(sendData)
    })


    $('.listatitle').change(function(e){
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id'), 'name':this.value}) )
        sendData.url = '/lista/edit'
        $.ajax(sendData)
    })


    $('.pendientcancel').click(function(e){
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id')}) )
        sendData.url = '/lista/pendiente/del'
        $.ajax(sendData).done( ()=> { location.reload() })
    })



    $('.deleteVideo').click(function(e){
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id')}) )
        sendData.url = '/lista/media/del'
        $.ajax(sendData).done( ()=> { location.reload() })
    })



    $('.deleteLista').click(function(e){
        sendData.data = JSON.stringify( new Array({'id':this.getAttribute('data-id')}) )
        sendData.url = '/lista/del'
        $.ajax(sendData).done( ()=> { location.href = "/lista" })
    })
</script>

@endsection
