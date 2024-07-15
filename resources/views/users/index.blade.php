@extends('layouts.app')

@section('css')
<style>
    .button {
        background-color: #4CAF50; 
        border: none;
        color: white;
        padding: 5px 10px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 12px;
        margin: 2px 1px;
        cursor: pointer;
        border-radius: 8px;
    }

    .button-primary {
        background-color: #4CAF50; 
    }

    .button-secondary {
        background-color: #008CBA; 
    }

    .button-danger {
        background-color: #f44336; 
    }

    .button:hover {
        opacity: 0.8;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table, th, td {
        border: 1px solid black;
    }

    th, td {
        padding: 5px;
        text-align: left;
    }

    td form {
        display: inline;
        margin: 0;
    }

    .wrap-contact100 {
        max-width: 600px;
    }
</style>
@endsection

@section('content')
<div class="container-contact100">
    <div class="wrap-contact100">
        <span class="contact100-form-title fs-39">USERS</span>
        <a class="button button-primary fs-12" href="{{ route('users.create') }}">Create User</a>
        <br>
        @if(session('success'))
            <p>{{ session('success') }}</p>
        @endif
        <table>
            <thead>
                <tr>
                    <th class="fs-12">Name</th>
                    <th class="fs-12">Email</th>
                    <th class="fs-12">Role</th>
                    <th class="fs-12">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="fs-12">{{ Str::limit($user->name, 15, '...') }}</td>
                        <td class="fs-12">{{ Str::limit($user->email, 25, '...') }}</td>
                        <td class="fs-12">{{ $user->role }}</td>
                        <td>
                            <a class="button button-secondary fs-12" href="{{ route('users.edit', $user->id) }}">Edit</a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="button button-danger fs-12" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
