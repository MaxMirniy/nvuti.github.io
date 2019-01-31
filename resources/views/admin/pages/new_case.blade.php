@extends('admin')

@section('content')
<div class="page-bar">
	<ul class="page-breadcrumb">
		<li>
			<a href="/admin">Главная</a>
			<i class="fa fa-circle"></i>
		</li>
		<li>
			<span>Добавить кейс</span>
		</li>
	</ul>
</div>

<h1 class="page-title"> Создание нового кейса </h1>

<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-body">
				<form method="post" action="/admin/case/save" class="horizontal-form" id="save">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">Название</label>
									<input type="text" class="form-control" placeholder="Название кейса" name="name">
								</div>
							</div>
							<!--/span-->
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">Цена</label>
									<input type="text" class="form-control" placeholder="0" name="cost">
								</div>
							</div>
							<!--/span-->
						</div>
						<!--/row-->
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">Картинка кейса</label>
									<input type="text" class="form-control" name="image" placeholder="Путь к картинке: /style/coin-100.svg" value="">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">Картинка предмета в кейсе</label>
									<input type="text" class="form-control" name="item_image" placeholder="Путь к картинке: /style/coin-100.svg" value="">
								</div>
							</div>
						</div>
						<div class="row" style="margin-top: 10px;
												margin-bottom: 10px;">
							<div class="col-md-12">
								<div class="form-group">
									<label class="col-md-12 control-label text-center"><b>Шанс окупаемости</b></label>
									<div class="col-md-12">
										<input id="range_1" type="text" name="chance" value="20"/>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="form-actions right">
						<button type="submit" class="btn blue"><i class="fa fa-check"></i> Создать </button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


@endsection