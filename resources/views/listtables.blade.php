@extends('layouts.master')

@section('title','Сравнение баз данных - Таблицы')

@section('style')
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
@endsection

@section('content')
    <div class="db-compare container-fluid">
        <div class="row">
            @foreach ($tables as $key=>$value)
                <div class="col-md-6 {{$key}}">
                    <div class="db-panel panel panel-default">
                        <div class="panel-heading">
                            <span>{{$key}}</span>
                            @if ($value['countChange'])
                                <sub class="count change" data-show="change">{{$value['countChange']}}</sub>
                            @endif
                            @if ($value['countNew'])
                                <sub class="count new" data-show="new">{{$value['countNew']}}</sub>
                            @endif
                        </div>
                        <div class="panel-body">
                            <ul class="listtables">
                                @foreach ($value['tables'] as $k=>$v)
                                    <li class="{{ isset($v['status']) ? $v['status'] : '' }}">
                                        <h4><span>{{$k}}</span></h4>

                                        <ul id="{{$key}}__{{$k}}" class="listfields">
                                            @foreach ($v['fields'] as $kf=>$vf)
                                                <li class="{{ isset($vf['status']) ? $vf['status'] : '' }}">
                                                    @if (isset($vf['key']) && !empty($vf['key']))
                                                        <i class="key-field key-{{strtolower($vf['key'])}}"></i>
                                                    @endif
                                                    <div class="field-info">
                                                        <span class="field">{{$kf}}</span>
                                                        <span class="field-type">{{$vf['type']}}</span>
                                                    </div>
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
    </div>
@endsection
