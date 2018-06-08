@extends('layouts.app')

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
                            <h2>Movement Update</h2>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{route('movement.update', $movement->id)}}" method="post" class="form-group" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group p-2">
                                <label for="inputCategory">Category</label>
                                <select name="movement_category_id" id="inputCategory" class="form-control">
                                    <option disabled selected> -- select an option -- </option>
                                    @foreach($movement_types as $type)
                                        <option value="<?= $type->id ?>"> <?= $type->name ?> </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group p-2">
                                <label for="inputDate">Date</label>
                                <input type="date" class="form-control" name="date" id="inputDate" value="{{old('date', $movement->date)}}"/>
                            </div>
                            <div class="form-group p-2">
                                <label for="inputValue">Value</label>
                                <input type="number" class="form-control" name="value" id="inputValue" value="{{old('value', $movement->value)}}"/>
                            </div>
                            <div class="form-group p-2">
                                <label for="inputDescription">Description</label>
                                <textarea class="form-control" name="description" id="inputDescription" rows="3" >{{old('description', $movement->description)}}</textarea>
                            </div>
                            <div class="form-group p-2">
                                <label for="inputDocument">Document</label>
                                <input type="file" class="form-control" name="document_file" id="inputDocument" value="{{old('document_file', $movement->document_file)}}"/>
                            </div>
                            <div class="form-group p-2">
                                <label for="inputDocumentDescription">Document Description</label>
                                <textarea class="form-control" name="document_description" id="inputDocumentDescription" rows="3">{{old('document_description', $movement->document_description)}}</textarea>
                            </div>
                            <div class="form-group d-flex justify-content-center">
                                <button type="submit" class="btn btn-success" name="ok">Update</button>
                                <a class="btn btn-default " href="{{route('showAccounts',  Auth::user()->id)}}">Cancel</a>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
