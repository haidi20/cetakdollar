@extends('layouts.app')

@section('content')
    <div id="main" class="layout-horizontal">
        <div class="align-content-center align-items-center container d-flex justify-content-center mx-auto"
            style="height: 90vh;">
            <div class="row mx-auto">
                <div class="card mx-auto mb-0">
                    <div class="card-body">
                        <h1 class="auth-title" style="font-size: 1.8rem;margin: 0rem 0rem 1.5rem;text-align: center; border-bottom: 1px solid #eee;
                        padding-bottom: 1rem;">HRIS - KPT
                        </h1>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group position-relative has-icon-left mb-3">
                                <input id="name" type="text" autofocus="true" name="name"
                                    class="form-control @error('name') is-invalid @enderror form-control-lg"
                                    placeholder="Masukkan Username">
                                <div class="form-control-icon">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group position-relative has-icon-left">
                                <input type="password" name="password" id="password" class="form-control form-control-lg"
                                    placeholder="Masukkan Password">
                                <div class="form-control-icon">
                                    <i class="bi bi-shield-lock-fill"></i>
                                </div>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <button type="submit"
                                class="btn btn-primary btn-block btn shadow-lg mt-4 mb-2">{{ __('Login') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
