@extends('layouts.app')

@section('content')
    <div class="container">
        @if(count($users))
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Picture</th>
                    <th scope="col">Fullname</th>
                    <th scope="col">Profile</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td width="80px">
                            @include('partials.img', ['photo' =>$user->profile_photo, 'size'=> 50])
                        </td>
                        <td class="border-left">
                            {{$user->name}}
                        </td>
                        <td>
                            <a class="btn btn-xs btn-primary" href="{{route('usersProfile', $user->id)}}">Show Profile</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="align-content-center">
                <h2>No users found</h2>
            </div>
        @endif
    </div>
@endsection