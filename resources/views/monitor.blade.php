<!-- resources/views/monitor.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container-contact100">
    <div class="wrap-contact100">
        <span class="contact100-form-title fs-39">MONITOR ACTIVE MACHINE</span>
        <span class="contact100-form-title fs-20">{{ $settings['pt_name'] }}</span>
        <div>
            <span class="label-input100">
                <label style="font-family: Monospace; font-size:large;">datetime :&nbsp;</label>
                <label id="timeID" style="font-family: Monospace; font-size:large;"></label><br>
            </span>
        </div><br>
        @foreach ($settings['monitor_list'] as $index => $monitor_list)
            @if ($monitor_list['stat'])
                <div class="wrap-input100"
                    <span class="label-input100">
                        <label style="font-weight:bold; font-size:medium;">{{ $settings['name_list'][$index]['nickname'] }}</label>
                        <label style="font-weight:bold; font-size:smaller">&nbsp;&nbsp;|&nbsp;&nbsp;</label>
                        <label style="font-size:smaller;">{{ $settings['name_list'][$index]['fullname'] }}</label>
                        <label style="font-weight:bold; font-size:smaller">&nbsp;&nbsp;|&nbsp;&nbsp;</label>
                        <label style="font-size:smaller;" id="{{ $settings['name_list'][$index]['field'] }}_hr">
                        </label>
                        <label style="font-size:smaller;"> hr</label>
                        <svg id="{{ $settings['name_list'][$index]['field'] }}_lamp" class="drop-down__item-icon">
                            <path d="M9.875,0.625C4.697,0.625,0.5,4.822,0.5,10s4.197,9.375,9.375,9.375S19.25,15.178,19.25,10S15.053,0.625,9.875,0.625"></path>
                        </svg>
                    </span>
                </div>
            @endif
        @endforeach

    </div>
</div>
@endsection

@section('scripts')
<script>
    function loadMonitorData() {
        $.ajax({
            url: '/getMonitor',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#timeID').text(data.time_updated);
                data.monitor_list.forEach(function(monitor, index) {
                    $('#' + monitor.name + '_hr').text((monitor.runseconds / 3600).toFixed(2));
                    if (monitor.stat) {
                        $('#' + monitor.name + '_lamp path').attr('fill', 'lime');
                    } else {
                        $('#' + monitor.name + '_lamp path').attr('fill', 'darkred');
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching monitor data:', error);
            }
        });
    }

    $(document).ready(function() {
        loadMonitorData();
        setInterval(loadMonitorData, 5000);
    });
</script>
@endsection