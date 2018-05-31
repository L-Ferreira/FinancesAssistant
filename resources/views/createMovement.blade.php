@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('create.movement', $account->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('post')
                            {{--<h3 style="margin-bottom: 25px; text-align: center;">Create Movement</h3>--}}
                            <label for="inputType">Type:</label>
                            <div class="form-group form-check-inline">
                                <input class="form-check-input" type="radio" name="type" id="inputTypeRevenue" value="revenue" checked>
                                <label class="form-check-label">
                                    Revenue
                                </label>
                            </div>
                            <div class="form-group form-check-inline">
                                <input class="form-check-input" type="radio" name="type" id="inputTypeExpense" value="expense">
                                <label class="form-check-label">
                                    Expense
                                </label>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="inputCategory" name="category" placeholder="Category" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="inputDate" name="date" placeholder="date" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="subject" name="value" placeholder="value" required>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" type="textarea" id="inputMessage" placeholder="Description" maxlength="140" rows="7"></textarea>
                                <span class="help-block"><p id="characterLeft" class="help-block ">You have reached the limit</p></span>
                            </div>

                            <button type="button" id="submit" name="submit" class="btn btn-primary pull-right">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection