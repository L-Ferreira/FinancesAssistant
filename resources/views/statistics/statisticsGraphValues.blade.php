@extends('layouts.app')

@section('title', 'Statistical Graph Values')

@section('content')

    <div class="container">
        <form action="{{ route('statistics.with.values') }}" method="post" class="form-group p-2" enctype="multipart/form-data">
            @csrf
            Initial Date
            <div class="p-2">
                <label for="inputInitialDate">Initial Date</label>
                <input type="date" id="inputInitialDate" name="initial_date">
            </div>

            Final Date
            <div class="p-2">
                <label for="inputFinalDate">Final Date</label>
                <input type="date" id="inputFinalDate" name="final_date">
            </div>

            <select class="custom-select" name="category_id">
                <option selected>Open this select menu</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary p-2" name="ok">Get Graph</button>
        </form>
    </div>
@endsection



















