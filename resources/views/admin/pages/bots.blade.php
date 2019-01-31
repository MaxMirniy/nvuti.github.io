@extends('admin')

@section('content')
<div class="page-bar">
	<ul class="page-breadcrumb">
		<li>
			<a href="/admin">Главная</a>
			<i class="fa fa-circle"></i>
		</li>
		<li>
			<span>Пользователи</span>
		</li>
	</ul>
</div>

<h1 class="page-title"> Пользователи </h1>
<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
      @if(Session::has('alert-' . $msg))

      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
      @endif
    @endforeach
</div> <!-- end .flash-message -->
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<form method="get" action="/admin/bot/add" class="horizontal-form">
					<div class="portlet-title">
						<div class="form-group">
							<div class="caption font-red-sunglo">
								<span class="caption-subject bold uppercase">Создать бота</span>
							</div>
						</div>
					</div>
					<input type="text" class="form-control" placeholder="Ник, например: Georgio" name="bot_name" value="" >
					<button type="submit" style="position: absolute;top: 50px;right: 37px;" class="btn blue btn-sm" href="/admin/bot/add">Добавить</button>
			</form>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-body">
				<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>Ник</th>
							<th>Управление</th>
						</tr>
					</thead>
					<tbody>
						@foreach($users as $user)
						<tr>
							<td style="vertical-align: middle;">{{$user->id}}</td>
							<td align="center">{{$user->name}}</td>
							<td align="center" style="width: 100px;vertical-align: middle;">
								<a type="button" class="btn red btn-sm" href="/admin/bot/{{ $user->id }}/delete">Удалить</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@endsection
