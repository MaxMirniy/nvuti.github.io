@extends('admin')

@section('content')
<div class="page-bar">
	<ul class="page-breadcrumb">
		<li>
			<a href="/admin">Главная</a>
			<i class="fa fa-circle"></i>
		</li>
		<li>
			<span>Панель управления</span>
		</li>
	</ul>
</div>

<h1 class="page-title"> Статистика
	<small>пополнения</small>
</h1>

<div class="row">
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<a class="dashboard-stat dashboard-stat-v2 blue" href="#">
			<div class="visual">
				<i class="fa fa-rub"></i>
			</div>
			<div class="details">
				<div class="number">
					<span data-counter="counterup" data-value="{{ $pay_today }}">{{ $pay_today }}</span>
				</div>
				<div class="desc"> за сегодня </div>
			</div>
		</a>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<a class="dashboard-stat dashboard-stat-v2 red" href="#">
			<div class="visual">
				<i class="fa fa-rub"></i>
			</div>
			<div class="details">
				<div class="number">
					<span data-counter="counterup" data-value="{{ $pay_week }}">{{ $pay_week }}</span></div>
				<div class="desc"> за 7 дней </div>
			</div>
		</a>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<a class="dashboard-stat dashboard-stat-v2 green" href="#">
			<div class="visual">
				<i class="fa fa-rub"></i>
			</div>
			<div class="details">
				<div class="number">
					<span data-counter="counterup" data-value="{{ $pay_month }}">{{ $pay_month }}</span>
				</div>
				<div class="desc"> за месяц </div>
			</div>
		</a>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<a class="dashboard-stat dashboard-stat-v2 purple" href="#">
			<div class="visual">
				<i class="fa fa-rub"></i>
			</div>
			<div class="details">
				<div class="number">
					<span data-counter="counterup" data-value="{{ $pay_all }}">{{ $pay_all }}</span>
				</div>
				<div class="desc"> за все время </div>
			</div>
		</a>
	</div>
</div>
<div class="clearfix"></div>

<h1 class="page-title"> Статистика
	<small>общая</small>
</h1>

<div class="row">
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat2 ">
			<div class="display">
				<div class="number">
					<h3 class="font-green-sharp">
						<span data-counter="counterup" data-value="{{(int)$user_money}}">{{(int)$user_money}}</span>
						<small class="font-green-sharp">руб</small>
					</h3>
					<small>Общий баланс игроков</small>
				</div>
				<div class="icon">
					<i class="icon-pie-chart"></i>
				</div>
			</div>
		</div>
	</div>

	<?php
	$settings = \DB::table('settings')->where('id', 1)->first();
	$rest = file_get_contents("http://www.free-kassa.ru/api.php?merchant_id=$settings->fk_id&action=get_balance&s=".md5("$settings->fk_id"."$settings->fk_secret2"));

	$xml = simplexml_load_string($rest, "SimpleXMLElement", LIBXML_NOCDATA);
	$json = json_encode($xml);
	$ar = json_decode($json,TRUE);
	if(!$data = $ar['balance']){
		$data = 0;
	}
	?>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat2 ">
			<div class="display">
				<div class="number">
					<h3 class="font-red-haze">
						<span data-counter="counterup" data-value="<?php echo $data ?>"><?php echo $data ?></span>
						<small class="font-red-haze">руб</small>
					</h3>
					<small>Баланс кошелька FK</small>
				</div>
				<div class="icon">
					<i class="fa fa-rub"></i>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat2 ">
			<div class="display">
				<div class="number">
					<h3 class="font-blue-sharp">
						<span data-counter="counterup" data-value="{{$opened_today}}">{{$opened_today}}</span>
					</h3>
					<small>Ставок сделано</small>
				</div>
				<div class="icon">
					<i class="icon-basket"></i>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat2 ">
			<div class="display">
				<div class="number">
					<h3 class="font-purple-soft">
						<span data-counter="counterup" data-value="{{$user_today}}">{{$user_today}}</span>
					</h3>
					<small>Новых пользователей</small>
				</div>
				<div class="icon">
					<i class="icon-user"></i>
				</div>
			</div>
		</div>
	</div>
