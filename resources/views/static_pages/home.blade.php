@extends('layouts.default')

@section('content')
  <div class="jumbotron">
    <h1>sailor 第一式</h1>
    <p class="lead">
      你现在所看到的是 <a href="https://learnku.com/courses/laravel-essential-training">Laravel 入门教程</a> 的示例项目主页。
    </p>
    <p>
      想要和得到之间，还需要做到！
    </p>
    <p>
      <a class="btn btn-lg btn-success" href="{{route('users.create')}}" role="button">现在注册</a>
    </p>
  </div>
@stop