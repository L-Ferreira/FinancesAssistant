@extends('users.profile')

@section('content')
    @if ($errors->any())
        @include('partials.errors')
    @endif
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-md-offset-1 ">


                <div class="card">
                    <div class="card-header">
                        <div class="d-flex flex-row justify-content-around align-items-center">
                            <div class="p-2">
                                @include('partials.img', ['photo' => $user->profile_photo, 'size'=> 150])
                            </div>
                            <div class="p-2">
                                <h2>{{$user->name}}</h2>
                            </div>
                            <div class="p-2">
                                <a class="btn btn-xs btn-primary" href="{{route('me')}}">Return to profile</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(Route::current()->getName() == 'me.associates')
                            <h2>My Associates</h2>
                        @elseif(Route::current()->getName() == 'me.associateOf')
                            <h2>Associate Of</h2>
                        @endif
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">Picture</th>
                                <th scope="col">Fullname</th>
                                <th scope="col">Email</th>
                                <th scope="col">Profile</th>
                                @if(Route::current()->getName() == 'me.associateOf')
                                    <th scope="col">Accounts</th>
                                @elseif(Route::current()->getName() == 'me.associates')
                                    <th scope="col">Action</th>
                                @endif
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
                                        {{$user->email}}
                                    </td>
                                    <td style="width: 200px">
                                        <a class="btn btn-xs btn-primary" href="{{route('usersProfile', $user->id)}} " style="width: 200px">Show Profile</a>
                                    </td>
                                    @if(Route::current()->getName() == 'me.associateOf')
                                        <td>
                                            <a class="btn btn-xs btn-primary" href="{{route('showAccounts', $user->id)}}" style="width: 200px">User's Accounts</a>
                                        </td>
                                    @endif
                                    <td>
                                        <form action="{{route('me.removeAssociate', $user->id)}}" method="POST" role="form" >
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-danger">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                            @if(Route::current()->getName() == 'me.associates')
                                <div class="p-2">
                                    <form action="{{route('me.associates')}}" method="post" class="form-group" enctype="multipart/form-data">
                                        @csrf
                                        <div class="d-flex flex-row align-items-center form-group">
                                            <div class="p-2">
                                                <label for="inputAssociatedUser">Associate a user: </label>
                                            </div>
                                            <div class="p-2" style="width: 620px">
                                                <input type="text" class="form-control" name="associated_user" id="inputAssociatedUser" placeholder="Enter user email"/>
                                            </div>
                                            <div class="ml-auto p-2">
                                                <button type="submit" class="btn btn-success" name="ok">Associate</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif
                    </div>

                    <div class="card-footer">

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
