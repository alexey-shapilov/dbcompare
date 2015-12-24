@extends('layouts.master')

@section('title','Сравнение баз данных')

@section('style')
    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
@endsection

@section('content')
    <form class="container-fluid" action="list_tables" method="post">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">Настройки подключения к базе 1</div>
                    <div class="panel-body">
                        <div id="connection1">
                            <div class="form-group">
                                <label for="driver1">Драйвер</label>
                                <select class="form-control" name="driver1" id="driver1">
                                    <option selected="selected" value="mysql">MySQL</option>
                                    <option value="pgsql">Postgres</option>
                                    <option value="sqlite">SQLite</option>
                                    <option value="sqlsrv">SQL Server</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="server1">Сервер</label>
                                <input type="text" class="form-control" name="server1" id="server1"
                                       placeholder="Адрес сервера" value="localhost">
                            </div>
                            <div class="form-group">
                                <label for="database1">Имя базы</label>
                                <input type="text" class="form-control" name="database1" id="database1"
                                       placeholder="Имя базы" value="kruazetru">
                            </div>
                            <div class="form-group">
                                <label for="prefix1">Префикс таблиц</label>
                                <input type="text" class="form-control" name="prefix1" id="prefix1"
                                       placeholder="Префикс таблиц" value="kr_">
                            </div>
                            <div class="form-group">
                                <label for="user1">Пользователь</label>
                                <input type="text" class="form-control" name="user1" id="user1"
                                       placeholder="Пользователь" value="root">
                            </div>
                            <div class="form-group">
                                <label for="password1">Пароль</label>
                                <input type="password" class="form-control" name="password1" id="password1"
                                       placeholder="Пароль">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Настройки подключения к базе 2
                    </div>
                    <div class="panel-body">
                        <div id="connection2">
                            <div class="form-group">
                                <label for="driver2">Драйвер</label>
                                <select class="form-control" name="driver2" id="driver2">
                                    <option selected="selected" value="mysql">MySQL</option>
                                    <option value="pgsql">Postgres</option>
                                    <option value="sqlite">SQLite</option>
                                    <option value="sqlsrv">SQL Server</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="server2">Сервер</label>
                                <input type="text" class="form-control" name="server2" id="server2"
                                       placeholder="Адрес сервера" value="localhost">
                            </div>
                            <div class="form-group">
                                <label for="database2">Имя базы</label>
                                <input type="text" class="form-control" name="database2" id="database2"
                                       placeholder="Имя базы" value="gb_opencart">
                            </div>
                            <div class="form-group">
                                <label for="prefix2">Префикс таблиц</label>
                                <input type="text" class="form-control" name="prefix2" id="prefix2"
                                       placeholder="Префикс таблиц" value="">
                            </div>
                            <div class="form-group">
                                <label for="user2">Пользователь</label>
                                <input type="text" class="form-control" name="user2" id="user2"
                                       placeholder="Пользователь" value="root">
                            </div>
                            <div class="form-group">
                                <label for="password2">Пароль</label>
                                <input type="password" class="form-control" name="password2" id="password2"
                                       placeholder="Пароль">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-lg col-md-12">Начать</button>
            </div>
        </div>
    </form>
@endsection
