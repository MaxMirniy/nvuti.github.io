<?php namespace App\Http\Controllers;

use App\User;
use App\Cards;
use App\Items;
use App\Withdraw;
use App\Promo;
use App\Support;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Settings;
use App\Reviews;

class AdminController extends Controller
{
	public function index()
    {
		$user_money = \DB::table('users')->where('money', '!=', 0)->sum('money');
		$user_today = \DB::table('users')->where('created_at', '>=', Carbon::today())->count();
		$opened_today = \DB::table('games')->where('created_at', '>=', Carbon::today())->count();
		$pay_today = \DB::table('payments')->where('updated_at', '>=', Carbon::today())->where('status', 1)->sum('amount');
		$pay_week = \DB::table('payments')->where('updated_at', '>=', Carbon::now()->subDays(7))->where('status', 1)->sum('amount');
		$pay_month = \DB::table('payments')->where('updated_at', '>=', Carbon::now()->subDays(30))->where('status', 1)->sum('amount');
		$pay_all = \DB::table('payments')->where('status', 1)->sum('amount');


		/*Подробная статистика*/
		$pay_yesterday = \DB::table('payments')->where('updated_at', '>=', Carbon::now()->subDays(1)->format('Y-m-d 00:00:01'))->where('updated_at', '<=', Carbon::now()->subDays(1)->format('Y-m-d 23:59:59'))->where('status', 1)->sum('amount');
		$pay_3dn =\DB::table('payments')->where('updated_at', '>=', Carbon::now()->subDays(2)->format('Y-m-d 00:00:01'))->where('updated_at', '<=', Carbon::now()->subDays(2)->format('Y-m-d 23:59:59'))->where('status', 1)->sum('amount');
		$pay_4dn = \DB::table('payments')->where('updated_at', '>=', Carbon::now()->subDays(3)->format('Y-m-d 00:00:01'))->where('updated_at', '<=', Carbon::now()->subDays(3)->format('Y-m-d 23:59:59'))->where('status', 1)->sum('amount');
		$pay_5dn = \DB::table('payments')->where('updated_at', '>=', Carbon::now()->subDays(4)->format('Y-m-d 00:00:01'))->where('updated_at', '<=', Carbon::now()->subDays(4)->format('Y-m-d 23:59:59'))->where('status', 1)->sum('amount');



		$pay_last_week = \DB::table('payments')->where('updated_at', '<=', Carbon::now()->subWeeks(1))->where('updated_at', '>=', Carbon::now()->subWeeks(2))->where('status', 1)->sum('amount');
		$pay_week_ago = \DB::table('payments')->where('updated_at', '<=', Carbon::now()->subWeeks(2))->where('updated_at', '>=', Carbon::now()->subWeeks(3))->where('status', 1)->sum('amount');
		$pay_week_ago1 = \DB::table('payments')->where('updated_at', '<=', Carbon::now()->subWeeks(3))->where('updated_at', '>=', Carbon::now()->subWeeks(4))->where('status', 1)->sum('amount');
		$pay_week_ago2 = \DB::table('payments')->where('updated_at', '<=', Carbon::now()->subWeeks(4))->where('updated_at', '>=', Carbon::now()->subWeeks(5))->where('status', 1)->sum('amount');




		$pay_last_month = \DB::table('payments')->where('updated_at', '<=', Carbon::now()->subMonths(1))->where('updated_at', '>=', Carbon::now()->subMonths(2))->where('status', 1)->sum('amount');
		$pay_last_month1 = \DB::table('payments')->where('updated_at', '<=', Carbon::now()->subMonths(2))->where('updated_at', '>=', Carbon::now()->subMonths(3))->where('status', 1)->sum('amount');
		$pay_last_month2 = \DB::table('payments')->where('updated_at', '<=', Carbon::now()->subMonths(3))->where('updated_at', '>=', Carbon::now()->subMonths(4))->where('status', 1)->sum('amount');
		$pay_last_month3 = \DB::table('payments')->where('updated_at', '<=', Carbon::now()->subMonths(4))->where('updated_at', '>=', Carbon::now()->subMonths(5))->where('status', 1)->sum('amount');

		/*Подробная статистика*/


		/*Статистика пользователей*/

		$reg_yesterday = \DB::table('users')->where('created_at', '>=', Carbon::now()->subDays(1)->format('Y-m-d 00:00:01'))->where('created_at', '<=', Carbon::now()->subDays(1)->format('Y-m-d 23:59:59'))->count();
		$reg_3dn =\DB::table('users')->where('created_at', '>=', Carbon::now()->subDays(2)->format('Y-m-d 00:00:01'))->where('created_at', '<=', Carbon::now()->subDays(2)->format('Y-m-d 23:59:59'))->count();
		$reg_4dn = \DB::table('users')->where('created_at', '>=', Carbon::now()->subDays(3)->format('Y-m-d 00:00:01'))->where('created_at', '<=', Carbon::now()->subDays(3)->format('Y-m-d 23:59:59'))->count();
		$reg_5dn = \DB::table('users')->where('created_at', '>=', Carbon::now()->subDays(4)->format('Y-m-d 00:00:01'))->where('created_at', '<=', Carbon::now()->subDays(4)->format('Y-m-d 23:59:59'))->count();



		$reg_this_week = \DB::table('users')->where('created_at', '<=', Carbon::now()->subWeeks(0))->where('created_at', '>=', Carbon::now()->subWeeks(1))->count();
		$reg_last_week = \DB::table('users')->where('created_at', '<=', Carbon::now()->subWeeks(1))->where('created_at', '>=', Carbon::now()->subWeeks(2))->count();
		$reg_week_ago = \DB::table('users')->where('created_at', '<=', Carbon::now()->subWeeks(2))->where('created_at', '>=', Carbon::now()->subWeeks(3))->count();
		$reg_week_ago1 = \DB::table('users')->where('created_at', '<=', Carbon::now()->subWeeks(3))->where('created_at', '>=', Carbon::now()->subWeeks(4))->count();
		/*Статистика пользователей*/


		//->where('created_at', '<', Carbon::now()->subDays(14))->where('type', 0)->where('status', 1)
		//dd(Carbon::now()->subDays(2)->format('Y-m-d 00:00:01'));
		//dd(Carbon::now()->subDays(2)->format('Y-m-d 23:59:59'));


		if(!$user_money) $user_money = 0;
		if(!$user_today) $user_today = 0;
		if(!$opened_today) $opened_today = 0;
		if(!$pay_today) $pay_today = 0;
		if(!$pay_week) $pay_week = 0;
		if(!$pay_month) $pay_month = 0;
		if(!$pay_all) $pay_all = 0;
		if(!$pay_yesterday) $pay_yesterday =0;
		if(!$pay_3dn) $pay_3dn =0;
		if(!$pay_4dn) $pay_4dn =0;
		if(!$pay_5dn) $pay_5dn =0;
		if(!$pay_last_week) $pay_last_week =0;
		if(!$pay_week_ago) $pay_week_ago =0;
		if(!$pay_week_ago1) $pay_week_ago1 =0;
		if(!$pay_week_ago2) $pay_week_ago2 =0;
		if(!$pay_last_month) $pay_last_month =0;
		if(!$pay_last_month1) $pay_last_month1 =0;
		if(!$pay_last_month2) $pay_last_month2 =0;
		if(!$pay_last_month3) $pay_last_month3 =0;
		if(!$reg_yesterday) $reg_yesterday =0;
		if(!$reg_3dn) $reg_3dn =0;
		if(!$reg_4dn) $reg_4dn =0;
		if(!$reg_5dn) $reg_5dn =0;

		if(!$reg_this_week) $reg_this_week =0;
		if(!$reg_last_week) $reg_last_week =0;
		if(!$reg_week_ago) $reg_week_ago =0;
		if(!$reg_week_ago1) $reg_week_ago1 =0;

		return view('admin.index', compact('user_money', 'user_today', 'opened_today', 'pay_today', 'pay_week', 'pay_month', 'pay_all', 'pay_yesterday', 'pay_3dn', 'pay_4dn', 'pay_5dn', 'pay_last_week', 'pay_week_ago', 'pay_week_ago1', 'pay_week_ago2', 'pay_last_month', 'pay_last_month1', 'pay_last_month2', 'pay_last_month3', 'reg_yesterday', 'reg_3dn', 'reg_4dn', 'reg_5dn', 'reg_last_week', 'reg_week_ago', 'reg_week_ago1', 'reg_this_week'));
    }

