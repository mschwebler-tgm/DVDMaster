@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col l6 m8 s12 offset-l3 offset-m2">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="card">
                        <div class="card-content">
                            <h3>{{ __('Register') }}</h3>
                            <div class="row">
                                <div class="input-field col l6 m12 s12">
                                    <input id="name" type="text"
                                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           name="name"
                                           value="{{ old('name') }}" required>
                                    <label for="name">{{ __('Name') }}</label>

                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="input-field col l6 m12 s12">
                                    <input id="email" type="email"
                                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           name="email"
                                           value="{{ old('email') }}" required>
                                    <label for="email">{{ __('E-Mail Address') }}</label>
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="input-field col l6 m12 s12">
                                    <input id="password" type="password"
                                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                           name="password" required>
                                    <label for="password">{{ __('Password') }}</label>
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="input-field col l6 m12 s12">
                                    <input id="password-confirm" type="password" class="form-control"
                                           name="password_confirmation" required>
                                    <label for="password-confirm">{{ __('Confirm Password') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-action">
                            <div style="display: flex; justify-content: flex-end">
                                <button type="submit" class="btn btn-primary right">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
