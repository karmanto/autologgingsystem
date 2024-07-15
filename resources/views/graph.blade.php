<!-- resources/views/graph.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container-contact100">
    <div class="wrap-contact100">
        <form class="contact100-form validate-form" action="{{ route('graphPreview') }}" method="GET">
            <span class="contact100-form-title fs-20" style="color: #ee2244;">{{ $settings['pt_name'] }}</span>
            <span class="contact100-form-title fs-39">GRAFIK</span>
            <div class="wrap-input100 validate-input" data-validate="Tidak boleh kosong">
                <span class="label-input100">Tanggal</span>
                <input class="input100" type="date" name="set_date" min="2010-01-01" id="set_date">
                <span class="focus-input100"></span>
            </div><br><br>

            <div class="container-contact100-form-btn">
                <div class="wrap-contact100-form-btn">
                    <div class="contact100-form-bgbtn"></div>
                    <button class="contact100-form-btn" type="submit">
                        <span>PREVIEW</span>
                    </button>
                </div>
            </div>
        </form><br>
    </div>
</div>
@endsection
