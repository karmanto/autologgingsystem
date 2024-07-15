@extends('layouts.app')

@section('content')
<div class="container-contact100">
    <div class="wrap-contact100">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="contact100-form validate-form" action="{{ route('insertComment') }}" method="POST">
            @csrf
            <span class="contact100-form-title fs-20" style="color: #ee2244;">{{ $ptName }}</span>
            <span class="contact100-form-title fs-39">INSERT COMMENT</span>
            <span class="contact100-form-title fs-12">Revisi ke - {{ $rev + 1 }}</span>
            <div class="wrap-input100 validate-input" data-validate="Tidak boleh kosong">
                <span class="label-input100">Comment ( karakter ' dan " tidak diperbolehkan )</span>
                <textarea class="input100" id="comment" placeholder="insert comment here" name="comment" required></textarea>
                <span class="focus-input100"></span>
            </div>
            <input type="hidden" id="process_date" name="process_date" value="{{ $set_date }}">
            <input type="hidden" id="rev" name="rev" value="{{ $rev + 1 }}">
            <div class="container-contact100-form-btn">
                <div class="wrap-contact100-form-btn">
                    <div class="contact100-form-bgbtn"></div>
                    <button class="contact100-form-btn" type="submit">
                        <span>SUBMIT</span>
                    </button>
                </div>
            </div>
        </form><br>
    </div>
</div>
@endsection
