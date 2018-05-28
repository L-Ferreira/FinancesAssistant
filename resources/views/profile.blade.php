@extends('layouts.app')

@section('content')
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
                                @can('edit', $user)
                                    <a class="btn btn-xs btn-primary" href="{{route('me.edit')}}">Edit</a>
                                @endcan
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="d-flex flex-row justify-content-around align-items-center">
                            <div class="p-2">
                                <a class="btn btn-xs btn-primary" href="{{route('me.associates')}}">My Associates</a>
                            </div>
                            <div class="p-2">
                                <a class="btn btn-xs btn-primary" href="{{route('me.associatesOf')}}">Associate Of</a>
                            </div>
                        </div>
                        @yield('content')
                    </div>
                    <div class="card-footer">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
