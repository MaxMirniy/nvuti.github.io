@extends('admin')

@section('content')
<div class="page-bar">
	<ul class="page-breadcrumb">
		<li>
			<a href="/admin">Главная</a>
			<i class="fa fa-circle"></i>
		</li>
		<li>
			<span>Поддержка</span>
		</li>
	</ul>
</div>

<h1 class="page-title"> Поддержка </h1>

<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
      @if(Session::has('alert-' . $msg))

      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
      @endif
    @endforeach
</div>
<div class="row">
	@foreach($a as $b)
			<div class="col-xs-12" style="background-color: #f3f3f3; border: 1px solid #aaa; margin-bottom: 20px;">
				<div class="col-xs-1" style="margin-top: 20px; margin-bottom: 20px;">
					<img src="{{ $b->avatar }}" alt="" class="img-circle" width="50px">
				</div>
				<div class="col-xs-8" style="margin-top: 20px; margin-bottom: 20px;">
					<span style="display: block;">Имя: <b>{{ $b->name }}</b></span>
					<span style="display: block;">E-mail: <b>{{ $b->email }}</b></span>
					<span style="display: block;">Тема: <b>{{ $b->theme }}</b></span>
					<span style="display: block;">Текст: <b>{{ $b->theme_text }}</b></span>
					@if($b->ban == 1)
						<span style="display: block; color: #6d0000;"><b>Пользователь забанен</b></span>
					@endif
				</div>
				<div class="col-xs-3" style="margin-top: 20px;">
					<div class="col-xs-6" style="margin-bottom: 20px; text-align: center;">
						<a href="https://vk.com/{{ $b->login }}" style="text-decoration:none;"><div class="btn-primary">Страница VK</div></a>
					</div>
					@if($b->ban == 0)
						<div class="col-xs-6" style="margin-bottom: 20px; text-align: center;">
							<a href="/admin/user/{{ $b->user_id }}/ban" style="text-decoration:none;"><div class="btn-danger">Ban</div></a>
						</div>
					@endif
					<div class="col-xs-6" style="margin-bottom: 20px; text-align: center;">
						<a href="/admin/support/{{ $b->id }}/close" style="text-decoration:none;"><div class="btn-success">Close Ticket</div></a>
					</div>
				</div>
			</div>
	@endforeach
</div>
@stop