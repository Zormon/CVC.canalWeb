<div class="modal fade" id="uploadMediaModal" tabindex="-1" role="dialog">
    <form id="uploadMediaForm">
       <fieldset>
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Upload') }}</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            @if ($isAdmin)
                            <input type="file" class="form-control" name="media[]" required multiple accept=".mp4,.mkv,.webm,.png,.jpg,.webp">
                            @else
                            <input type="file" class="form-control" name="media[]" required multiple accept="video/*,image/*">
                            @endif

                        </div>
                        <div class="form-group row">
                            <div class="col-4">
                                <label class="small">{{ __('Title') }}</label>
                                <input type="text" name="title" class="card-title form-control">
                            </div>
                            <div class="col-4">
                                <label class="small">{{ __('Transition') }}</label>
                                <select class="custom-select transition" name="transition">
                                    <option value="none">none</option>
                                    <option value="fade">fade</option>
                                    <option value="zoom-in">zoom-in</option>
                                    <option value="zoom-out">zoom-out</option>
                                    <option value="slide-left">slide-left</option>
                                    <option value="slide-up">slide-up</option>
                                    <option value="slide-right">slide-right</option>
                                    <option value="slide-down">slide-down</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <label class="small">{{ __('Volume') }}</label>
                                <input type="number" min="0" max="10" name="volume" value="0" required class="card-title form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-3">
                                <label class="small">{{ __('DateFrom') }}</label>
                                <input type="date" name="dateFrom" class="card-title form-control">
                            </div>
                            <div class="col-3">
                                <label class="small">{{ __('DateTo') }}</label>
                                <input type="date" name="dateTo" class="card-title form-control">
                            </div>
                            <div class="col-3">
                                <label class="small">{{ __('Time From') }}</label>
                                <input type="time" name="timeFrom" class="card-title form-control">
                            </div>
                            <div class="col-3">
                                <label class="small">{{ __('Time To') }}</label>
                                <input type="time" name="timeTo" class="card-title form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        @if ($isAdmin)
                        <div class="form-check">
                            <input type="checkbox" id="noCod" name="noCod" checked>
                            <label for="noCod">{{ __('No encode') }}</label>
                        </div>
                        @endif
                        <input type="submit" class="btn btn-primary" value="{{ __('Upload') }}">
                    </div>
                    <progress max="100" value="0"></progress>
                </div>
            </div>
       </fieldset>
    </form>
</div>