</div>


<h1 class="page-title"> Статистика
	<small>подробная</small>
</h1>
<div class = "row">
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat2 ">
			<div class="display">
				<div class="number">
					<h3 class="font-black-haze">
						<span data-counter="counterup" data-value="{{$pay_yesterday}}">{{$pay_yesterday}} РУБ</span>
					</h3>
					<small>За вчера</small>
				</div>
				<div class="icon">
					<i class="fa fa-info" aria-hidden="true"></i>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="dashboard-stat2 ">
				<div class="display">
					<div class="number">
						<h3 class="font-black-soft">
							<span data-counter="counterup" data-value="{{$pay_3dn}}">{{$pay_3dn}} РУБ</span>
						</h3>
						<small>За позавчера</small>
					</div>
					<div class="icon">
						<i class="fa fa-info" aria-hidden="true"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="dashboard-stat2 ">
				<div class="display">
					<div class="number">
						<h3 class="font-black-soft">
							<span data-counter="counterup" data-value="{{$pay_4dn}}">{{$pay_4dn}} РУБ</span>
						</h3>
						<small>3 дня назад</small>
					</div>
					<div class="icon">
						<i class="fa fa-info" aria-hidden="true"></i>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
			<div class="dashboard-stat2 ">
				<div class="display">
					<div class="number">
						<h3 class="font-black-soft">
							<span data-counter="counterup" data-value="{{$pay_5dn}}">{{$pay_5dn}} РУБ</span>
						</h3>
						<small>4 дня назад</small>
					</div>
					<div class="icon">
						<i class="fa fa-info" aria-hidden="true"></i>
					</div>
				</div>
			</div>
		</div>
</div>
<div class = "row">
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat2 ">
			<div class="display">
				<div class="number">
					<h3 class="font-green-soft">
						<span data-counter="counterup" data-value="{{$pay_last_week}}">{{$pay_last_week}} РУБ</span>
					</h3>
					<small>Прошлая неделя</small>
				</div>
				<div class="icon">
					<i class="fa fa-info" aria-hidden="true"></i>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat2 ">
			<div class="display">
				<div class="number">
					<h3 class="font-green-soft">
						<span data-counter="counterup" data-value="{{$pay_week_ago}}">{{$pay_week_ago}} РУБ</span>
					</h3>
					<small>2 недели назад</small>
				</div>
				<div class="icon">
					<i class="fa fa-info" aria-hidden="true"></i>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat2 ">
			<div class="display">
				<div class="number">
					<h3 class="font-green-soft">
						<span data-counter="counterup" data-value="{{$pay_week_ago1}}">{{$pay_week_ago1}} РУБ</span>
					</h3>
					<small>3 недели назад</small>
				</div>
				<div class="icon">
					<i class="fa fa-info" aria-hidden="true"></i>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat2 ">
			<div class="display">
				<div class="number">
					<h3 class="font-green-soft">
						<span data-counter="counterup" data-value="{{$pay_week_ago2}}">{{$pay_week_ago2}} РУБ</span>
					</h3>
					<small>4 недели назад</small>
				</div>
				<div class="icon">
					<i class="fa fa-info" aria-hidden="true"></i>
				</div>
			</div>
		</div>
	</div>
</div>

