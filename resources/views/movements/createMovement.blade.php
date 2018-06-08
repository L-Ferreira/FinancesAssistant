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
                            <h2>Creating a Movement</h2>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{route('movement.store', $account->id)}}" method="post" class="form-group" enctype="multipart/form-data">
                            @csrf
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
                                <input type="date" class="form-control" name="date" id="inputDate"/>
                            </div>
                            <div class="form-group p-2">
                                <label for="inputValue">Value</label>
                                <input type="number" class="form-control" name="value" id="inputValue"/>
                            </div>
                            <div class="form-group p-2">
                                <label for="inputDescription">Description</label>
                                <textarea class="form-control" name="description" id="inputDescription" rows="3"></textarea>
                            </div>
                            <div class="form-group p-2">
                                <label for="inputDocument">Document</label>
                                <input type="file" class="form-control" name="document_file" id="inputDocument"/>
                            </div>
                            <div class="form-group p-2">
                                <label for="inputDocumentDescription">Document Description</label>
                                <textarea class="form-control" name="document_description" id="inputDocumentDescription" rows="3"></textarea>
                            </div>
                            <div class="form-group d-flex justify-content-center">
                                <button type="submit" class="btn btn-success" name="ok">Create</button>
                                <a class="btn btn-default " href="{{route('showAccounts',  Auth::user()->id)}}">Cancel</a>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
