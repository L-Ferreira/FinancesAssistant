@extends('layouts.app')

@section('title', 'List users')

@section('content')

    <div class="container">
        <form action="{{ route('showUsers') }}" method="get" class="navbar-form navbar-left">
            <div class="input-group custom-search-form">
                <input type="text" name="name" class="form-control" placeholder="Search">
            </div>
            <div class="bs-docs-example d-flex" >
                <div class="p-2">
                    <p>Consult per type:</p>
                </div>
                <div class="form-group  p-2" >
                    <select class="selectpicker" data-style="btn-info" name="type">
                        <option disabled selected value> -- select an option -- </option>
                        <option value="admin">Admin</option>
                        <option value="normal">Normal</option>
                    </select>
                </div>
                <div class="p-2">
                    <p>Consult per status:</p>
                </div>
                <div class="form-group p-2">
                    <select class="selectpicker" data-style="btn-info" name="status">
                        <option disabled selected value> -- select an option -- </option>
                        <option value="blocked">Blocked</option>
                        <option value="unblocked">Not Blocked</option>
                    </select>
                </div>
            </div>
            <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary">Search</button>
                    </span>
        </form>

    </div>

    <div class="container">
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
                            <td class="user-is-admin">Admin</td>
                        @else
                            <td>---</td>
                        @endif
                        @if($user->blocked == 1)
                            <td class="user-is-blocked">
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
                        <div class="d-flex justify-content-around">
                            <td class="d-flex">
                                @if(Auth::user()->id != $user->id )
                                    <div class="p-2">
                                        @if(!$user->blocked)
                                            <form  method="POST" action="{{route('block', $user->id)}}" >
                                                @csrf
                                                @method('patch')
                                                <button class="btn btn-danger" type="submit" role="button">Block</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{route('unblock', $user->id)}}">
                                                @csrf
                                                @method('patch')
                                                <button class="btn btn-success" type="submit" role="button">Unblock</button>
                                            </form>
                                        @endif
                                    </div>
                                    <div class="p-2">
                                        @if(!$user->admin)
                                            <form method="POST" action="{{route('promote', $user->id)}}">
                                                @csrf
                                                @method('patch')
                                                <button class="btn btn-primary" type="submit" role="button">Promote</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{route('demote', $user->id)}}">
                                                @csrf
                                                @method('patch')
                                                <button class="btn btn-primary" type="submit" role="button">Demote</button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </div>
                    </tr>
                @endforeach
            </table>
            {{$users->links()}}
        @else
            <div class="align-content-center">
                <h2>No users found</h2>
            </div>
        @endif
    </div>
@endsection