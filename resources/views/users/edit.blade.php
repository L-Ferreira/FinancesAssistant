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
                                <a class="nav-link active" href="{{route('me.edit')}}">Edit Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('me.editPassword')}}">Change Password</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <form action="{{route('me.update')}}" method="post" class="form-group" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="form-group p-2">
                                <div class="d-flex justify-content-around align-items-center" style="padding: 20px">
                                    <div class="p-2">
                                        @include('partials.img', ['photo' => Auth::user()->profile_photo, 'size'=> 150])
                                    </div>
                                    <div class="p-2">
                                        <label class="btn btn-primary">
                                            Choose file
                                            <input  id="profile_photo" type="file" name="profile_photo" style="width: 300px">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group p-2">
                                <label for="inputFullname">Name</label>
                                <input
                                        type="text" class="form-control"
                                        name="name" id="inputFullname"
                                        placeholder="Name" value="{{old('name', Auth::user()->name)}}" />
                            </div>
                            <div class="form-group p-2">
                                <label for="inputEmail">Email</label>
                                <input
                                        type="email" class="form-control"
                                        name="email" id="inputEmail"
                                        placeholder="Email address" value="{{old('email', Auth::user()->email)}}"/>
                            </div>
                            <div class="form-group p-2">
                                <label for="inputPhone">Phone Number</label>
                                <input
                                        type="text" class="form-control"
                                        name="phone" id="inputPhone"
                                        placeholder="Phone number" value="{{old('phone', Auth::user()->phone)}}"/>
                            </div>
                            <div class="form-group d-flex justify-content-center">
                                <button type="submit" class="btn btn-success" name="ok">Save</button>
                                <a class="btn btn-default " href="{{route('me')}}">Cancel</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection