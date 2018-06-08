@extends('layouts.app')


@section('content')
    @if ($errors->any())
        @include('partials.errors')
    @endif
    <div class="container">
        @if(count($documents))
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                </tr>
                </thead>
                @foreach($documents as $document)
                    <tr>
                        <td>{{ $document->original_name}}</td>
                        <td>{{ $document->description}}</td>
                        <td>
                        </td>
                    </tr>
                @endforeach
            </table>
        @else
            <div class="align-content-center">
                <h3>No Documents Associate</h3>
            </div>
        @endif
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-md-offset-1 ">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <h3>Associate Document</h3>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <form action="{{route('document.associateDocument',$movement->id)}}" method="post" class="form-group" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group p-2">
                                <div class="d-flex justify-content-around align-items-center" style="padding: 20px">
                                    <div class="p-2">
                                        <label class="btn btn-primary">
                                            Choose Document
                                            <input  id="document" type="file" name="document_file" style="width: 300px">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group p-2">
                                <label for="inputDescription">Description</label>
                                <textarea class="form-control" name="document_description" id="inputDescription" rows="3"></textarea>
                            </div>
                            <div class="form-group d-flex justify-content-center">
                                <button type="submit" class="btn btn-success" name="ok">Associate Document</button>
                                <a class="btn btn-default " href="{{route('account.movement',$movement->account_id)}}">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection