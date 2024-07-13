<!-- resources/views/printShow.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Change Logs</h1>

    @foreach ($changeLogs as $field => $logs)
        <h2>{{ $field }}</h2>
        <ul>
            @foreach ($logs as $log)
                <li>{{ $log['changed_at'] }} - {{ $log['change_type'] }}</li>
            @endforeach
        </ul>
    @endforeach
</div>
@endsection
