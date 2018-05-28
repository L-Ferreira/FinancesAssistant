@extends('layouts.app')

@section('content')
    <div class="container">
        @can('isAdmin', Auth::user())
            <a type="button" class="btn btn-primary" href="{{ route('showUsers') }}">Show Users</a>
        @endcan
        <a  class="btn btn-primary" href="{{ route('showAccounts',Auth::user()) }}">Show Accounts</a>
    </div>
@endsection\