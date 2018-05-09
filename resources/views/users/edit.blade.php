@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="panel panel-default col-md-10 col-md-offset-1">
            <div class="panel-heading">
                <h4>
                    <i class="glyphicon glyphicon-edit"></i> 编辑个人资料
                </h4>
            </div>

            @include('common.error')

            <div class="panel-body">

                <form action="{{ route('users.update', $user->id) }}" method="POST" accept-charset="UTF-8">
                    {{method_field('PUT')}}
                    {{csrf_field()}}

                    <div class="form-group">
                        <label for="name">用户名</label>
                        <input class="form-control" type="text" id="name" name="name" value="{{ old('name', $user->name) }}" />
                    </div>

                    <div class="form-group">
                        <label for="email">邮 箱</label>
                        <input class="form-control" type="text" id="email" name="email" value="{{ old('email', $user->email) }}">
                    </div>

                    <div class="form-group">
                        <label for="introduction">个人简介</label>
                        <textarea class="form-control" name="introduction" id="introduction" rows="3" placeholder="说些什么，介绍下自己...">{{ old('introduction', $user->introduction) }}</textarea>
                    </div>

                    <div class="well well-sm">
                        <button type="submit" class="btn btn-primary">保存</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@stop