<div class='row'>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat2 ">
			<div class="display">
				<div class="number">
					<h3 class="font-red-soft">
						<span data-counter="counterup" data-value="{{$pay_last_month}}">{{$pay_last_month}} РУБ</span>
					</h3>
					<small>Прошлый месяц</small>
				</div>
				<div class="icon">
					<i class="fa fa-info" aria-hidden="true"></i>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat2 ">
			<div class="display">
				<div class="number">
					<h3 class="font-red-soft">
						<span data-counter="counterup" data-value="{{$pay_last_month1}}">{{$pay_last_month1}} РУБ</span>
					</h3>
					<small>2 месяца назад</small>
				</div>
				<div class="icon">
					<i class="fa fa-info" aria-hidden="true"></i>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat2 ">
			<div class="display">
				<div class="number">
					<h3 class="font-red-soft">
						<span data-counter="counterup" data-value="{{$pay_last_month2}}">{{$pay_last_month2}} РУБ</span>
					</h3>
					<small>3 месяца назад</small>
				</div>
				<div class="icon">
					<i class="fa fa-info" aria-hidden="true"></i>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat2 ">
			<div class="display">
				<div class="number">
					<h3 class="font-red-soft">
						<span data-counter="counterup" data-value="{{$pay_last_month3}}">{{$pay_last_month3}} РУБ</span>
					</h3>
					<small>4 месяца назад</small>
				</div>
				<div class="icon">
					<i class="fa fa-info" aria-hidden="true"></i>
				</div>
			</div>
		</div>
	</div>
</div>

<h1 class="page-title"> Статистика
	<small>новых пользователей</small>
</h1>
<div class="row">
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat2 ">
			<div class="display">
				<div class="number">
					<h3 class="font-purple-soft">
						<span data-counter="counterup" data-value="{{$reg_yesterday}}">{{$reg_yesterday}} ЧЕЛ</span>
					</h3>
					<small>Вчера</small>
				</div>
				<div class="icon">
					<i class="icon-user"></i>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat2 ">
			<div class="display">
				<div class="number">
					<h3 class="font-purple-soft">
						<span data-counter="counterup" data-value="{{$reg_3dn}}">{{$reg_3dn}} ЧЕЛ</span>
					</h3>
					<small>Позавчера</small>
				</div>
				<div class="icon">
					<i class="icon-user"></i>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat2 ">
			<div class="display">
				<div class="number">
					<h3 class="font-purple-soft">
						<span data-counter="counterup" data-value="{{$reg_4dn}}">{{$reg_4dn}} ЧЕЛ</span>
					</h3>
					<small>4 дня назад</small>
				</div>
				<div class="icon">
					<i class="icon-user"></i>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat2 ">
			<div class="display">
				<div class="number">
					<h3 class="font-purple-soft">
						<span data-counter="counterup" data-value="{{$reg_5dn}}">{{$reg_5dn}} ЧЕЛ</span>
					</h3>
					<small>5 дней назад</small>
				</div>
				<div class="icon">
					<i class="icon-user"></i>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat2 ">
			<div class="display">
				<div class="number">
					<h3 class="font-purple-soft">
						<span data-counter="counterup" data-value="{{$reg_this_week}}">{{$reg_this_week}} ЧЕЛ</span>
					</h3>
					<small>За эту неделю</small>
				</div>
				<div class="icon">
					<i class="icon-user"></i>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat2 ">
			<div class="display">
				<div class="number">
					<h3 class="font-purple-soft">
						<span data-counter="counterup" data-value="{{$reg_last_week}}">{{$reg_last_week}} ЧЕЛ</span>
					</h3>
					<small>За прошлую неделю</small>
				</div>
				<div class="icon">
					<i class="icon-user"></i>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat2 ">
			<div class="display">
				<div class="number">
					<h3 class="font-purple-soft">
						<span data-counter="counterup" data-value="{{$reg_week_ago}}">{{$reg_week_ago}} ЧЕЛ</span>
					</h3>
					<small>2 недели назад</small>
				</div>
				<div class="icon">
					<i class="icon-user"></i>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
		<div class="dashboard-stat2 ">
			<div class="display">
				<div class="number">
					<h3 class="font-purple-soft">
						<span data-counter="counterup" data-value="{{$reg_week_ago1}}">{{$reg_week_ago1}} ЧЕЛ</span>
					</h3>
					<small>3 недели назад</small>
				</div>
				<div class="icon">
					<i class="icon-user"></i>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
