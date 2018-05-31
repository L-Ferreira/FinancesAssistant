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
                            <h2>Creating an account</h2>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{route('account.store')}}" method="post" class="form-group" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group p-2">
                                <label for="inputAccountType">Account Type</label>
                                <select name="account_type_id" id="inputAccountType" class="form-control">
                                    <option disabled selected> -- select an option -- </option>
                                    <option value="1">Bank account</option>
                                    <option value="2">Pocket money</option>
                                    <option value="3">PayPal account</option>
                                    <option value="4">Credit card</option>
                                    <option value="5">Meal card</option>
                                </select>
                            </div>
                            <div class="form-group p-2">
                                <label for="inputCode">Code</label>
                                <input type="text" class="form-control" name="code" id="inputCode"/>
                            </div>
                            <div class="form-group p-2">
                                <label for="inputStartBalance">Start Balance</label>
                                <input type="number" class="form-control" name="start_balance" id="inputStartBalance"/>
                            </div>
                            <div class="form-group p-2">
                                <label for="inputDate">Start Date</label>
                                <input type="date" class="form-control" name="date" id="inputDate"/>
                            </div>
                            <div class="form-group p-2">
                                <label for="inputDescription">Description</label>
                                <textarea class="form-control" name="description" id="inputDescription" rows="3"></textarea>
                            </div>
                            <div class="form-group d-flex justify-content-center">
                                <button type="submit" class="btn btn-success" name="ok">Save</button>
                                <a class="btn btn-default " href="{{route('me')}}">Cancel</a>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
