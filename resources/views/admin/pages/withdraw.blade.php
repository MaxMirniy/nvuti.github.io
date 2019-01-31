@extends('admin')

@section('content')
<div class="page-bar">
	<ul class="page-breadcrumb">
		<li>
			<a href="/admin">Главная</a>
			<i class="fa fa-circle"></i>
		</li>
		<li>
			<span>Выводы</span>
		</li>
	</ul>
</div>

<h1 class="page-title"> Выводы пользователей </h1>

<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
      @if(Session::has('alert-' . $msg))

      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
      @endif
    @endforeach
</div>

<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-body">
				<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>Пользователь</th>
							<th>Система</th>
							<th>Кошелек</th>
							<th>Сумма</th>
							<th>Время</th>
							<th>Статус</th>
							<th>Редактировать</th>
						</tr>
					</thead>
					<tbody>
					@if(isset($withdrows))
						@foreach($withdrows as $withdrow)
						<?php
							if($withdrow->system == 1) { $img = '/files/ya.png'; } // ЯНДЕКС
							elseif($withdrow->system == 2) { $img = '/files/payeer.png'; }
							elseif($withdrow->system == 3) { $img = '/files/wm.png'; }
							elseif($withdrow->system == 4) { $img = '/files/qiwi.png'; }
							elseif($withdrow->system == 5) { $img = '/files/beeline.png'; }
							elseif($withdrow->system == 6) { $img = '/files/megafon.png'; }
							elseif($withdrow->system == 7) { $img = '/files/mts.png'; }
							elseif($withdrow->system == 8) { $img = '/files/tele.png'; }
							elseif($withdrow->system == 9) { $img = '/files/visa.png'; }
							elseif($withdrow->system == 10) { $img = '/files/mc.png'; }
						?>
						<tr>
							<td style="vertical-align: middle;">{{$withdrow->id}}</td>
							<td style="vertical-align: middle;"><a href="https://vk.com/{{ $withdrow->user->bonus_url }}" target="_blank">{{ $withdrow->user->name }}</a> | <div data-toggle="modal" data-target="#user_edit" href="/admin/user/{{ $withdrow->user->id }}/edit" style="display: inline-block;
																									cursor: pointer;">Инфо</div></td>
							<td style="vertical-align: middle;">
																<center><img src="{{$img}}" ></center>

							<td style="vertical-align: middle;">{{$withdrow->wallet}}</td>
							<td style="vertical-align: middle;">{{$withdrow->amount}}</td>
							<td style="vertical-align: middle;">{{$withdrow->dfh}}</td>
																@if(isset($withdrow->status))
							<td style="vertical-align: middle;">@if($withdrow->status == 0)
																<div class="btn green btn-sm">Ожидает</div>
																@elseif($withdrow->status == 1)
																<div class="btn orange btn-sm">Выплачено</div>
																@elseif($withdrow->status == 2)
																<div class="btn red btn-sm">Отказано</div>
																@endif</td>
																@endif
							<td style="width: 150px;vertical-align: middle;">@if(isset($withdrow->status) && isset($withdrow->id)) @if($withdrow->status == 0)<a class="btn blue btn-sm" data-toggle="modal" data-target="#usr_edit" href="/admin/withdraw/{{ $withdrow->id }}/edit">Редактировать</a>@endif @endif</td>
						</tr>
						@endforeach
					@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@if(!empty($withdrows))
<div class="modal fade" id="usr_edit" tabindex="-1" role="basic" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			@if(isset($withdrow))
			@include('admin.includes.modal_withdrows', ['user' => $withdrow])
			@else
			@endif
		</div>
	</div>
</div>
<div class="modal fade" id="user_edit" tabindex="-1" role="basic" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			@include('admin.includes.modal_users', ['user' => $withdrow->user])
		</div>
	</div>
</div>
@endif
@endsection