	public function users()
    {
		$users = User::where('is_bot', 0)->get();
		foreach($users as $user)
		{
			$user->payed = \DB::table('payments')->where('user_id', $user->id)->where('status', 1)->sum('amount');
			$user->with =  \DB::table('withdraws')->where('user_id', $user->id)->where('status', 1)->sum('amount');
			$user->with0 = \DB::table('withdraws')->where('user_id', $user->id)->where('status', 0)->sum('amount');
			if($user->payed == null) $user->payed = 0;
			if($user->with == null) $user->with = 0;
			if($user->with0 == null) $user->with0 = 0;
		}
		return view('admin.pages.users', compact('users'));
    }

	public function edit_user($id)
    {
		$user = User::findOrFail($id);
		$user->payed = \DB::table('payments')->where('user_id', $user->id)->where('status', 1)->sum('amount');
		$user->with =  \DB::table('withdraws')->where('user_id', $user->id)->where('status', 1)->sum('amount');
		$user->with0 = \DB::table('withdraws')->where('user_id', $user->id)->where('status', 0)->sum('amount');
		if($user->payed == null) $user->payed = 0;
		if($user->with == null) $user->with = 0;
		if($user->with0 == null) $user->with0 = 0;
		return view('admin.includes.modal_users', ['user' => $user]);
		#['user' => $user, 'pay' => $pay, 'with' => $with, 'with0' => $with0]
    }
	public function user_save(Request $r)
	{
        User::where('id', $r->get('id'))->update([
			'name' => $r->get('name'),
			'email' => $r->get('email'),
	    'money' => $r->get('money'),
	    'is_admin' => $r->get('is_admin'),
			'is_ban' => $r->get('is_ban'),
			'is_block' => $r->get('is_block'),
	    'password' => $r->get('password'),
			'yt_user' => $r->get('yt_user')
        ]);

		$r->session()->flash('alert-success', 'Настройки пользователя сохранены');
        return redirect('/admin/users');
  }

