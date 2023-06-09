@extends('layouts.app')

@section('content')

<div class="card-header">{{ __('Playlists') }}
    @if (auth()->user()->hasRole('admin'))
        <button type="button" class="newlist float-right btn btn-primary"><i class="fas fa-plus"></i> {{__('Playlist')}}</button>
    @endif
</div>

<div class="card-body p-0">
    @if (session('status'))
        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
    @endif
    <div class="list-group list-group-flush">
        @foreach ($playlists as $playlist)
            <a href="{{route('admin.editentry', ['id' => $id,'pid' => $playlist->id])}}" class="list-group-item d-flex justify-content-between align-items-center">
                <h3 class="font-weight-bold text-primary ml-2"><i class="ni ni-tv-2"></i>&nbsp;&nbsp;{{ $playlist->name }} | <small>{{ $playlist->id }}</small></h3>
                <h5>{{ $playlist->Screen_W }} x {{ $playlist->Screen_H }}</h5>
                <h5>{{ $playlist->updated_at }}</h5>
                <div><span class="badge badge-success badge-pill">{{ $counter[$playlist->id]['active'] }}</span>
                <span class="badge badge-secondary badge-pill">{{ $counter[$playlist->id]['noactive'] }}</span></div>
            </a>
        @endforeach
    </div>
</div>

<script type="text/javascript">
    $('.newlist').click( (e)=> {
        $.ajax({
            type: "POST",
            url: "/usrlist/{{ $id }}/lista/new",
            headers: { 'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content },
            dataType: "json"
        }).done( (e)=> {
            location.reload()
        })
    })
</script>
@endsection
