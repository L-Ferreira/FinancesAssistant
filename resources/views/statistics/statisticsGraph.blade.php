@extends('layouts.app')

@section('title', 'Statistical Graph Values')

@section('content')

    <div>{!! $chart->container() !!}</div>

     <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/4.1.0/echarts-en.common.js" charset="utf-8"></script>
     {!! $chart->script() !!}

@endsection