<div class="modal fade" id="deletePlaylistModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Are you sure you want to delete this playlist?') }}?</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>{{ __('Remember that deleting this list will also delete all the multimedia files associated with it.') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="deletePlaylist" class="btn btn-danger" data-toggle="modal" data-target="#deletePlaylistModal">{{ __('Delete') }}</button>
            </div>
        </div>
    </div>
</div>