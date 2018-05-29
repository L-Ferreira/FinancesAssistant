@extends('layouts.app')

@section('content')
    <div class="container">
        {{--<form action="{{ route('showAccounts',Auth::user()) }}" method="get" class="navbar-form navbar-left">--}}
            <label>Choose one option :</label>
            <a  class="btn btn-primary" href="{{ route('showAccounts',Auth::user()) }}" id="all" >All Accounts</a>
            <a  class="btn btn-primary" href="{{ route('account.opened',Auth::user()) }}" id="opened" >Opened Accounts</a>
            <a  class="btn btn-primary" href="{{ route('account.closed',Auth::user()) }}" id="closed">Closed Accounts </a>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Code</th>
                    <th>Account Type</th>
                    <th>Current Balance</th>
                </tr>
                </thead>
                    @foreach ($accounts as $account)
                        <tr>
                            <td>{{ $account->code}}</td>
                            <td>{{ $account->name}}</td>
                            <td>{{ $account->current_balance }}</td>
                            <td>
                                @if( is_null($account->last_movement_date))
                                    @can('delete',$account)
                                        <form action="{{route('accounts.destroy',$account->id)}}" method="POST" role="form" class="inline">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-danger">Delete</button>
                                        </form>
                                    @endcan
                                @endif
                            </td>
                            <td>
                                @if(is_null($account->deleted_at))
                                    @can('close',$account)
                                        <form action="{{route('close',$account->id)}}" method="POST" role="form" class="inline">
                                            @method('patch')
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-danger">Close</button>
                                        </form>
                                    @endcan
                                @endif
                            </td>
                            <td>
                                @if($account->deleted_at != null)
                                    @can('reopen',$account)
                                        <form action="{{route('reopen',$account->id)}}" method="POST" role="form" class="inline">
                                            @method('patch')
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-danger">Reopen</button>
                                        </form>
                                    @endcan
                                @endif
                            </td>
                        </tr>
                    @endforeach
            </table>
    </div>
@endsection