	public function createpromo(Request $r)
	{
		$data = date("m.d.y H:i:s");
    \DB::table('promocode')->insert([
			'promo' => $r->get('promo'),
			'money' => $r->get('money'),
	    'active_limit' => $r->get('count_active'),
			'data' => $data
    ]);

		$r->session()->flash('alert-success', 'Промокод создан');
        return redirect('/admin/promocodes');
  }

	public function payments()
	{
		$a = \DB::table('payments')->orderBy('id', 'desc')->where('status', 1)->take(20)->get();
		foreach ($a as $b) {
			$u = User::find($b->user_id);
			$b->name = $u->name;
			$b->name_id = $u->id;
		}
		return view('admin.pages.payments', compact('a'));
	}

	public function promocodes()
	{
		return view('admin.pages.promocodes', compact('a'));
	}

	public function withdraw()
    {
		$withdrows = \DB::table('withdraws')->get();
		foreach($withdrows as $w)
		{
			$user = \DB::table('users')->where('id', $w->user_id)->first();
			$user->payed = \DB::table('payments')->where('user_id', $user->id)->where('status', 1)->sum('amount');
			$user->with =  \DB::table('withdraws')->where('user_id', $user->id)->where('status', 1)->sum('amount');
			$user->with0 = \DB::table('withdraws')->where('user_id', $user->id)->where('status', 0)->sum('amount');
			if($user->payed == null) $user->payed = 0;
			if($user->with == null) $user->with = 0;
			if($user->with0 == null) $user->with0 = 0;

			$w->user = $user;
			$date = $w->created_at;
			Carbon::setlocale('ru');
			$w->dfh = Carbon::createFromFormat('Y-m-d H:i:s', $date)->diffForHumans();
			//$w->dfh = $date->diffForHumans();
		}

		//dd($withdrows);
		return view('admin.pages.withdraw', compact('withdrows'));
    }

