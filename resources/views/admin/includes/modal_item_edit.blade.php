<script>
	$("#range_1").ionRangeSlider({
		type: "single",
		min: 0.1,
		max: 100,
		step: 0.1
	});
</script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	<h4 class="modal-title">Редактирование предмета</h4>
</div>
<form method="post" action="/admin/item/update" class="horizontal-form" id="save">
<div class="modal-body">
	<input name="id" value="{{ $item->id }}" type="hidden">
	<input name="card" value="{{ $item->card }}" type="hidden">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div class="form-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Цена</label>
					<input type="text" class="form-control" name="cost" value="{{ $item->cost }}">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Тип</label>
					<select class="form-control" tabindex="1" name="type" value="{{ $item->type }}">
						<option value="0" @if($item->type == '0') selected @endif>Выпадающий</option>
						<option value="1" @if($item->type == '1') selected @endif>Не выпадет</option>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group text-center">
					<label class="control-label">Путь к картинке</label>
					<input type="text" class="form-control" name="img" placeholder="Путь к картинке: /style/coin-100.svg" value="{{ $item->image }}">
				</div>
			</div>
		</div>
	</div>
	
</div>
<div class="modal-footer">
	<button type="button" class="btn dark btn-outline" data-dismiss="modal">Закрыть</button>
	<button type="submit" class="btn green"><i class="fa fa-check"></i> Сохранить</button>
</div>
</form>