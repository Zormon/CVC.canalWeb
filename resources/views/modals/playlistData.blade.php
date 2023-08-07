<div class="modal fade" id="playlistDataModal" tabindex="-1" role="dialog">
    <form id="playlistDataForm">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $modalAction }} {{ __('Playlist') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Playlist name') }}</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="name" value="{{$playlistName}}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="musicURL" class="col-md-4 col-form-label text-md-right">{{ __('Music Json URL') }}</label>
                        <div class="col-md-6">
                            <input type="url" class="form-control" name="musicURL" value="{{$musicJsonURL}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="screenW" class="col-md-4 col-form-label text-md-right">{{ __('Resolution') }}</label>
                        <div class="col-md-3">
                            <input id="screenW" type="number" class="form-control" name="screenW" min="10" max="8192" value="{{$screenW}}" required>
                        </div>
                        <div class="col-md-3">
                            <input id="screenH" type="number" class="form-control" name="screenH" min="10" max="8192" value="{{$screenH}}" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="zonaGuardias" class="col-md-4 col-form-label text-md-right">{{ __('Oncall Zone') }}</label>
                        <div class="col-md-6">
                            <select name="zonaGuardias" class="form-control">
                                <option value="0"   @if ($zonaGuardias=='0') selected @endif>Sin guardias</option>
                                <option value="7"   @if ($zonaGuardias=='7') selected @endif>Las Palmas de GC</option>
                                <option value="17"  @if ($zonaGuardias=='17') selected @endif>Telde</option>
                                <option value="3"   @if ($zonaGuardias=='3') selected @endif>Arucas</option>
                                <option value="5"   @if ($zonaGuardias=='5') selected @endif>Gáldar / Guía</option>
                                <option value="1"   @if ($zonaGuardias=='1') selected @endif>Agaete</option>
                                <option value="2"   @if ($zonaGuardias=='2') selected @endif>Aguimes / Ingenio</option>
                                <option value="4"   @if ($zonaGuardias=='4') selected @endif>Firgas</option>
                                <option value="6"   @if ($zonaGuardias=='6') selected @endif>La Aldea de San Nicolás</option>
                                <option value="8"   @if ($zonaGuardias=='8') selected @endif>Mogán</option>
                                <option value="9"   @if ($zonaGuardias=='9') selected @endif>Moya</option>
                                <option value="10"  @if ($zonaGuardias=='10') selected @endif>San Bartolomé de Tirajana</option>
                                <option value="11"  @if ($zonaGuardias=='11') selected @endif>San Bartolomé de Tirajana (Casco)</option>
                                <option value="12"  @if ($zonaGuardias=='12') selected @endif>San Mateo</option>
                                <option value="13"  @if ($zonaGuardias=='13') selected @endif>Santa Brígida</option>
                                <option value="14"  @if ($zonaGuardias=='14') selected @endif>Santa Lucía de Tirajana</option>
                                <option value="15"  @if ($zonaGuardias=='15') selected @endif>Santa Lucía de Tirajana</option>
                                <option value="16"  @if ($zonaGuardias=='16') selected @endif>Tejeda - Artenara</option>
                                <option value="18"  @if ($zonaGuardias=='18') selected @endif>Teror</option>
                                <option value="19"  @if ($zonaGuardias=='19') selected @endif>Valleseco</option>
                                <option value="20"  @if ($zonaGuardias=='20') selected @endif>Valsequillo</option>
                            </select>
                        </div>
                    </div>
                    @if ($uId)
                    <input type="hidden" name="userId" value="{{ $uId }}">
                    @endif
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="{{ $modalAction }}">
                </div>
            </div>
        </div>
    </form>
</div>