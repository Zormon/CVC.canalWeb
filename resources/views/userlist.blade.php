@if( !Auth::user()->hasRole('admin') )
    <script>location='/home'</script>
@else
    @extends('layouts.app')
    @section('content')

    <div class="card-header">{{ __("User's list") }}</div>
    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success" role="alert">{{ session('status') }}</div>
        @endif

        <ul id="results" class="list-group"></ul>
    </div>

    <script>
        $.ajax({
            type: "POST",
            url: "/users/s",
            headers: { 'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content },
            data: '[{"query":""}]',
            dataType: "json"
        }).done(( data )=> {
            $("#results").html("");
            $(data).each((index, item)=> {
                const li = `
                    <li class="list-group-item list-group-item-action">
                        <div class="row">
                            <div class="col">${item.name}</div>
                            <div class="col"><small>${item.username}</small></div>
                            <div class="col">
                                <a class="btn btn-success" href="/users/edit/${item.id}"><i class="fas fa-edit"></i></a>
                                <a class="btn btn-info" href="/usrlist/${item.id}"><i class="fas fa-video"></i></a>
                            </div>
                        </div>
                    </li>`;

                $("#results").append(li);
            });
        });
    </script>
    @endsection

@endif

