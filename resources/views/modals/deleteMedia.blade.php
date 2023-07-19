<div class="modal fade" id="deleteMediaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteMediaModalLabel">{{ __('Are you sure you want to delete') }}?</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>{{ __('Remember that once it has been deleted it can not be recovered.') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="deleteMedia" class="btn btn-danger" data-toggle="modal" data-target="#deleteMediaModal">{{ __('Delete') }}</button>
            </div>
        </div>
    </div>
</div>