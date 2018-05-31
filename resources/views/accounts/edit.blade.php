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
                            <h2>Updating your account</h2>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{route('account.update',$account->id)}}" method="post" class="form-group" enctype="multipart/form-data">
                            @method('put')
                            @csrf
                            <div class="form-group p-2">
                                <label for="inputAccountType">Account Type</label>
                                <select name="account_type_id" id="inputAccountType" class="form-control">
                                    <option disabled selected> -- select an option -- </option>
                                    <option {{is_selected(old('account_type_id', $account->account_type_id), '1')}} value="1">Bank account</option>
                                    <option {{is_selected(old('account_type_id', $account->account_type_id), '2')}} value="2">Pocket money</option>
                                    <option {{is_selected(old('account_type_id', $account->account_type_id), '3')}} value="3">PayPal account</option>
                                    <option {{is_selected(old('account_type_id', $account->account_type_id), '4')}} value="4">Credit card</option>
                                    <option {{is_selected(old('account_type_id', $account->account_type_id), '5')}} value="5">Meal card</option>
                                </select>
                            </div>
                            <div class="form-group p-2">
                                <label for="inputCode">Code</label>
                                <input type="text" class="form-control" name="code" id="inputCode" value="{{old('code',  $account->code)}}"/>
                            </div>
                            <div class="form-group p-2">
                                <label for="inputStartBalance">Start Balance</label>
                                <input type="number" class="form-control" name="start_balance" id="inputStartBalance" value="{{old('start_balance',  $account->start_balance)}}"/>
                            </div>
                            <div class="form-group p-2">
                                <label for="inputDate">Start Date</label>
                                <input type="date" class="form-control" name="date" id="inputDate" value="{{old('date',  $account->date)}}">
                            </div>
                            <div class="form-group p-2">
                                <label for="inputDescription">Description</label>
                                <textarea class="form-control" name="description" id="inputDescription" rows="3" value="{{old('description',  $account->description)}}"></textarea>
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
