<div class="modal fade" id="userDataModal" tabindex="-1" role="dialog">
    <form id="userDataForm">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $modalAction }} {{ __('User') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('User') }}</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="username" pattern="[a-zA-Z]+" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="name" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="{{ $modalAction }}">
                </div>
            </div>
        </div>
    </form>
</div>


<script type="module">
    import {_$} from "{{ asset('js/exports.js') }}";
    var fetchOptions = {
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    }

    const form = _$('userDataForm')

    form.onsubmit = (e)=> {
        e.preventDefault()
        form.reportValidity()

        const formData = new FormData(form)
        fetchOptions.method = 'POST';
        fetchOptions.body = JSON.stringify( Object.fromEntries(formData) )

        fetch("{{ route('users') }}", fetchOptions).then( (resp)=> {
            if (resp.status == 200) {
                location.reload()
            } else {
                alert('Error. HTTP CODE: ' + resp.status)
            }
        })
    }
</script>