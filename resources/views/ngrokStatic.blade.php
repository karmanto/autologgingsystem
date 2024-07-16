@extends('layouts.app')

@section('content')
<div class="container-contact100">
    <div class="wrap-contact100">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
        <form class="contact100-form validate-form" action="{{ route('ngrokStatic') }}" method="POST">
        @csrf
            <span class="contact100-form-title fs-39">NGROK STATIC</span>
            @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="fs-12">{{ $error }}</li>
                @endforeach
            </ul>
            @endif
            <div class="wrap-input100 validate-input" data-validate="Tidak boleh kosong">
                <span class="label-input100">NGROK Static Domain</span>
                <input class="input100 @error('ngrokStatic') is-invalid @enderror" name="ngrokStatic" type="text" id="ngrokStatic" placeholder="ngrok static domain" value="{{ old('ngrokStatic', $settings['ngrok_static']) }}" required autofocus>
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
