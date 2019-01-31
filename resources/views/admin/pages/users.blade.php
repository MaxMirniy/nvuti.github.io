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
			<div class="portlet-body">
				<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>Ник</th>
							<th>Баланс</th>
							<th>Управление</th>
						</tr>
					</thead>
					<tbody>
						@foreach($users as $user)
						<tr>
							<td style="vertical-align: middle;">{{$user->id}}</td>
							<td align="center">{{$user->name}} @if($user->is_admin == 1) <i style="color: green;"class="fa fa-gitlab" aria-hidden="true"></i> @endif @if($user->is_ban == 1) <i style="color: red;"class="fa fa-ban" aria-hidden="true"></i> @endif @if($user->is_block == 1) <i style="color: orange;"class="fa fa-times-circle-o" aria-hidden="true"></i> @endif @if($user->yt_user == 1) <i style="color: blue;"class="fa fa-youtube-play" aria-hidden="true"></i> @endif</td>
							<td style="vertical-align: middle;">{{$user->money}}</td>
							<td align="center" style="width: 200px;text-align: center;vertical-align: middle;">
								<button type="button" style="float: left;" class="btn blue btn-sm" data-toggle="modal" data-target="#usr_edit" href="/admin/user/{{ $user->id }}/edit">Редактировать</button>
								<a type="button" style="float: left;" class="btn red btn-sm" href="/admin/user/{{ $user->id }}/delete">Удалить</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="usr_edit" tabindex="-1" role="basic" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			@include('admin.includes.modal_users', ['user' => $user])
		</div>
	</div>
</div>
@endsection
