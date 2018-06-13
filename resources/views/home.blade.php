@extends('layouts.app')

@section('content')
    <div class="container">
        @can('isAdmin', Auth::user())
            <div class="d-flex flex-row justify-content-center align-items-center">
                <div class="p-2">
                    <h2> Admin Area </h2>
                </div>
                <div class="p-2">
                    <a type="button" class="btn btn-primary" href="{{ route('showUsers') }}" style="width: 200px">Show Users</a>
                </div>
            </div>
            <div class="dropdown-divider"></div>
        @endcan
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-md-offset-1 ">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex flex-row justify-content-around align-items-center">
                            <div class="p-2">
                                <h2> User Area </h2>
                            </div>
                            <div class="p-2">
                                @include('partials.img', ['photo' => Auth::user()->profile_photo, 'size'=> 150])
                            </div>
                            <div class="p-2">
                                <a href="{{route('me')}}" class="">
                                    <h2>{{Auth::user()->name}}</h2>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="p-2">
                            <div class="p-2">
                                <h2> My Accounts </h2>
                            </div>
                            <div class="p-2">
                                <a  class="btn btn-primary" href="{{ route('showAccounts',Auth::user()) }}">Show Accounts</a>
                                <a class="btn btn-xs btn-primary" href="{{route('account.create')}}">Create account</a>
                            </div>
                        </div>
                        <div class="dropdown-divider p-2"></div>
                        <div class="p-2">
                            <div class="p-2">
                                <h2> My Finances Network </h2>
                            </div>
                            <div class="p-2">
                                <a class="btn btn-xs btn-primary" href="{{route('me.associates')}}">My Associates</a>
                                <a class="btn btn-xs btn-primary" href="{{route('me.associateOf')}}">Associate Of</a>
                            </div>
                        </div>
                        <div class="dropdown-divider p-2"></div>
                        <div class="p-2">
                            <div class="p-2">
                                <h2> Statistics </h2>
                            </div>
                            <div class="p-2">
                                <a  class="btn btn-primary" href="{{ route('statistics.totalBalance', Auth::user()) }}">See statistics</a>
                                <a  class="btn btn-primary" href="{{ route('statistics.form')}}">See Graphics</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection