@extends('layouts.app')
@section('content')
            <div class="card">
                <div class="card-header">{{ __('Edit Profile') }}</div>

                <div class="card-body">

                    @if (\Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <h4 class="text-white"><i class="ni ni-bulb-61"></i> {!! \Session::get('success') !!}</h4>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                         </div>
                    @endif

                    <form method="POST" enctype="multipart/form-data" action="{{ route('saveuser') }}" autocomplete="off" class="needs-validation" novalidate>
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" readonly type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $user->name }}" required autocomplete="false" autofocus>
                            <input type="hidden" name="id" value="{{ $user->id }}">
                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username') }}</label>

                            <div class="col-md-6">
                                <input id="username" readonly type="username" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ $user->username }}" autocomplete="false" required>

                                @if ($errors->has('username'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input minlength=8 placeholder="Password" id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" autocomplete="false">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input minlength=8  placeholder="Password" id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="false">
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>

                            <div class="col-md-6">
                                <input placeholder="{{ __('Address') }}" id="address" type="text" class="form-control" name="address" value="{{ isset($user->address)?$user->address:"" }}" autocomplete="false">
                            </div>
                        </div>


                        <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right"> </label>
                                <div class="col-md-6">
                                        <h3 class="text-center">{{ __('Schedule') }}</h3>
                                </div>
                            </div>

                        <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right"> </label>
                                <div class="col-md-3">
                                    <h3 class="text-center">{{ __('AM') }}</h3>
                                </div>
                                <div class="col-md-3">
                                    <h3 class="text-center">{{ __('PM') }}</h3>
                                </div>
                        </div>

                        <div class="form-group row">
                                <label for="MondayAm" class="col-md-4 col-form-label text-md-right">{{ __('Monday') }}</label>
                                <div class="col-md-3">
                                    <input placeholder="{{ __('Ex: 8:00') }}" id="MondayAm" type="text" class="form-control" name="schedule[M][AM]" value="{!!(isset($user->schedule->M->AM)) ? $user->schedule->M->AM : '' !!}" autocomplete="false">
                                </div>
                                <div class="col-md-3">
                                    <input placeholder="{{ __('Ex: 16:00') }}" id="MondayPm" type="text" class="form-control" name="schedule[M][PM]" value="{!!(isset($user->schedule->M->PM)) ? $user->schedule->M->PM : '' !!}" autocomplete="false">
                                </div>
                        </div>
                        <div class="form-group row">
                            <label for="TuesdayAm" class="col-md-4 col-form-label text-md-right">{{ __('Tuesday') }}</label>
                            <div class="col-md-3">
                                <input placeholder="{{ __('Ex: 8:00') }}" id="TuesdayAm" type="text" class="form-control" name="schedule[T][AM]" value="{!!(isset($user->schedule->T->AM)) ? $user->schedule->T->AM : '' !!}" autocomplete="false">
                            </div>
                            <div class="col-md-3">
                                <input placeholder="{{ __('Ex: 16:00') }}" id="TuesdayPm" type="text" class="form-control" name="schedule[T][PM]" value="{!!(isset($user->schedule->T->PM)) ? $user->schedule->T->PM : '' !!}" autocomplete="false">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="WednesdayAm" class="col-md-4 col-form-label text-md-right">{{ __('Wednesday') }}</label>
                            <div class="col-md-3">
                                <input placeholder="{{ __('Ex: 8:00') }}" id="WednesdayAm" type="text" class="form-control" name="schedule[W][AM]" value="{!!(isset($user->schedule->W->AM)) ? $user->schedule->W->AM : '' !!}" autocomplete="false">
                            </div>
                            <div class="col-md-3">
                                <input placeholder="{{ __('Ex: 16:00') }}" id="WednesdayPm" type="text" class="form-control" name="schedule[W][PM]" value="{!!(isset($user->schedule->W->PM)) ? $user->schedule->W->PM : '' !!}" autocomplete="false">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="ThursdayAm" class="col-md-4 col-form-label text-md-right">{{ __('Thursday') }}</label>
                            <div class="col-md-3">
                                <input placeholder="{{ __('Ex: 8:00') }}" id="ThursdayAm" type="text" class="form-control" name="schedule[TH][AM]" value="{!!(isset($user->schedule->TH->AM)) ? $user->schedule->TH->AM : '' !!}" autocomplete="false">
                            </div>
                            <div class="col-md-3">
                                <input placeholder="{{ __('Ex: 16:00') }}" id="ThursdayPm" type="text" class="form-control" name="schedule[TH][PM]" value="{!!(isset($user->schedule->TH->PM)) ? $user->schedule->TH->PM : '' !!}" autocomplete="false">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="FridayAm" class="col-md-4 col-form-label text-md-right">{{ __('Friday') }}</label>
                            <div class="col-md-3">
                                <input placeholder="{{ __('Ex: 8:00') }}" id="FridayAm" type="text" class="form-control" name="schedule[F][AM]" value="{!!(isset($user->schedule->F->AM)) ? $user->schedule->F->AM : '' !!}" autocomplete="false">
                            </div>
                            <div class="col-md-3">
                                <input placeholder="{{ __('Ex: 16:00') }}" id="FridayPm" type="text" class="form-control" name="schedule[F][PM]" value="{!!(isset($user->schedule->F->PM)) ? $user->schedule->F->PM : '' !!}" autocomplete="false">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="SaturdayAm" class="col-md-4 col-form-label text-md-right">{{ __('Saturday') }}</label>
                            <div class="col-md-3">
                                <input placeholder="{{ __('Ex: 8:00') }}" id="SaturdayAm" type="text" class="form-control" name="schedule[S][AM]" value="{!!(isset($user->schedule->S->AM)) ? $user->schedule->S->AM : '' !!}" autocomplete="false">
                            </div>
                            <div class="col-md-3">
                                <input placeholder="{{ __('Ex: 16:00') }}" id="SaturdayPm" type="text" class="form-control" name="schedule[S][PM]" value="{!!(isset($user->schedule->S->PM)) ? $user->schedule->S->PM : '' !!}" autocomplete="false">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="SundayAm" class="col-md-4 col-form-label text-md-right">{{ __('Sunday') }}</label>
                            <div class="col-md-3">
                                <input placeholder="{{ __('Ex: 8:00') }}" id="SundayAm" type="text" class="form-control" name="schedule[SU][AM]" value="{!!(isset($user->schedule->SU->AM)) ? $user->schedule->SU->AM : '' !!}" autocomplete="false">
                            </div>
                            <div class="col-md-3">
                                <input placeholder="{{ __('Ex: 16:00') }}" id="SundayPm" type="text" class="form-control" name="schedule[SU][PM]" value="{!!(isset($user->schedule->SU->PM)) ? $user->schedule->SU->PM : '' !!}" autocomplete="false">
                            </div>
                        </div>

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
@endsection
