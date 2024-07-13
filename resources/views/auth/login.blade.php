<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container-contact100">
    <div class="wrap-contact100">
        <form class="contact100-form validate-form" action="{{ route('login') }}" method="POST">
        @csrf
            <span class="contact100-form-title fs-39">LOGIN</span>
            
            <div class="wrap-input100 validate-input" data-validate="Tidak boleh kosong">
                <span class="label-input100">Alamat Email</span>
                <input class="input100 @error('email') is-invalid @enderror" name="email" type="email" id="email" placeholder="Email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                <span class="focus-input100">{{ $message }}</span>
                @enderror
            </div>
            <div class="wrap-input100 validate-input" data-validate="Tidak boleh kosong">
                <span class="label-input100">Kata Sandi</span>
                <input class="input100 @error('password') is-invalid @enderror" name="password" type="password" id="password" placeholder="Kata Sandi" required autocomplete="current-password">
                @error('password')
                <span class="focus-input100">{{ $message }}</span>
                @enderror
            </div>
            <div class="container-contact100-form-btn">
                <div class="wrap-contact100-form-btn">
                    <div class="contact100-form-bgbtn"></div>
                    <button class="contact100-form-btn" name="Submit" type="submit">
                        <span>
                            Submit
                            <i class="fa fa-long-arrow-right m-l-7" aria-hidden="true"></i>
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection