@extends('layouts.master')

@section('title','Сравнение баз данных - Таблицы')

@section('style')
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
@endsection

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"></div>
                    <div class="panel-body">
                        <ul class="listables">
                            @foreach ($tablesDiff['tables'] as $table)
                                <li>
                                    <input type="checkbox" aria-label="..."><span> {{$table}}</span>
                                </li>
                                <ul id="{{$table}}">
                                    @foreach ($tablesDiff[$table] as $column)
                                        <li class="<?= isset($column->status) ? $column->status : '' ?>">{{$column->Field}}</li>
                                    @endforeach
                                </ul>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{--<div class="row">--}}
            {{--@foreach ($tables as $key=>$value)--}}
                {{--<div class="col-md-6 {{$key}}">--}}
                    {{--<div class="panel panel-default">--}}
                        {{--<div class="panel-heading">Список таблиц <strong>{{$key}}</strong> (префикс:--}}
                            {{--<strong>{{$value['prefix']}}</strong>)--}}
                        {{--</div>--}}
                        {{--<div class="panel-body">--}}
                            {{--<ul id="{{$key}}" class="listables">--}}
                                {{--@foreach ($value['tables'] as $table)--}}
                                    {{--<li>--}}
                                        {{--<input type="checkbox" aria-label="..."><span> {{$table}}</span>--}}
                                    {{--</li>--}}
                                {{--@endforeach--}}
                            {{--</ul>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--@endforeach--}}
        {{--</div>--}}
    </div>
@endsection
