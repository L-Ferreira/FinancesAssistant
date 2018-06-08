
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
                                <form  method="GET" action="{{route('movement.edit', $movement->id)}}">
                                    @csrf
                                    <button class="btn btn-primary list-inline" type="submit" role="button">Edit</button>
                                </form>
                                <form  method="POST" action="{{route('movement.destroy', $movement->id)}}">
                                    @csrf
                                    <button class=" btn btn-danger list-inline" type="submit" role="button">Delete</button>
                                </form>
                                @if(is_null($movement->document_id))
                                    <a type="button" class="btn btn-primary"  href="{{route('document.document',$movement->id)}}">Associate Document</a>
                                @endif
                                @if(!is_null($movement->document_id))
                                    <a type="button" class="btn btn-primary"  href="{{route('document.document',$movement->id)}}">Replace Document</a>
                                    <a type="button" class="btn btn-primary"  href="{{route('document.viewDocument',$movement->document_id)}}">Download</a>
                                    @if(Auth::user()->id == $account->owner_id)
                                        <form action="{{route('document.destroy',$movement->document_id)}}" method="POST" role="form" class="inline">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-danger">Disassociate</button>
                                        </form>
                                    @endif

                                @endif
                            </div>
                        </td>
                        <td>
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>
@endsection
