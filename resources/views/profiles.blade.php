@extends('layouts.app')

@section('content')
    <div class="container">
        @if(count($users))
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Fullname</th>
                    <th>Picture</th>
                </tr>
                </thead>
                @foreach ($users as $user)

                    <tr>
                        <td class="border-left">
                            {{ $user->name}}
                        </td>
                        <td>
                            @include('partials.img', ['photo' =>$user->profile_photo, 'size'=> 50]);
                        </td>
                    </tr>
                @endforeach
            </table>
        @else
            <div class="align-content-center">
                <h2>No users found</h2>
            </div>
        @endif
    </div>
@endsection