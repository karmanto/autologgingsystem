<!DOCTYPE html>
<html>
<head>
    <title>{{ config('app.name', 'Config Management') }}</title>
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <h1>{{ config('app.name', 'Config Management') }}</h1>

    <a href="{{ route('monitor') }}" style="display: block; margin-bottom: 20px;">&larr; Back to Monitor</a>

    @if (session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="color: red;">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table border="1">
        <thead>
            <tr>
                <th>Field</th>
                <th>Status</th>
                <th>Nickname</th>
                <th>Fullname</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($settings['show_list'] as $index => $field)
                <tr>
                <form action="{{ route('config.update') }}" method="POST">
                    @csrf
                    <td>{{ $field['field'] }}</td>
                    <td>
                        <input type="hidden" name="field" value="{{ $field['field'] }}">
                        <label class="switch">
                            <input type="checkbox" name="stat" value="1" {{ $field['stat'] ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </td>
                    <td>
                        <input type="text" name="nickname" value="{{ $settings['name_list'][$index]['nickname'] ?? '' }}">
                    </td>
                    <td>
                        <input type="text" name="fullname" value="{{ $settings['name_list'][$index]['fullname'] ?? '' }}">
                    </td>
                    <td>
                        <button type="submit">Update</button>
                    </td>
                </form>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
