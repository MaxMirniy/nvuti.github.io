@extends('admin')

@section('content')
<div class="page-bar">
	<ul class="page-breadcrumb">
		<li>
			<a href="/admin">Главная</a>
			<i class="fa fa-circle"></i>
		</li>
		<li>
			<span>Редактировать кейс</span>
		</li>
	</ul>
</div>

<h1 class="page-title"> Редактирование кейса </h1>

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
				<form method="post" action="/admin/case/update" class="horizontal-form" id="save">
					<input name="id" value="{{$case->id}}" type="hidden">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label"><b>Название</b></label>
									<input type="text" class="form-control" placeholder="Название кейса" name="name" value="{{ $case->name }}">
								</div>
							</div>
							<!--/span-->
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label"><b>Цена</b></label>
									<input type="text" class="form-control" placeholder="0" name="cost" value="{{ $case->cost }}">
								</div>
							</div>
							<!--/span-->
						</div>
						<!--/row-->
						<div class="row" style="margin-top: 10px;">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label"><b>Картинка</b></label>
									<input type="text" class="form-control" placeholder="Название кейса" name="image" value="{{ $case->image }}">
									<div class="col-md-12">
										<img src="{{ $case->image }}" width="50px">
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label"><b>Картинка предмета</b></label>
									<input type="text" class="form-control" placeholder="Название кейса" name="item_image" value="{{ $case->item_image }}">
									<div class="col-md-12">
										<img src="{{ $case->item_image }}" width="50px">
									</div>
								</div>
							</div>
						</div>
						<div class="row" style="margin-top: 10px;
												margin-bottom: 10px;">
							<div class="col-md-12">
								<div class="form-group">
									<label class="col-md-12 control-label text-center"><b>Шанс окупаемости</b></label>
									<div class="col-md-12">
										<input id="range_1" type="text" name="chance" value="{{ $case->chance }}"/>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="form-actions right">
						<button type="submit" class="btn blue"><i class="fa fa-check"></i> Сохранить </button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<h1 class="page-title"> Список предметов <span style="margin-left: 20px;"><a class="btn btn-lg green" data-toggle="modal" data-target="#add_item" href="/admin/item/{{ $case->id }}/add"> Добавить предмет <i class="fa fa-plus"></i></a></span></h1>

<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-body">
				<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Картинка</th>
							<th>Цена</th>
							<th>Тип</th>
							<th>Управление</th>
						</tr>
					</thead>
					<tbody>
						@foreach($items as $item)
						<tr>
							<td align="center"><img width="50px" src="{{ $item->image }}"/></td>
							<td style="vertical-align: middle;">{{$item->cost}}</td>
							<td style="vertical-align: middle;">@if($item->type == 0) Выпадащий @else Не выпадет @endif</td>
							<td align="center" style="vertical-align: middle;">
								<a class="btn blue btn-sm" data-toggle="modal" data-target="#add_item" href="/admin/item/{{ $item->id }}/edit">Редактировать</a>
								<a class="btn red btn-sm" href="/admin/item/{{ $item->id }}/delete">Удалить</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="add_item" tabindex="-1" role="basic" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			@include('admin.includes.modal_item_add', ['case' => $case])
		</div>
	</div>
</div>

@if(!$item)
<div class="modal fade" id="edit_item" tabindex="-1" role="basic" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			@include('admin.includes.modal_item_edit', ['item' => $item])
		</div>
	</div>
</div>
@else
@endif
@endsection