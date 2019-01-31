<script>
	$("#range_1").ionRangeSlider({
		type: "single",
		min: 0,
		max: 100,
		step: 10,
	});
</script>
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	<h4 class="modal-title">Пользователь: {{ $user->name }}</h4>
</div>
<form method="post" action="/admin/user/save" class="horizontal-form" id="save">
<div class="modal-body">

	<input name="id" value="{{$user->id}}" type="hidden">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<div class="form-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Никнейм</label>
					<input type="text" class="form-control" name="name" value="{{ $user->name }}" readonly="readonly">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Email</label>
					<input type="text" class="form-control" name="email" value="{{ $user->email }}" readonly="readonly">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Баланс</label>
					<input type="number" class="form-control" name="money" value="{{ $user->money }}" onchange="if (this.value < 0) this.value=0">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Админ</label>
					<select class="form-control" tabindex="1" name="is_admin" value="{{ $user->is_admin }}">
						<option value="1" @if($user->is_admin == 1) selected @endif>Да</option>
						<option value="0" @if($user->is_admin == 0) selected @endif>Нет</option>
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label">Заблокирован</label>
					<select class="form-control" tabindex="1" name="is_ban" value="{{ $user->is_ban }}">
						<option value="1" @if($user->is_ban == 1) selected @endif>Да</option>
						<option value="0" @if($user->is_ban == 0) selected @endif>Нет</option>
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label">Подкрутка</label>
					<select class="form-control" tabindex="1" name="yt_user" value="{{ $user->yt_user }}">
						<option value="1" @if($user->yt_user == 1) selected @endif>Да</option>
						<option value="0" @if($user->yt_user == 0) selected @endif>Нет</option>
					</select>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label">Блокировка вывода</label>
					<select class="form-control" tabindex="1" name="is_block" value="{{ $user->is_block }}">
						<option value="1" @if($user->is_block == 1) selected @endif>Да</option>
						<option value="0" @if($user->is_block == 0) selected @endif>Нет</option>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label class="control-label">Пароль:</label>
					<input type="text" class="form-control" name="password" value="{{ $user->password }}">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label">Пополнил на сумму: </label>
					<input type="text" class="form-control" value="{{ $user->payed }} РУБ" readonly="readonly">
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label">Вывел на сумму: </label>
					<input type="text" class="form-control" value="{{ $user->with }} РУБ" readonly="readonly">
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="control-label">Ожидает выплаты: </label>
					<input type="text" class="form-control" value="{{ $user->with0 }} РУБ" readonly="readonly">
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
