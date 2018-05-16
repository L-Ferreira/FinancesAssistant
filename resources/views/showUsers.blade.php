@extends('layouts.app')

@section('title', 'List users')

@section('content')
   <div class="top-right links">
        @auth
            <a href="{{ route('register') }}">Register</a>
        @endauth
    </div>
    @if(count($users))
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Fullname</th>
                <th>Email</th>
                <th>Registered At</th>
                <th>Type</th>
                <th>Status</th>
                <th>Phone Number</th>
            </tr>
            </thead>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name}}</td>
                    <td>{{ $user->email}}</td>
                    <td>{{ $user->created_at }}</td>
                    @if($user->admin == 1)
                        <td>Admin</td>
                    @else
                        <td>---</td>
                    @endif
                    @if($user->blocked == 1)
                        <td>
                            Blocked
                        </td>
                    @else
                        <td>---</td>
                    @endif
                    @if($user->phone == NULL)
                        <td>---</td>
                    @else
                        <td>{{ $user->phone }}</td>
                    @endif
                </tr>
            @endforeach
        </table>
    @else
        <h2>No users found</h2>
    @endif
@endsection