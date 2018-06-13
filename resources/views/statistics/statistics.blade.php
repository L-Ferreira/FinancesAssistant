@extends('layouts.app')

@section('title', 'List users')

@section('content')
    <div class="container">
        @auth
            <div>
                <h3>Total Balance from all accounts:  {{$balance}}</h3>
            </div>
            <table class="table table-striped">
                <thead>
                    <th>Account Code</th>
                    <th>Account Type</th>
                    <th>Account Balance</th>
                    <th>Total Balance Percentage</th>
                </thead>
                @foreach($accounts as $account)
                    <tr>
                        <td>{{ $account->code}}</td>
                        <td>{{ $account->name}}</td>
                        <td>{{ $account->current_balance }}</td>
                        @if((int)$account->current_balance <= 0 || $balance <= 0)
                            <td>-----</td>
                        @else
                            <td>{{ number_format((($account->current_balance *100)/ ($balance)),2) }} %</td>
                        @endif
                    </tr>
                @endforeach
            </table>
        @endauth
    </div>
@endsection