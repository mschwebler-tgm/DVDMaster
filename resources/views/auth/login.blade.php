@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col l6 m8 s12 offset-l3 offset-m2">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="card">
                        <div class="card-content">
                            <h3>{{ __('Login') }}</h3>
                            <div class="row">
                                <div class="input-field col s6">
                                    <input id="email" type="email"
                                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           name="email"
                                           value="{{ old('email') }}" required>
                                    <label for="email">Email Address</label>
                                </div>
                                <div class="input-field col s6">
                                    <input id="password" type="password"
                                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                           name="password" required>
                                    <label for="email">Password</label>
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                    @endif
                                </div>

                                <div class="col s-6">
                                    <label>
                                        <input type="checkbox"
                                               class="filled-in" {{ old('remember') ? 'checked' : '' }}/>
                                        <span>{{ __('Remember Me') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="card-action" style="display: flex; justify-content: flex-end">
                            <div>
                                <button type="submit" class="btn">
                                    {{ __('Login') }}
                                </button>
                                <a class="btn" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
