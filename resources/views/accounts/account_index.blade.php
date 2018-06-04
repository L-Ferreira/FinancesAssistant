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
                            @if($account->owner_id == Auth::user()->id)
                                <div class="d-flex justify-content-around">
                                    <td class="d-flex">
                                        @if( is_null($account->last_movement_date))
                                            @can('delete',$account)
                                                <div class="p-2">
                                                    <form action="{{route('accounts.destroy',$account->id)}}" method="POST" role="form" class="inline">
                                                        @method('delete')
                                                        @csrf
                                                        <button type="submit" class="btn btn-xs btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            @endcan
                                        @endif
                                        @if(is_null($account->deleted_at))
                                            @can('close',$account)
                                                <div class="p-2">
                                                    <form action="{{route('close',$account->id)}}" method="POST" role="form" class="inline">
                                                        @method('patch')
                                                        @csrf
                                                        <button type="submit" class="btn btn-xs btn-danger">Close</button>
                                                    </form>
                                                </div>
                                            @endcan
                                        @endif
                                        @if($account->deleted_at != null)
                                            @can('reopen',$account)
                                                <div class="p-2">
                                                    <form action="{{route('reopen',$account->id)}}" method="POST" role="form" class="inline">
                                                        @method('patch')
                                                        @csrf
                                                        <button type="submit" class="btn btn-xs btn-success">Reopen</button>
                                                    </form>
                                                </div>
                                            @endcan
                                        @endif
                                        <div class="p-2">
                                            @can('editAccount', $account)
                                                <a class="btn btn-xs btn-primary" href="{{route('account.edit',$account->id)}}">Edit</a>
                                            @endcan
                                        </div>
                                        @if(!is_null($account->last_movement_date))
                                            <div class="p-2">
                                                <a class="btn btn-primary" href="{{ route('account.movement', $account->id) }}" id="movements" >SeeMovements</a>
                                            </div>
                                        @endif
                                        <div class="p-2">
                                            <a type="button" class="btn btn-xs btn-success" href="{{ route('movement.create', $account->id) }}">Create Movement</a>
                                        </div>
                                    </td>

                                </div>
                            @endif
                        </tr>
                    @endforeach
            </table>
    </div>
@endsection