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

        <div class="input-group input-group-lg">
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroup-sizing-lg">{{ __('Search') }}</span>
            </div>
            <input name="query" id="query" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg">
        </div>

        <ul id="results" class="list-group"></ul>
    </div>

    <script>
        var data = new Array({'query':''});
        $.ajax({
            type: "POST",
            url: "/users/s",
            headers: { 'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content },
            data: JSON.stringify(data),
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


        $('#query').keyup((e)=> {
            if($('#query').val().length > 3){
                var data = new Array({'query':$('#query').val()});

                $.ajax({
                    type: "POST",
                    url: "/users/s",
                    headers: {
                    'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
                },
                    data: JSON.stringify(data),
                    dataType: "json"
                }).done(( data )=> {
                    $("#results").html("");
                    $(data).each((index, item)=> {
                        $("#results").html('<li class="list-group-item list-group-item-action"><div class="row"><div class="col">' + item.name +'</div><div class="col"><small>' + item.email +'</small></div><div class="col"><a class="btn btn-primary" href="/users/edit/' + item.userId + '"><i class="fas fa-id-card"></i></a>&nbsp;<a class="btn btn-primary" href="/usrlist/' + item.userId + '"><i class="fas fa-video"></i></a></div></div></li>');
                    });
                });
            }

            if( $('#query').val().length == 0 ) {
                var data = new Array({'query':''});

                $.ajax({
                    type: "POST",
                    url: "/users/s",
                    headers: { 'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content },
                    data: JSON.stringify(data),
                    dataType: "json"
                }).done(( data )=> {
                    $("#results").html("");

                    $(data).each( (index, item)=> {
                        $("#results").append('<li class="list-group-item list-group-item-action"><div class="row"><div class="col">' + item.name +'</div><div class="col"><small>' + item.email +'</small></div><div class="col"><a class="btn btn-primary" href="/users/edit/' + item.userId + '"><i class="fas fa-id-card"></i></a>&nbsp;<a class="btn btn-primary" href="/usrlist/' + item.userId + '"><i class="fas fa-video"></i></a></div></div></li>');
                    });
                });
            }
        });
    </script>
    @endsection

@endif

