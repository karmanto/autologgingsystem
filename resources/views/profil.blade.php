@extends('layouts.app')

@section('content')
<div class="container-contact100">
    <div class="wrap-contact100">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
        <form class="contact100-form validate-form" action="{{ route('profil.update') }}" method="POST">
        @csrf
            <span class="contact100-form-title fs-39">EDIT PROFIL</span>
            @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li class="fs-12">{{ $error }}</li>
                @endforeach
            </ul>
            @endif
            <div class="wrap-input100 validate-input" data-validate="Tidak boleh kosong">
                <span class="label-input100">Nama PT</span>
                <input class="input100 @error('name') is-invalid @enderror" name="name" type="text" id="name" placeholder="Name" value="{{ old('name', $settings['pt_name']) }}" maxlength="25" required autofocus>
            </div>
            <div class="wrap-input100 validate-input" data-validate="Tidak boleh kosong">
                <span class="label-input100">Awal Jam</span>
                <input class="input100 @error('hour') is-invalid @enderror" name="hour" type="number" id="hour" placeholder="Awal Jam (0-23)" value="{{ old('hour', $settings['print_start_hour']) }}" min="0" max="23" required>
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
