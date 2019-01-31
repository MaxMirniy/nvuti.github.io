@extends('admin')

@section('content')
<div class="page-bar">
	<ul class="page-breadcrumb">
		<li>
			<a href="/admin">Главная</a>
			<i class="fa fa-circle"></i>
		</li>
		<li>
			<span>Настройки</span>
		</li>
	</ul>
</div>

<h1 class="page-title"> Настройки сайта </h1>

<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
      @if(Session::has('alert-' . $msg))

      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
      @endif
    @endforeach
</div>

<div class="row">
	<style>
	.bs-callout-danger {
		background-color: #fdf7f7;
		border-color: #d9534f;
	}
	.bs-callout {
		margin: 20px 0;
		padding: 20px;
		border-left: 3px solid #eee;
	}
	.bs-callout-danger h4 {
		color: #d9534f;
	}
	.bs-callout h4 {
		margin-top: 0;
		margin-bottom: 5px;
	}
	bs-callout p:last-child {
		margin-bottom: 0;
	}
	</style>
	<div class="col-md-12">
		<div class="bs-callout bs-callout-danger">
			<h4>URL'ы для настройки кассы: </h4>
			<p>В настройках кассы укажите следующие ссылки:<br>
			<b>URL оповещения: <code>{{ $_SERVER['HTTP_HOST'].'/sukaoplati190' }}</code>, метод: <code>GET</code><br>
			URL успешной / неуспешной оплаты: <code>{{ $_SERVER['HTTP_HOST'].'/yauspel' }}</code>, метод: <code>GET</code></b></p>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-body">
				<form method="post" action="/admin/settings/save" class="horizontal-form">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<div class="form-body">
						<div class="row">
							<div class="col-md-12">
								<div class="portlet-title">
									<div class="form-group">
										<div class="caption font-red-sunglo">
											<span class="caption-subject bold uppercase">Основные настройки</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label">Название сайта</label>
									<input type="text" class="form-control" placeholder="Название сайта" name="sitename" value="{{ $settings->sitename }}">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label">Валюта сайта</label>
									<input type="text" class="form-control" placeholder="Валюта сайта" name="sitewallet" value="{{ $settings->sitewallet }}">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label">Футер сайта</label>
									<input type="text" class="form-control" placeholder="Футер сайта" name="sitefooter" value="{{ $settings->sitefooter }}">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label">Ключ сайта(Recaptcha)</label>
									<input type="text" class="form-control" placeholder="Ключ сайта(Recaptcha)" name="recaptcha" value="{{ $settings->recaptcha }}">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label">Фон сайта(files)</label>
									<input type="text" class="form-control" placeholder="Название фона" name="sitebgc" value="{{ $settings->sitebgc }}">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label">Иконка сайта(files)</label>
									<input type="text" class="form-control" placeholder="Название иконки" name="sitefav" value="{{ $settings->sitefav }}">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">Сумма минимального пополнения</label>
									<input type="number" class="form-control" placeholder="20" name="min_dep" value="{{ $settings->min_dep }}" onchange="if (this.value < 0) this.value=0">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">Сумма минимального вывода</label>
									<input type="number" class="form-control" placeholder="100" name="min_width" value="{{ $settings->min_width }}" onchange="if (this.value < 0) this.value=0">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">Ссылка на группу VK</label>
									<input type="text" class="form-control" placeholder="https://vk.com/cheap.scripts" name="vk_group" value="{{ $settings->vk_group }}">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label">Процент отчисления пригласившему</label>
									<input type="number" class="form-control" placeholder="5" name="tttk" value="{{ $settings->ref_percent }}">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="portlet-title">
									<div class="form-group">
										<div class="caption font-red-sunglo">
											<span class="caption-subject bold uppercase">Настройка оплаты</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">Платежный шлюз:</label>
									<input type="text" class="form-control" name="amount" value="Free-Kassa" readonly="readonly">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">ID Магазина (FK)</label>
									<input type="number" class="form-control" placeholder="ID Магазина" name="fk_id" value="{{ $settings->fk_id }}" onchange="if (this.value < 0) this.value=0">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">Секретный ключ #1 (FK)</label>
									<input type="text" class="form-control" placeholder="Секретный ключ #1" name="fk_secret1" value="{{ $settings->fk_secret1 }}">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label class="control-label">Секретный ключ #2 (FK)</label>
									<input type="text" class="form-control" placeholder="Секретный ключ #2" name="fk_secret2" value="{{ $settings->fk_secret2 }}">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="portlet-title">
									<div class="form-group">
										<div class="caption font-red-sunglo">
											<span class="caption-subject bold uppercase">Настройка авторизации через VK</span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label">ID приложения VK</label>
									<input type="number" class="form-control" placeholder="5777698" name="vk_id" value="{{ $settings->vk_id }}" onchange="if (this.value < 0) this.value=0">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label">Секретный ключ</label>
									<input type="text" class="form-control" placeholder="OG54RLqYjZms27ZsDajS" name="vk_secret" value="{{ $settings->vk_secret }}">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label">Redirect URI</label>
									<input type="text" class="form-control" placeholder="storegamer.ru" name="vk_redirect_uri" value="{{ $settings->vk_redirect_uri }}">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="portlet-title">
									<div class="form-group">
										<div class="caption font-red-sunglo">
											<span class="caption-subject bold uppercase">Настройка бутафории</span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="control-label">Email поддержки</label>
									<input type="text" class="form-control" placeholder="tech@storegamer.ru" name="adm_email" value="{{ $settings->adm_email }}" >
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="portlet-title">
									<div class="form-group">
										<div class="caption font-red-sunglo">
											<span class="caption-subject bold uppercase">Настройка получения бонуса</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label">Бонус при регистрации</label>
									<input type="number" class="form-control" placeholder="100" name="money_bonus" value="{{ $settings->money_bonus }}" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label">ID группы</label>
									<input type="number" class="form-control" placeholder="166634684" name="vk_g_id" value="{{ $settings->vk_g_id }}" >
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label">Токен от группы</label>
									<input type="text" class="form-control" placeholder="Токен от группы" name="vk_g_token" value="{{ $settings->vk_g_token }}" >
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<div class="portlet-title">
									<div class="form-group">
										<div class="caption font-red-sunglo">
											<span class="caption-subject bold uppercase">Настройка ботов</span>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label">+К онлайну</label>
									<input type="number" class="form-control" placeholder="517" name="online" value="{{ $settings->online }}" onchange="if (this.value < 0) this.value=0">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label">Задержка ботов от</label>
									<input type="number" class="form-control" placeholder="3000" name="bot_deop1" value="{{ $settings->bot_deop1 }}" onchange="if (this.value < 0) this.value=0">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label">до (мс)</label>
									<input type="number" class="form-control" placeholder="5000" name="bot_deop2" value="{{ $settings->bot_deop2 }}" onchange="if (this.value < 0) this.value=0">
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


@endsection
