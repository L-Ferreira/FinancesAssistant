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
                                <a class="nav-link" href="{{route('me.edit')}}">Edit Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="{{route('me.editPassword')}}">Change Password</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <form action="{{route('me.updatePassword')}}" method="post" class="form-group">
                            @csrf
                            @method('patch')
                            <div class="form-group">
                                <label for="old_password">Current password:</label>
                                <input type="password" name="old_password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password">New password:</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password:</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                            <div class="form-group d-flex justify-content-center">
                                <button type="submit" class="btn btn-success">Save</button>
                                <a class="btn btn-default " href="{{route('me')}}">Cancel</a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection