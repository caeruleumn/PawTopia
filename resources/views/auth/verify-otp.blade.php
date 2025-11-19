@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('Verifikasi OTP') }}</div>

                <div class="card-body">
                    @if (session('otp'))
                        <div class="alert alert-info">
                            <strong>Kode OTP Anda: {{ session('otp') }}</strong><br>
                            <small class="text-muted">* Hanya untuk keperluan pengembangan</small>
                        </div>
                    @endif

                    @if (session('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('otp.verify') }}">
                        @csrf
                        <input type="hidden" name="phone" value="{{ $phone }}">
                        <input type="hidden" name="user_type" value="{{ $userType }}">

                        <div class="form-group row mb-3">
                            <label for="otp" class="col-md-4 col-form-label text-md-right">
                                {{ __('Kode OTP') }}
                            </label>

                            <div class="col-md-6">
                                <input id="otp" type="text" 
                                       class="form-control @error('otp') is-invalid @enderror" 
                                       name="otp" 
                                       value="{{ old('otp') }}" 
                                       required 
                                       autocomplete="off"
                                       autofocus
                                       maxlength="6">

                                @error('otp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Verifikasi') }}
                                </button>

                                <a class="btn btn-link" href="{{ route('otp.resend', ['phone' => $phone, 'user_type' => $userType]) }}">
                                    {{ __('Kirim Ulang OTP') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
