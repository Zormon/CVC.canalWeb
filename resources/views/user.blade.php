@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header">{{ __('Edit Profile') }}</div>

    <div class="card-body">
        <form id="userdataForm">
            @if ($isAdmin)
            <div class="form-group row">
                <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username') }}</label>
                <div class="col-md-6">
                    <input class="form-control" type="username" name="username" placeholder="{{ __('Username') }}" value="{{ $userData->username }}" required>
                </div>
            </div>
            @endif

            <div class="form-group row">
                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
                <div class="col-md-6">
                    <input class="form-control" type="text" name="name" placeholder="{{ __('Name') }}" value="{{ $userData->name }}" required>
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                <div class="col-md-6">
                    <input id="password" class="form-control" type="password" name="password" placeholder="{{ __('Password') }}" @if ($isAdmin) minlength="8" @endif>
                </div>
            </div>

            <div class="form-group row">
                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
                <div class="col-md-6">
                    <input id="password_confirmation" class="form-control" type="password" placeholder="Password" minlength=8>
                </div>
            </div>


            <div class="form-group row">
                <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>
                <div class="col-md-6">
                    <input class="form-control" type="text" name="address" placeholder="{{ __('Address') }}" value="{{ $userData->address }}">
                </div>
            </div>

            @if ($isAdmin)
            <div class="form-group row">
                <label for="notes" class="col-md-4 col-form-label text-md-right">{{ __('Notes') }}</label>
                <div class="col-md-6">
                    <textarea class="form-control" name="notes">{{ $userData->notes }}</textarea>
                </div>
            </div>
            @endif

        <?php // < Horarios ?>
            <div class="form-group row">
                <label class="col-md-4 col-form-label text-md-right"> </label>
                <div class="col-md-6">
                        <h3 class="text-center">{{ __('On / Off') }}</h3>
                </div>
            </div>
            <div class="form-group row">
                    <label for="MondayAm" class="col-md-4 col-form-label text-md-right">{{ __('Monday') }}</label>
                    <div class="col-md-3">
                        <input class="form-control" type="text" name="power.0.ON" placeholder="{{ __('Ex: 08:00') }}" pattern="([0-1][0-9]|2[0-3]):[0-5][0-9]" value="{!!(isset($userData->power[0]->ON)) ? $userData->power[0]->ON : '' !!}">
                    </div>
                    <div class="col-md-3">
                        <input class="form-control" type="text" name="power.0.OFF" placeholder="{{ __('Ex: 16:00') }}" pattern="([0-1][0-9]|2[0-3]):[0-5][0-9]" value="{!!(isset($userData->power[0]->OFF)) ? $userData->power[0]->OFF : '' !!}">
                    </div>
            </div>
            <div class="form-group row">
                <label for="TuesdayAm" class="col-md-4 col-form-label text-md-right">{{ __('Tuesday') }}</label>
                <div class="col-md-3">
                    <input class="form-control" type="text" name="power.1.ON" placeholder="{{ __('Ex: 08:00') }}" pattern="([0-1][0-9]|2[0-3]):[0-5][0-9]" value="{!!(isset($userData->power[1]->ON)) ? $userData->power[1]->ON : '' !!}">
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="text" name="power.1.OFF" placeholder="{{ __('Ex: 16:00') }}" pattern="([0-1][0-9]|2[0-3]):[0-5][0-9]" value="{!!(isset($userData->power[1]->OFF)) ? $userData->power[1]->OFF : '' !!}">
                </div>
            </div>
            <div class="form-group row">
                <label for="WednesdayAm" class="col-md-4 col-form-label text-md-right">{{ __('Wednesday') }}</label>
                <div class="col-md-3">
                    <input class="form-control" type="text" name="power.2.ON" placeholder="{{ __('Ex: 08:00') }}" pattern="([0-1][0-9]|2[0-3]):[0-5][0-9]" value="{!!(isset($userData->power[2]->ON)) ? $userData->power[2]->ON : '' !!}">
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="text" name="power.2.OFF" placeholder="{{ __('Ex: 16:00') }}" pattern="([0-1][0-9]|2[0-3]):[0-5][0-9]" value="{!!(isset($userData->power[2]->OFF)) ? $userData->power[2]->OFF : '' !!}">
                </div>
            </div>
            <div class="form-group row">
                <label for="ThursdayAm" class="col-md-4 col-form-label text-md-right">{{ __('Thursday') }}</label>
                <div class="col-md-3">
                    <input class="form-control" type="text" name="power.3.ON" placeholder="{{ __('Ex: 08:00') }}"  pattern="([0-1][0-9]|2[0-3]):[0-5][0-9]" value="{!!(isset($userData->power[3]->ON)) ? $userData->power[3]->ON : '' !!}">
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="text" name="power.3.OFF" placeholder="{{ __('Ex: 16:00') }}" pattern="([0-1][0-9]|2[0-3]):[0-5][0-9]" value="{!!(isset($userData->power[3]->OFF)) ? $userData->power[3]->OFF : '' !!}">
                </div>
            </div>
            <div class="form-group row">
                <label for="FridayAm" class="col-md-4 col-form-label text-md-right">{{ __('Friday') }}</label>
                <div class="col-md-3">
                    <input class="form-control" type="text" name="power.4.ON" placeholder="{{ __('Ex: 08:00') }}" pattern="([0-1][0-9]|2[0-3]):[0-5][0-9]" value="{!!(isset($userData->power[4]->ON)) ? $userData->power[4]->ON : '' !!}">
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="text" name="power.4.OFF" placeholder="{{ __('Ex: 16:00') }}" pattern="([0-1][0-9]|2[0-3]):[0-5][0-9]" value="{!!(isset($userData->power[4]->OFF)) ? $userData->power[4]->OFF : '' !!}">
                </div>
            </div>
            <div class="form-group row">
                <label for="SaturdayAm" class="col-md-4 col-form-label text-md-right">{{ __('Saturday') }}</label>
                <div class="col-md-3">
                    <input class="form-control" type="text" name="power.5.ON" placeholder="{{ __('Ex: 08:00') }}" pattern="([0-1][0-9]|2[0-3]):[0-5][0-9]" value="{!!(isset($userData->power[5]->ON)) ? $userData->power[5]->ON : '' !!}">
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="text" name="power.5.OFF" placeholder="{{ __('Ex: 16:00') }}" pattern="([0-1][0-9]|2[0-3]):[0-5][0-9]" value="{!!(isset($userData->power[5]->OFF)) ? $userData->power[5]->OFF : '' !!}">
                </div>
            </div>
            <div class="form-group row">
                <label for="SundayAm" class="col-md-4 col-form-label text-md-right">{{ __('Sunday') }}</label>
                <div class="col-md-3">
                    <input class="form-control" type="text" name="power.6.ON" placeholder="{{ __('Ex: 08:00') }}" pattern="([0-1][0-9]|2[0-3]):[0-5][0-9]" value="{!!(isset($userData->power[6]->ON)) ? $userData->power[6]->ON : '' !!}">
                </div>
                <div class="col-md-3">
                    <input class="form-control" type="text" name="power.6.OFF" placeholder="{{ __('Ex: 16:00') }}" pattern="([0-1][0-9]|2[0-3]):[0-5][0-9]" value="{!!(isset($userData->power[6]->OFF)) ? $userData->power[6]->OFF : '' !!}">
                </div>
            </div>
        <?php // Horarios > ?>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ __('Edit Profile') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="module">
    import {_$} from "{{ asset('js/exports.js') }}";
    var fetchOptions = {
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    }


    const form = _$('userdataForm')
    const password = _$('password')
    const password_confirmation = _$('password_confirmation')

    password.onchange = checkPasswordConfirmation
    password_confirmation.onkeyup = checkPasswordConfirmation

    function checkPasswordConfirmation() {
        if (password.value != password_confirmation.value) {
            password_confirmation.setCustomValidity('{{ __("Password and password confirmation must be the same") }}')
        } else {
            password_confirmation.setCustomValidity('')
        }
    }

    form.onsubmit = (e)=> {
        e.preventDefault()
        form.reportValidity()

        const formData = new FormData(form)
        fetchOptions.method = 'PATCH';
        fetchOptions.body = JSON.stringify( Object.fromEntries(formData) )
        fetch("{{ route('profile.update',[$userData->id]) }}", fetchOptions).then( (resp)=> {
            if (resp.status == 200) {
                @if ($isAdmin)
                    location = "{{ route('users') }}"
                @else
                    location = "{{ route('home') }}"
                @endif
            } else {
                alert('Error. HTTP CODE: ' + resp.status)
            }
        })
    }

    
</script>

@endsection
