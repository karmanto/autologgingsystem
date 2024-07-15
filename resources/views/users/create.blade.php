@extends('layouts.app')

@section('content')
<div class="container-contact100">
    <div class="wrap-contact100">
        <form class="contact100-form validate-form" action="{{ route('users.store') }}" method="POST">
        @csrf
            <span class="contact100-form-title fs-39">CREATE USER</span>
            @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="fs-12">{{ $error }}</li>
                @endforeach
            </ul>
            @endif
            <div class="wrap-input100 validate-input" data-validate="Tidak boleh kosong">
                <span class="label-input100">Name</span>
                <input class="input100 @error('name') is-invalid @enderror" name="name" type="text" id="name" placeholder="Name" value="{{ old('name') }}" required autofokus>
            </div>
            <div class="wrap-input100 validate-input" data-validate="Tidak boleh kosong">
                <span class="label-input100">Email</span>
                <input class="input100 @error('email') is-invalid @enderror" name="email" type="email" id="email" placeholder="Email" value="{{ old('email') }}" required autocomplete="email">
            </div>
            <div class="wrap-input100 validate-input" data-validate="Tidak boleh kosong">
                <span class="label-input100">Password</span>
                <input class="input100 @error('password') is-invalid @enderror" name="password" type="password" id="password" placeholder="Password" required>
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
            <div class="container-contact100-form-btn">
                <div class="wrap-contact100-form-btn">
                    <div class="contact100-form-bgbtn"></div>
                    <a href="{{ route('users.index') }}">
                        <button class="contact100-form-btn" type="button">
                            <span>
                                Back
                            </span>
                        </button>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
