@extends('admin')

@section('content')
<div class="page-bar">
	<ul class="page-breadcrumb">
		<li>
			<a href="/admin">Главная</a>
			<i class="fa fa-circle"></i>
		</li>
		<li>
			<span>Промо-коды</span>
		</li>
	</ul>
</div>

<h1 class="page-title">Промо-коды </h1>
<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
      @if(Session::has('alert-' . $msg))

      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
      @endif
    @endforeach
</div> <!-- end .flash-message -->
<div class="row">
	<div class="col-md-12">
		<form class="form-horizontal" method="POST" action="/admin/createpromo">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="form-group">
				<div class="col-xs-3">
					<input type="text" required class="form-control" placeholder="Название промо-кода" name='promo'>
				</div>
				<div class="col-xs-3">
					<input type="number" required class="form-control" placeholder="Cумма активации" onchange="if (this.value < 1) this.value=1" name="money">
				</div>
				<div class="col-xs-3">
					<input type="number" required class="form-control" placeholder="Количество активаций" onchange="if (this.value < 1) this.value=1" name="count_active">
				</div>
				<button type="submit" style="position: absolute;right: 15px;margin-top: -1px;" class="btn btn-primary col-xs-2"><i class="fa fa-plus-circle" aria-hidden="true"></i> Создать</button>
			</div>
		</form>
		<div class="portlet light bordered">
			<div class="portlet-body">
				<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>#</th>
							<th>Название</th>
							<th>Сумма за активацию</th>
							<th>Активировали</th>
							<th>Кол-во активаций</th>
							<th>Редактировать</th>
						</tr>
					</thead>
					<tbody>
						<?php $a = \DB::table('promocode')->orderBy('data', 'desc')->take(20)->get(); ?>
						@foreach($a as $b)
						<tr>
							<td style="vertical-align: middle;text-align: center;">{{$b->id}}</td>
							<td style="vertical-align: middle;text-align: center;">{{ $b->promo }}</td>
							<td style="vertical-align: middle;text-align: center;">{{$b->money}}</td>
							<td style="vertical-align: middle;text-align: center;">{{$b->active}}</td>
							<td style="vertical-align: middle;text-align: center;">{{$b->active_limit}}</td>
							<td style="vertical-align: middle;text-align: center;width: 100px;"><a type="button" class="btn red btn-sm" href="/admin/promo/{{ $b->id }}/delete">Удалить</a></td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@endsection
