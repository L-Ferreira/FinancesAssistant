@extends('layouts.app')

@section('content')
@if ($errors->any())
    @include('partials.errors')
@endif

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-md-offset-1 ">


                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('me.update', $user->id)}}">Edit Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="{{route('me.updatePassword', $user->id)}}">Change Password</a>
                            </li>

                        </ul>
                    </div>

                    <div class="card-body">
                        <form action="{{route('me.update', $user->id)}}" method="post" class="form-group">
                            @method('put')
                            <div class="form-group">
                                <label for="password">New password:</label>
                                <input type="password" name="password" value="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password-confirm">Confirm Password:</label>
                                <input type="password" name="password_confirmation" value="" class="form-control">
                            </div>
                        </form>
                    </div>

                    <div class="card-footer">
                        <div class="form-group d-flex justify-content-center">
                            <button type="submit" class="btn btn-success" name="ok">Save</button>
                            <a class="btn btn-default " href="{{route('me', $user->id)}}">Cancel</a>
                        </div>
                    </div>

                </div>


            </div>
        </div>
    </div>
@endsection