@extends('layouts.master')

@section('title','Сравнение баз данных - Таблицы')

@section('style')
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            @foreach ($tables as $key=>$value)
                <div class="col-md-6 {{$key}}">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span>{{$key}}</span>
                        </div>
                        <div class="panel-body">
                            <ul class="listtables">
                                @foreach ($value as $k=>$v)
                                    <li class="<?= isset($v['status']) ? $v['status'] : '' ?>">
                                        <h4><span>{{$k}}</span></h4>

                                        <ul id="{{$key}}__{{$k}}" class="listfields">
                                            @foreach ($v['fields'] as $kf=>$vf)
                                                <li class="<?= isset($vf['status']) ? $vf['status'] : '' ?>">
                                                    <span class="field">{{$kf}}</span>
                                                    <span class="field-type">{{$vf['type']}}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
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
