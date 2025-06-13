@extends('layout.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    {{ __('Two-Factor Authentication') }}
                </div>

                <div class="card-body">
                    <p class="text-muted">
                        {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
                    </p>

                    <form method="POST" action="{{ url('/two-factor-challenge') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="code" class="form-label">
                                {{ __('Authentication Code') }}
                            </label>
                            <input id="code" type="text" 
                                   class="form-control @error('code') is-invalid @enderror" 
                                   name="code" required autofocus>
                            @error('code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Verify') }}
                            </button>
                        </div>
                    </form>

                    <hr>

                    <p class="text-muted">
                        {{ __('Or you can use a recovery code:') }}
                    </p>

                    <form method="POST" action="{{ url('/two-factor-challenge') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="recovery_code" class="form-label">
                                {{ __('Recovery Code') }}
                            </label>
                            <input id="recovery_code" type="text" 
                                   class="form-control @error('recovery_code') is-invalid @enderror" 
                                   name="recovery_code">
                            @error('recovery_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-secondary">
                                {{ __('Use Recovery Code') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection