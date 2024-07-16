@extends('layouts.app')

@section('content')
<div class="container-contact100">
    <div class="wrap-contact100">
        <span class="contact100-form-title fs-20" style="color: #ee2244;">{{ $settings['pt_name'] }}</span>
        <span class="contact100-form-title fs-39">ONLINE STATUS</span>
        <div>
            <span class="label-input100">
                <label style="font-family: Monospace; font-size: large; color: {{ $info['online_status'] ? '#00FF00' : '#FF0000' }};">
                    {{ $info['online_status'] ? 'ONLINE' : 'OFFLINE' }}
                </label>
            </span>
            <span class="label-input100">
                <label style="font-family: Monospace; font-size: large;">
                    {!! nl2br(e($info['ifconfig'])) !!}
                </label>
            </span>
        </div><br>
    </div>
</div>
@endsection
