@extends('admin')

@section('content')
<div class="page-bar">
	<ul class="page-breadcrumb">
		<li>
			<a href="/admin">Главная</a>
			<i class="fa fa-circle"></i>
		</li>
		<li>
			<span>Отзывы</span>
		</li>
	</ul>
</div>
<h1 class="page-title">Отзывы </h1>
<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
      @if(Session::has('alert-' . $msg))

      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
      @endif
    @endforeach
</div> <!-- end .flash-message -->
<div class="row">
	<div class="col-md-12" style="margin-bottom: 20px;">
		<div class="row">
			<form method="post" action="/admin/opinions/create" class="horizontal-form">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="col-md-6">
				<input type="text" class="form-control" placeholder="Имя Фамилия" name="username" value="">
			</div>
			<div class="col-md-6">
				<input type="text" class="form-control" placeholder="Ссылка на страницу пользователя" name="user_link" value="">
			</div>
			<div class="col-md-6">
				<input type="text" class="form-control" placeholder="Ссылка на аватар пользователя" name="user_avatar" value="">
			</div>
			<div class="col-md-6">
				<input type="text" class="form-control" placeholder="Текст отзыва" name="text" value="">
			</div>
			<div class="col-md-6">
				<input type="text" class="form-control" placeholder="Ссылка на вложение" name="photo" value="">
			</div>
			<div class="col-md-6">
				<input type="text" class="form-control" placeholder="Ссылка на оригинал отзыва" name="opinion_link" value="">
			</div>
		</div>
		<div class="row">
			<div class="col-md-10"></div>
			<div class="col-md-2"><button type="submit" class="btn blue"><i class="fa fa-check"></i> Добавить отзыв</button></div>
		</div>
	</div>
	<div class="col-md-12">
		<center><h2>Добавленные отзывы</h2></center>
		<div class="row">
			@foreach($opinions as $o)
			<div class = "col-md-12" style="padding: 20px;border: 1px dashed black;">
				<div class = "col-md-6">
					<b>Пользователь:</b>  <a href="{{$o->user_link}}" target="blank">{{$o->username}}</a><br>
					<b>Тест:</b> {{$o->text}}<br>
					@if(isset($o->photo))<b>Прикрепил:</b> <br> <img src="{{$o->photo}}" width="250px">@endif
				</div>
				<a href="/admin/opinion/{{$o->id}}/delete">
				<div class="btn red pull-right">
					<i class="fa fa-times" aria-hidden="true"></i> Удалить 
				</div>
				</a>
			</div>
			@endforeach
		</div>
	</div>
</div>
@stop