	public function edit_withdraw($id)
	{

		$user = \DB::table('withdraws')->where('id', $id)->first();
		$user->user = User::where('id', $user->user_id)->first();
		return view('admin.includes.modal_withdrows', compact('user'));
	}
	public function withdraw_save(Request $r)
	{
		if($r->get('status') == 0 || $r->get('status') == 1)
		{
			\DB::table('withdraws')->where('id', $r->id)->update([
				'status' => $r->get('status')
			]);
		}
		elseif($r->get('status') == 2)
		{
			$withdraw = \DB::table('withdraws')->where('id', $r->id)->first();
			$user = User::where('id', $withdraw->user_id)->first();
			$user->money = $user->money + $withdraw->amount;
			$user->save();
			\DB::table('withdraws')->where('id', $r->id)->update(["status" => 2]);
		}
		$r->session()->flash('alert-success', 'Статус выплаты обновлен!');
		return redirect()->back();
	}

	public function settings()
	{
		$settings = Settings::where('id', 1)->first();
		return view('admin.pages.settings', compact('settings'));
	}
	public function settings_save(Request $r)
	{
		Settings::where('id', 1)->update([
			'vk_id' => $r->get('vk_id'),
			'vk_secret' => $r->get('vk_secret'),
			'vk_redirect_uri' => $r->get('vk_redirect_uri'),
			'fk_id' => $r->get('fk_id'),
			'fk_secret1' => $r->get('fk_secret1'),
			'fk_secret2' => $r->get('fk_secret2'),
			'ref_percent' => $r->get('tttk'),
			'adm_email' => $r->get('adm_email'),
      'vk_group' => $r->get('vk_group'),
			'min_dep' => $r->get('min_dep'),
			'min_width' => $r->get('min_width'),
			'online' => $r->get('online'),
			'bot_deop1' => $r->get('bot_deop1'),
			'bot_deop2' => $r->get('bot_deop2'),
			'sitename' => $r->get('sitename'),
			'sitewallet' => $r->get('sitewallet'),
			'sitefooter' => $r->get('sitefooter'),
			'sitebgc' => $r->get('sitebgc'),
			'sitefav' => $r->get('sitefav'),
			'recaptcha' => $r->get('recaptcha'),
			'vk_g_id' => $r->get('vk_g_id'),
			'vk_g_token' => $r->get('vk_g_token'),
			'money_bonus' => $r->get('money_bonus')
        ]);

		$r->session()->flash('alert-success', 'Настройки обновлены');
        return redirect()->back();
	}

	public function bots()
	{
		$users = \DB::table('users')->where('is_bot', 1)->get();
		return view('admin.pages.bots', compact('users'));
	}

	public function botadd(Request $r)
	{
		if(!isset($r->bot_name) || $r->bot_name == '')
		{
			$r->session()->flash('alert-danger', 'Укажите имя для создания ботов!');
			return redirect()->back();
		}
		else
		{
			User::create([
				'name' => $r->bot_name,
				'is_bot' => 1
			]);
			$r->session()->flash('alert-success', 'Бот создан!');
			return redirect()->back();
		}
	}

	public function bot_delete($id, Request $r)
	{
		\DB::table('users')->where('id', $id)->delete();
		$r->session()->flash('alert-success', 'Бот удален!');
		return redirect()->back();
	}

	public function user_delete($id, Request $r)
	{
		\DB::table('users')->where('id', $id)->delete();
		$r->session()->flash('alert-success', 'Пользователь удален!');
		return redirect()->back();
	}

	public function promo_delete($id, Request $r)
	{
		\DB::table('promocode')->where('id', $id)->delete();
		$r->session()->flash('alert-success', 'Промокод удален!');
		return redirect()->back();
	}

	public function generate()
    {
        $length = 15;
        $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $string;
    }
}
