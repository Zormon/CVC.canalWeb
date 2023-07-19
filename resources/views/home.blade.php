@extends('layouts.app')

@section('content')



                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if(Auth::user()->hasRole('admin'))
                        <h2 class="text-danger">{{ __('You\'re admin!') }}</h2>
                        <h5>{{ __('Welcome to the administration area.') }}</h5>

                        @if (count($pendientes) > 0)
                        <div class="row">
                            <div class="col">
                                <h4>{{ __('Encoding Queue') }}:</h4>
                                <ul class="list-group">
                                    @foreach ($pendientes as $pendiente)
                                <li class="list-group-item list-group-item-warning">
                                    <div class="row">
                                        <div class="col">
                                            <strong>#{{ $pendiente->id }}</strong> - <strong>Usuario:</strong> {{ $pendiente->name }} - <strong>Archivo:</strong> {{ $pendiente->original_name }}
                                        </div>
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

                    @else
                        <h2>{{ __('Dashboard') }}</h2>
                        <h5>{{ __('Welcome to your private area, from here you can manage the content and playlists of your company.') }}</h5>
                        <p>{{ __('Options:') }}</p>
                        <ul>
                        <li>{{ __('Playlists management') }}</li>
                            <li>{{ __('Contents sort & management.') }}</li>
                            <li>{{__('Upload of images and video files.')}}</li>
                            <li>{{ __('Automatic video encoding')}}</li>
                            <li>{{ __('Drag & drop upload support.')}}</li>
                        </ul>
                    <small>{{ __('Last access') }}: {{ $last_visit }}</small>
                    @endif
                </div>



<script>


$('.pendientcancel').click(function(e){

var data = new Array({'id':this.getAttribute('data-id')});

    $.ajax({
        type: "POST",
        url: "/lista/pendiente/del",
        data: JSON.stringify(data),
        headers: {
        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
    },
        dataType: "json"
    }).done( function() {
        location.reload();
    });

});

</script>

@endsection
