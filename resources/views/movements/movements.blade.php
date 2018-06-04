
@extends('layouts.app')

@section('title', 'List movements')

@section('content')

    <div class="container">
        @if(count($movements))
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Category</th>
                    <th>Date</th>
                    <th>Value</th>
                    <th>Type</th>
                    <th>End Balance</th>
                    <th>Options</th>
                </tr>
                </thead>
                @foreach($movements as $movement)
                    <tr>
                        <td>{{ $movement->name}}</td>
                        <td>{{ $movement->date}}</td>
                        <td>{{ $movement->value}}</td>
                        <td>{{ $movement->type}}</td>
                        <td>{{ $movement->end_balance}}</td>
                        <td>
                            <div class="input-group">
                                <form  method="GET" action="{{route('edit.movement', $movement->id)}}">
                                    <button class="btn-danger list-inline" type="submit" role="button">Edit</button>
                                </form>
                                <div class="input-group-btn">
                                    <form  method="POST" action="{{route('delete.movement', $movement->id)}}">
                                        <button class="btn-danger list-inline" type="submit" role="button">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>
@endsection
