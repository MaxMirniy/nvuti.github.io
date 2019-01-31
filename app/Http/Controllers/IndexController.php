<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Game;
use App\Settings;

class IndexController extends Controller
{
	/*const SECRET_GOOGLE_1 = '6LerHlQUAAAAAPEL36ycL7UGY0bsyNFxRdiSCIhD';
	const SECRET_GOOGLE_2 = '6Let3VQUAAAAAJ7QOjT29wd-g7BBPtYoa02snuqr';
	*/

  public function index(Request $r)
  {
	$settings = \DB::table('settings')->where('id', 1)->first();
	if(isset($r->i))
	{
		$r->session()->put('ref', $r->i);
	}
	if(Auth::check())
	{
		$payments = \DB::table('payments')->where('user_id', Auth::user()->id)->limit(5)->orderBy('id', 'desc')->where('status', 1)->get();
		if($payments == true)
		{
			return view('layout', compact('payments', 'settings'));
		}
	}
      return view('layout', compact('settings'));
  }

  public function check(Request $r)
  {
    return view('check');
  }

	public function sukaoplati190(Request $request)
	{
		$settings = \DB::table('settings')->where('id', 1)->first();
		$sign = md5($settings->fk_id.':'.$request->AMOUNT.':'.$settings->fk_secret2.':'.$request->MERCHANT_ORDER_ID);
		/*if($sign != $request->SIGN)
		{
			return "ERROR [SIGN IS NOT VALID]";
		}*/
		$payment = \DB::table('payments')->where('id', $request->MERCHANT_ORDER_ID)->first();
		if(count($payment) == 0)
		{
			return "ERROR [PAYMENT NOT FOUND]";
		}
		else
		{
			if($payment->status != 0)
			{
				return "ERROR [SERVER ERROR #0]";
			}
			else
			{
				if($payment->amount != $request->AMOUNT)
				{
					return "ERROR [SERVER ERROR #1]";
				}
				else
				{
					$user = User::where('id', $payment->user_id)->first();
					$user->money = $user->money + $payment->amount;

					if($user->ref_use != 0)
					{
						$te = User::where('id', $user->ref_use)->first();
						if(count($te) == null || count($te) == 0)
						{
							$user->save();
						}
						else
						{
							$bon = ($settings->ref_percent/100)*$payment->amount;
							$te->money =   $te->money + $bon;
							$te->save();
							$user->ref_profit = $user->ref_profit + ($settings->ref_percent/100)*$payment->amount;
							$user->save();
						}
					}
					else
					{
						$user->save();
					}

					\DB::table('payments')
					->where('id', $payment->id)
					->update(['status' => 1]);
					return 'success';
				}
			}
		}
	}
	public function vklogin(Request $r)
    {
		$settings = \DB::table('settings')->where('id', 1)->first();
        $client_id = $settings->vk_id;
        $client_secret = $settings->vk_secret;
        $redirect_uri = $settings->vk_redirect_uri;
        if (!is_null($r->code))
		{
			$obj = json_decode($this->curl('https://oauth.vk.com/access_token?client_id=' . $client_id . '&client_secret=' . $client_secret . '&redirect_uri=http://' . $redirect_uri . '/login&code=' . $r->code));
			if (isset($obj->access_token))
			{
				$info = json_decode($this->curl('https://api.vk.com/method/users.get?user_ids&fields=photo_200&access_token=' . $obj->access_token . '&v=V'), true);
				$user = User::where('login2', $info['response'][0]['uid'])->first();
				if($user == NULL)
				{
					if(array_key_exists('photo_200', $info['response'][0]))
					{
						$photo = $info['response'][0]['photo_200'];
					}else
					{
						$photo = 'http://vk.com/images/camera_200.png';
					}
					if ($r->session()->has('ref')) {
						$has = DB::table('users')->where('ref_code', session('ref'))->first();
						if(!empty($has))
						{
							$ref_use = session('ref');
							$money = 0;
						}
						else
						{
							$ref_use = 0;
							$money = 0;
						}
					}
					else
					{
						$ref_use = 0;
						$money = 0;
					}

					$user = User::create([
						'name' => $info['response'][0]['first_name'] . ' ' . $info['response'][0]['last_name'],
						'login' => 'id'.$info['response'][0]['uid'],
						'money' => $money+$settings->money_bonus,
						'login2' => $info['response'][0]['uid'],
						'bonus' => 1,
            'bonus_url' => $info['response'][0]['uid']
					]);

				}
				else
				{
          if(array_key_exists('photo_200', $info['response'][0]))
					{
						$photo = $info['response'][0]['photo_200'];
					}
					else
					{
						$photo = 'http://vk.com/images/camera_200.png';
					}
					$user->name = $info['response'][0]['first_name'] . ' ' . $info['response'][0]['last_name'];
					$user->login = 'id'.$info['response'][0]['uid'];
					$user->login2 = $info['response'][0]['uid'];
					$user->save();
				}
        $users = User::where('login2', $info['response'][0]['uid'])->first();
        if($users->is_ban == 1)
        {
          return redirect('/');
        }
				Auth::login($user, true);
				return redirect('/');
			}
		}
		else
		{
      return redirect('https://oauth.vk.com/authorize?client_id=' . $client_id . '&display=page&redirect_uri=http://' . $redirect_uri . '/login&scope=friends,photos,status,offline,&response_type=code&v=5.53');
		}
	}

	public function action(Request $r)
	{
    if($r->type == 'check_code')
		{
      $cd = User::where('name', Auth::user()->name)->first();
      if($r->code == $cd->code_check)
      {
        User::where('name', Auth::user()->name)->update(['code_check' => 'success']);
        return json_encode(["success" => true]);
      }else {
        return json_encode(["error"=> ["text" =>"Код введен неверно"]]);
      }
    }
    if($r->type == 'resetPass')
		{
      $user = \DB::table('users')->where('name', $r->login)->orWhere('email', $r->login)->first();
      if($user)
      {
        return json_encode(["success" => ["text" => "Успешно"]]);
      }
    }
    if($r->type == 'PromoActive')
		{
      if(empty($r->promo))
      {
        return json_encode(["error"=> ["text" =>"Введите Промокод"]]);
      }
      $promo= \DB::table('promocode')->where('promo', $r->promo)->first();

      if($promo)
      {
        $active = $promo->active;
        $activelimit = $promo->active_limit;
        $idactive = $promo->id_active;
        $summa = $promo->money;

        $user = User::where('name', Auth::user()->name)->first();

        if($user)
        {
          $user_id = $user->id;
          $balance = $user->money;
        }

        if($active >= $activelimit)
        {
          $error = 3;
          return json_encode(["error"=> ["text" =>"Количество активаций исчерпано"]]);
        }

        if (preg_match("/$user_id /",$idactive))
        {
          $error = 3;
          return json_encode(["error"=> ["text" =>"Вы уже активировали данный промокод"]]);
        }
      }
      else
      {
        $error = 2;
        return json_encode(["error"=> ["text" =>"Промокод не существует"]]);
      }
      $error = 0;
      if($error == 0)
      {
        $balancenew = $balance + $summa;
        $activeupd = $active + 1;
        $idupd = "$user_id $idactive";
        \DB::table('promocode')->where('promo', $r->promo)->update(['id_active' => $idupd, 'active' => $activeupd]);
        User::where('name', Auth::user()->name)->update(['money' => $balancenew]);
        return json_encode(["success" => ["balance" => $balance, "new_balance" => $balancenew, "suma" => $summa]]);
      }
    }
    if($r->type == 'getBonus')
		{
      $users = User::where('name', Auth::user()->name)->first();
      if($users)
      {
        $vkcount = $users->bonus_url;
      }
      $userss = User::where('name', Auth::user()->name)->first();
      if($userss)
      {
        $vkcounts = $userss->bonus;
        $bala = $userss->money;
      }
      if($vkcount)
    	{
        User::where('name', Auth::user()->name)->update(['bonus' => 1]);
        return json_encode(["error"=> ["text" =>"Вы уже получали бонус"]]);
      }else{
        if(!$vkcount)
        {
          if($vkcounts == 0)
          {
            $user = explode( 'vk.com', $r->vk )[1];
            $http = "https://";
            $vks = str_replace($user, '', $r->vk);
            $vks = str_replace($http, '', $vks);
            if($vks == "vk.com" || $vks == "m.vk.com")
            {
              //good
          		$domainvk = explode( 'https://vk.com/', $r->vk )[1];
              if (!is_numeric($domainvk))
              {
              	$domainvk = explode( 'com/', $r->vk )[1];
              }
              $settings = \DB::table('settings')->where('id', 1)->first();
              $vk1 = json_decode(file_get_contents("https://api.vk.com/method/users.get?user_ids={$domainvk}&access_token=".$settings->vk_g_token."&v=5.74"));
              $vk1 = $vk1->response[0]->id;
              $resp = file_get_contents("https://api.vk.com/method/groups.isMember?group_id=".$settings->vk_g_id."&user_id={$vk1}&access_token=".$settings->vk_g_token."&v=5.74");
              $data = json_decode($resp, true);
              if($data['response']=='1')
              {
                $bonus = $settings->money_bonus;
                $balances = $bala + $bonus;
                User::where('name', Auth::user()->name)->update(['bonus_url'=>$domainvk, 'bonus' => 1, 'money'=>$balances]);
                return json_encode(["success"=> ["text" =>"Бонус получен", 'money'=>$bala, 'new_money'=>$balances]]);
              }
            }
          }
        }
      }
    }
    if($r->type == 'hideBonus')
		{
      User::where('name', Auth::user()->name)->update(['bonus' => 1]);
      return json_encode(["success"=> true]);
    }
		if($r->type == 'login')
		{
			$user = User::where('name', $r->login)->first();
			if($user == false)
			{
				return json_encode(["error"=>"Пользователь не найден"]);
			}

			if($r->pass != $user->password)
			{
				return json_encode(["error"=>"Неверный пароль"]);
			}

      if($user->is_ban == 1)
			{
				return json_encode(["error"=>"Аккаунт заблокирован за нарушение правил"]);
			}

			if ($user->salt1 == '' || $user->salt2 == '' || $user->number == 0)
			{
				$hash = json_decode($this->hash_generate());
				$user->salt1 = $hash->salt_1;
				$user->number = $hash->number;
				$user->salt2 = $hash->salt_2;
				$user->hash = hash('sha512', $hash->salt_1.$hash->number.$hash->salt_2);
				$user->save();
			}

			Auth::login($user, true);
			return json_encode(["success" => true]);
		}

		elseif($r->type == "register")
		{
			if(!Auth::guest())
			{
				return json_encode(['error'=>"Вы уже авторизованы"]);
			}
			$user = User::where('email', $r->email)->first();
			if($user == true)
			{
				return json_encode(["error"=>"Email уже используется"]);
			}
			$user2 = User::where('name', $r->login)->first();
			if($user2 == true)
			{
				return json_encode(["error"=>"Логин уже используется"]);
			}
			else
			{
				$name = $r->login;
				$pass = $r->pass;
				$email = $r->email;
				$hash = json_decode($this->hash_generate());

				if ($r->session()->has('ref'))
				{
					$has = \DB::table('users')->where('id', session('ref'))->first();
					if(!empty($has))
					{
						$ref_use = session('ref');
					}
					else
					{
						$ref_use = 0;
					}
				}
				else
				{
					$ref_use = 0;
				}

				$user = User::create([
					'name' => $name,
					'email' => $email,
					'password' => $pass,
					'ref_use' => $ref_use,
					'money' => 0,
					'salt1' => $hash->salt_1,
					'number' => $hash->number,
					'salt2' => $hash->salt_2,
					'hash' => hash('sha512', $hash->salt_1.$hash->number.$hash->salt_2)
				]);


//         $settings = \DB::table('settings')->where('id', 1)->first();
//
//         $char = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","A","B","C","D","E","F","G","H","I","J","K","L","M","N","P","Q","R","S","T","U","V","W","X","Y","Z","1","2","3","4","5","6","7","8","9","0");
//       	for($i=0;$i<=12;$i++)
//       	{
//       			$q = rand(0,60);
//       			$code = $code.$char[$q];
//       	}
//
//         $to = "{$email}";
//         $subject = "Код подтверждения - $settings->sitename";
//         $from = 'noreply@'.$_SERVER['HTTP_HOST'].'';
//         $message = <<<HERE
//         <table class="nl-container_mailru_css_attribute_postfix" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;vertical-align: top;min-width: 320px;margin: 0 auto;background-color: #f5f7fa;width: 100%" cellpadding="0" cellspacing="0">
//         			<tbody>
//         					<tr style="vertical-align: top">
//         							<td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top;padding: 0">
//
//
//
//         				<div style="background-color:transparent;margin-top:45px;">
//         											<div style="margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #FFFFFF;padding-top:34px;border-radius: 11px;" class="block-grid_mailru_css_attribute_postfix">
//         													<div style="border-collapse: collapse;display: table;width: 100%;background-color:#FFFFFF;">
//
//
//
//
//         															<div class="col_mailru_css_attribute_postfix num12_mailru_css_attribute_postfix" style="min-width: 320px;max-width: 600px;display: table-cell;vertical-align: top;">
//         																	<div style="background-color: transparent;width: 100% !important;">
//
//
//         																			<div style="border-top: 0px solid transparent;border-left: 0px solid transparent;border-bottom: 0px solid transparent;border-right: 0px solid transparent;padding-top:5px;padding-bottom:0px;padding-right: 0px;padding-left: 0px;">
//
//
//         																					<div align="center" class="img-container_mailru_css_attribute_postfix center_mailru_css_attribute_postfix" style="padding-right: 0px;padding-left: 0px;">
//
//         											<span class="center_mailru_css_attribute_postfix" align="center" border="0" style="outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;clear: both;display: block !important;border: 0;height: auto;float: none;font-family: 'Open Sans', sans-serif;font-weight:600!important;font-size:37px;color: #404E67;">$settings->sitename</span>
//
//         																					</div>
//
//
//         																			</div>
//
//
//         																	</div>
//         															</div>
//
//
//         													</div>
//         											</div>
//         									</div>
//         									<div style="background-color:transparent;margin-bottom:45px;">
//         											<div style="margin: 0 auto;min-width: 320px;max-width: 600px;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #FFFFFF;padding-bottom:34px;border-radius: 11px;" class="block-grid_mailru_css_attribute_postfix">
//         													<div style="border-collapse: collapse;display: table;width: 100%;background-color:#FFFFFF;">
//
//
//
//
//         															<div class="col_mailru_css_attribute_postfix num12_mailru_css_attribute_postfix" style="min-width: 320px;max-width: 600px;display: table-cell;vertical-align: top;">
//         																	<div style="background-color: transparent;width: 100% !important;">
//
//
//         																			<div style="border-top: 0px solid transparent;border-left: 0px solid transparent;border-bottom: 0px solid transparent;border-right: 0px solid transparent;padding-top:0px;padding-bottom:5px;padding-right: 0px;padding-left: 0px;">
//
//
//
//         																					<div style="font-family:'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;line-height:150%;color:#555555;padding-right: 10px;padding-left: 10px;padding-top: 10px;padding-bottom: 0px;">
//         																							<div style="font-size:12px;line-height:18px;font-family:Montserrat, 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;color:#555555;text-align:left;">
//         																									<p style="margin: 0;font-size: 14px;line-height: 21px;text-align: center"><span style="font-size: 16px;line-height: 24px;">Вам нужно подтвердить свою почту</span>
//         																											<br><span style="font-size: 18px;line-height: 27px;"></span></p>
//         																							</div>
//         																					</div>
//
//         																					<div align="center" class="button-container_mailru_css_attribute_postfix center_mailru_css_attribute_postfix" style="padding-right: 10px;padding-left: 10px;padding-top:15px;padding-bottom:10px;">
//
//         											<span style="line-height: 24px;font-size: 16px;" data-mce-style="line-height: 21px;">Ваш код: $code</span></span>
//         																									</span>
//         																							</a>
//
//
//         																					</div>
//
//
//         																			</div>
//
//
//         																	</div>
//         															</div>
//
//
//         													</div>
//         											</div>
//         									</div>
//
//
//
//         							</td>
//         					</tr>
//         			</tbody>
//         	</table>
// HERE;
//         $headers = "Content-type: text/html; charset=utf-8\r\nFrom: $from\r\n"."Reply-To: $from\r\n"."X-Mailer: PHP/".phpversion();
//         $m = mail ($to, $subject, $message, $headers, "-f$from");

				Auth::login($user, true);

				return json_encode(["success" => true]);
			}
		}

		elseif($r->type == "deposit")
		{
			$amount = $r->size;
			$type = $r->system;
			if((int)$amount < 1){
				$amount = 99;
			}
			$int_id =  \DB::table('payments')->insertGetId([
				'amount' => (int)$amount,
				'user_id' => Auth::user()->id,
				'status' => 0
			]);
			$orderID = $int_id;

			$settings = \DB::table('settings')->where('id', 1)->first();

			$sign = md5($settings->fk_id.':'.$amount.':'.$settings->fk_secret1.':'.$orderID);

			if($type == 1)
			{
				$url = 'http://www.free-kassa.ru/merchant/cash.php?m='.$settings->fk_id.'&oa='.$amount.'&o='.$orderID.'&s='.$sign.'&lang=ru';
			}
			elseif($type == 2)
			{
				$url = 'http://www.free-kassa.ru/merchant/cash.php?m='.$settings->fk_id.'&oa='.$amount.'&o='.$orderID.'&s='.$sign.'&lang=ru&i=114';
			}
			elseif($type == 3)
			{
				$url = 'http://www.free-kassa.ru/merchant/cash.php?m='.$settings->fk_id.'&oa='.$amount.'&o='.$orderID.'&s='.$sign.'&lang=ru&i=155';
			}

			return json_encode(["success" => ["location" => $url]]);
		}

		elseif($r->type == "withdraw")
		{
			if(Auth::guest())
			{
				return json_encode(['error' => ["text" => 'Необходимо авторизоваться']]);
			}
      elseif(Auth::user()->is_ban == 1)
			{
				return json_encode(['ban' => ["text" => 'Обновите страницу']]);
			}
      elseif(Auth::user()->is_block == 1)
			{
				return json_encode(['error' => ["text" => 'Вывод отключен для вашего аккаунта']]);
			}
			if(Auth::user()->money < $r->size)
			{
				return json_encode(['error' => ["text" => 'Недостаточно средств']]);
			}

			$active = \DB::table('withdraws')->where('user_id', Auth::user()->id)->where('status', 0)->first();
			if($active == true)
			{
				return json_encode(['error' => ["text" => 'Дождитесь предыдущего вывода!']]);
			}

			$user = Auth::user();
			$old_balance = $user->money;
			$user->money = $user->money - $r->size;
			$user->save();
			date_default_timezone_set('UTC');
			$new_balance = Auth::user()->money;

			if($r->system == 1) { $img = '/files/ya.png'; } // ЯНДЕКС
			elseif($r->system == 2) { $img = '/files/payeer.png'; }
			elseif($r->system == 3) { $img = '/files/wm.png'; }
			elseif($r->system == 4) { $img = '/files/qiwi.png'; }
			elseif($r->system == 5) { $img = '/files/beeline.png'; }
			elseif($r->system == 6) { $img = '/files/megafon.png'; }
			elseif($r->system == 7) { $img = '/files/mts.png'; }
			elseif($r->system == 8) { $img = '/files/tele.png'; }
			elseif($r->system == 9) { $img = '/files/visa.png'; }
			elseif($r->system == 10) { $img = '/files/mc.png'; }

			$int_id =  \DB::table('withdraws')->insertGetId([
				'user_id' => Auth::user()->id,
				'system' => $r->system,
				'amount' => (int)$r->size,
				'wallet' => $r->wallet,
				'status' => 0,
				'created_at' => date("Y-m-d H:i:s")
			]);
      $settings = \DB::table('settings')->where('id', 1)->first();


			return json_encode(['success' => ["add_bd" => '<tr style="cursor:default" id="'.$int_id.'_his"><td><i class="ft-x" style="color:red;cursor:pointer;margin-left: -18px;" onclick="removeWithdraw('.$int_id.')"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Отменить вывод"></i>&nbsp;'.date("Y-m-d H:i:s").'</td><td><img src="'.$img.'" /> '.$r->wallet.'</td><td>'.$r->size.' '.$settings->sitewallet.'</td><td><div class="tag tag-warning">Ожидание</div></td></tr>', "balance" => $old_balance, "new_balance" => $new_balance]]);
		}

		elseif($r->type == "removeWithdraw")
		{
			if(isset($r->id))
			{
        if(Auth::user()->is_ban == 1)
  			{
  				return json_encode(['ban' => ["text" => 'Обновите страницу']]);
  			}
				if(Auth::check())
				{
					$withdraw = \DB::table('withdraws')->where('id', $r->id)->first();
					if($withdraw == true)
					{
						if($withdraw->status == 0)
            {
              if(Auth::user()->id == $withdraw->user_id)
  						{
  							$user = Auth::user();
  							$balance = $user->money;
  							$user->money = $user->money + $withdraw->amount;
  							$new_balance = $user->money;
  							$user->save();
  							\DB::table('withdraws')->where('id', $r->id)->delete();
  							return json_encode(["success" => ["balance" => $balance, "new_balance" => $new_balance]]);
  						}
  						else
  						{
  							return json_encode(["error" => ["text" => "Неверный пользователь"]]);
  						}
            }
            // if($withdraw->status != 0 or !$withdraw->id) {
            //   $settings = \DB::table('settings')->where('id', 1)->first();
            //   $widr = \DB::table('withdraws')->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
            //   foreach($widr as $row) {
            //     if($row->status == 0)
        		// 		{
        		// 			$tag = "warning";
        		// 			$s = '<i class="ft-x" style="color:red;cursor:pointer;margin-left: -18px;" onclick="removeWithdraw('.$row->id.')"></i>';
            //       $i = 'id="'.$row->id.'_his"';
            //       $stat = 'Ожидает';
        		// 		}
        		// 		if($row->status == 1)
        		// 		{
        		// 			$tag = "success";
        		// 			$s = "";
            //       $i = '';
            //       $stat = 'Выплачено';
        		// 		}
        		// 		if($row->status == 2)
        		// 		{
        		// 			$tag = "danger";
        		// 			$s = "";
            //       $i = '';
            //       $stat = 'Отказано';
        		// 		}
            //
            //     if($row->system == 1) { $img = '/files/ya.png'; } // ЯНДЕКС
          	// 		elseif($row->system == 2) { $img = '/files/payeer.png'; }
          	// 		elseif($r->system == 3) { $img = '/files/wm.png'; }
          	// 		elseif($row->system == 4) { $img = '/files/qiwi.png'; }
          	// 		elseif($row->system == 5) { $img = '/files/beeline.png'; }
          	// 		elseif($row->system == 6) { $img = '/files/megafon.png'; }
          	// 		elseif($row->system == 7) { $img = '/files/mts.png'; }
          	// 		elseif($row->system == 8) { $img = '/files/tele.png'; }
          	// 		elseif($row->system == 9) { $img = '/files/visa.png'; }
          	// 		elseif($row->system == 10) { $img = '/files/mc.png'; }
            //
            //     if($row->id == 0){
            //       $payouts = '<tr><td id="emptyHistory" colspan="4" class="text-xs-center">История пуста</td></tr>';
            //     }else{
            //       $payouts = '<tr style="cursor:default" '.$i.'><td>'.$s.''.$row->created_at.'</td><td><img src="'.$img.'" /> '.$row->wallet.'</td><td>'.$row->amount.' '.$settings->sitewallet.'</td><td><div class="tag tag-'.$tag.'">'.$stat.'</div></td></tr>';
            //     }
            //   }
            //   return json_encode(['success' => ["add_bd" => $payouts.$payouts]]);
            // }
            else {
              return json_encode(["error" => ["text" => "Этот платеж уже обработан"]]);
            }
					}
					else
					{
						return json_encode(["error" => ["text" => "Выплата не найдена"]]);
					}
				}
				else
				{
					return json_encode(["error" => ["text" => "Необходимо авторизоваться"]]);
				}
			}
			else
			{
				return json_encode(["error" => ["text" => "Не переданы параметры"]]);
			}
		}

		elseif($r->type == "resetPassPanel")
		{
      if(Auth::user()->is_ban == 1)
			{
				return json_encode(['ban' => ["text" => 'Обновите страницу']]);
			}
			if(Auth::check())
			{
				if(isset($r->newPass))
				{
					$user = Auth::user();

					$user->password = $r->newPass;
					$user->save();
					return json_encode(["success" => true]);
				}
				else
				{
					return json_encode(["error" => "Не переданы параметры"]);
				}
			}
			else
			{
				return json_encode(["error" => "Необходимо авторизоваться"]);
			}
		}

		elseif($r->type == "betMin")
		{
			if(Auth::guest())
			{
				return json_encode(['error' => ["text" => 'Необходимо авторизоваться']]);
			}
      elseif(Auth::user()->is_ban == 1)
			{
				return json_encode(['ban' => ["text" => 'Обновите страницу']]);
			}
			elseif(Auth::user()->money < $r->betSize)
			{
				return json_encode(['error' => ["text" => 'Недостаточно средств']]);
			}

			$hash = hash('sha512', Auth::user()->salt1.Auth::user()->number.Auth::user()->salt2);

			if($r->hash != $hash)
			{
				$user = Auth::user();
				$hash = json_decode($this->hash_generate());
				$user->salt1 = $hash->salt_1;
				$user->number = $hash->number;
				$user->salt2 = $hash->salt_2;
				$user->hash = hash('sha512', $hash->salt_1.$hash->number.$hash->salt_2);
				$user->save();

				return json_encode(["new" => ["hash" => hash('sha512', $hash->salt_1.$hash->number.$hash->salt_2), "text" => "Хэш обновлен!"]]);
			}

			$game = Game::where('hash', $hash)->first();

			if($game == true)
			{
				$user = Auth::user();
				$hash = json_decode($this->hash_generate());
				$user->salt1 = $hash->salt_1;
				$user->number = $hash->number;
				$user->salt2 = $hash->salt_2;
				$user->hash = hash('sha512', $hash->salt_1.$hash->number.$hash->salt_2);
				$user->save();

				return json_encode(["new" => ["hash" => hash('sha512', $hash->salt_1.$hash->number.$hash->salt_2), "text" => "Игра уже сыграна!"]]);
			}
			$o_balance = Auth::user()->money;
			$user = Auth::user();
			$user->money = $user->money - $r->betSize;
			$user->save();


			if(in_array(Auth::user()->number, range(0, $r->betPercent/100*999999)))
			{
				$game = \DB::table('games')->insertGetId([
					'salt1' => Auth::user()->salt1,
					'number' => Auth::user()->number,
					'salt2' => Auth::user()->salt2,
					'hash' => hash('sha512', Auth::user()->salt1.Auth::user()->number.Auth::user()->salt2),
					'arrow' => '0-'.(int)floor($r->betPercent/100*999999),
					'chance' => $r->betPercent,
					'summ' => $r->betSize,
					'profit' => round(100/$r->betPercent*$r->betSize,2),
					'user_id' => Auth::user()->id
				]);

				$user = Auth::user();
				$user->money = $user->money + round(100/$r->betPercent*$r->betSize,2);
				$hash = json_decode($this->hash_generate());
				$user->salt1 = $hash->salt_1;
				$user->number = $hash->number;
				$user->salt2 = $hash->salt_2;
				$user->hash = hash('sha512', $hash->salt_1.$hash->number.$hash->salt_2);
				$user->save();

				$n_balance = Auth::user()->money;



				return json_encode(["success" => ["type" => "win", "check_bet" => $game, "profit" => round(100/$r->betPercent*$r->betSize,2), "hash" =>  hash('sha512', $hash->salt_1.$hash->number.$hash->salt_2), "balance" => $o_balance, "new_balance" => $n_balance]]);
			}
			else
			{

				$game = \DB::table('games')->insertGetId([
					'salt1' => Auth::user()->salt1,
					'number' => Auth::user()->number,
					'salt2' => Auth::user()->salt2,
					'hash' => hash('sha512', Auth::user()->salt1.Auth::user()->number.Auth::user()->salt2),
					'arrow' => '0-'.(int)floor($r->betPercent/100*999999),
					'chance' => $r->betPercent,
					'summ' => $r->betSize,
					'profit' => '0.00',
					'user_id' => Auth::user()->id
				]);

				$number = Auth::user()->number;
				$user = Auth::user();
				$hash = json_decode($this->hash_generate());
				$user->salt1 = $hash->salt_1;
				$user->number = $hash->number;
				$user->salt2 = $hash->salt_2;
				$user->hash = hash('sha512', $hash->salt_1.$hash->number.$hash->salt_2);
				$user->save();

				$n_balance = Auth::user()->money;



				return json_encode(["success" => ["type" => "lose", "check_bet" => $game, "number" => $number, "hash" =>  hash('sha512', $hash->salt_1.$hash->number.$hash->salt_2), "balance" => $o_balance, "new_balance" => $n_balance]]);
			}

		}


		elseif($r->type == "betMax")
		{
			if(Auth::guest())
			{
				return json_encode(['error' => ["text" => 'Необходимо авторизоваться']]);
			}
      elseif(Auth::user()->is_ban == 1)
			{
				return json_encode(['ban' => ["text" => 'Обновите страницу']]);
			}
			elseif(Auth::user()->money < $r->betSize)
			{
				return json_encode(['error' => ["text" => 'Недостаточно средств']]);
			}

			$hash = hash('sha512', Auth::user()->salt1.Auth::user()->number.Auth::user()->salt2);

			if($r->hash != $hash)
			{
				$user = Auth::user();
				$hash = json_decode($this->hash_generate());
				$user->salt1 = $hash->salt_1;
				$user->number = $hash->number;
				$user->salt2 = $hash->salt_2;
				$user->hash = hash('sha512', $hash->salt_1.$hash->number.$hash->salt_2);
				$user->save();

				return json_encode(["new" => ["hash" => hash('sha512', $hash->salt_1.$hash->number.$hash->salt_2), "text" => "Хэш обновлен!"]]);
			}

			$game = Game::where('hash', $hash)->first();

			if($game == true)
			{
				$user = Auth::user();
				$hash = json_decode($this->hash_generate());
				$user->salt1 = $hash->salt_1;
				$user->number = $hash->number;
				$user->salt2 = $hash->salt_2;
				$user->hash = hash('sha512', $hash->salt_1.$hash->number.$hash->salt_2);
				$user->save();

				return json_encode(["new" => ["hash" => hash('sha512', $hash->salt_1.$hash->number.$hash->salt_2), "text" => "Игра уже сыграна!"]]);
			}
			$o_balance = Auth::user()->money;
			$user = Auth::user();
			$user->money = $user->money - $r->betSize;
			$user->save();


			if(in_array(Auth::user()->number, range((int)ceil(999999-$r->betPercent/100*999999), 999999)))
			{
				$game = \DB::table('games')->insertGetId([
					'salt1' => Auth::user()->salt1,
					'number' => Auth::user()->number,
					'salt2' => Auth::user()->salt2,
					'hash' => hash('sha512', Auth::user()->salt1.Auth::user()->number.Auth::user()->salt2),
					'arrow' => (int)ceil(999999-$r->betPercent/100*999999).'-999999',
					'chance' => $r->betPercent,
					'summ' => $r->betSize,
					'profit' => round(100/$r->betPercent*$r->betSize, 2),
					'user_id' => Auth::user()->id
				]);

				$user = Auth::user();
				$user->money = $user->money + round(100/$r->betPercent*$r->betSize, 2);
				$hash = json_decode($this->hash_generate());
				$user->salt1 = $hash->salt_1;
				$user->number = $hash->number;
				$user->salt2 = $hash->salt_2;
				$user->hash = hash('sha512', $hash->salt_1.$hash->number.$hash->salt_2);
				$user->save();

				$n_balance = Auth::user()->money;



				return json_encode(["success" => ["type" => "win", "check_bet" => $game, "profit" => round(100/$r->betPercent*$r->betSize,2), "hash" =>  hash('sha512', $hash->salt_1.$hash->number.$hash->salt_2), "balance" => $o_balance, "new_balance" => $n_balance]]);
			}
			else
			{

				$game = \DB::table('games')->insertGetId([
					'salt1' => Auth::user()->salt1,
					'number' => Auth::user()->number,
					'salt2' => Auth::user()->salt2,
					'hash' => hash('sha512', Auth::user()->salt1.Auth::user()->number.Auth::user()->salt2),
					'arrow' => (int)ceil(999999-$r->betPercent/100*999999).'-999999',
					'chance' => $r->betPercent,
					'summ' => $r->betSize,
					'profit' => '0.00',
					'user_id' => Auth::user()->id
				]);

				$number = Auth::user()->number;
				$user = Auth::user();
				$hash = json_decode($this->hash_generate());
				$user->salt1 = $hash->salt_1;
				$user->number = $hash->number;
				$user->salt2 = $hash->salt_2;
				$user->hash = hash('sha512', $hash->salt_1.$hash->number.$hash->salt_2);
				$user->save();

				$n_balance = Auth::user()->money;

				return json_encode(["success" => ["type" => "lose", "check_bet" => $game, "number" => $number, "hash" =>  hash('sha512', $hash->salt_1.$hash->number.$hash->salt_2), "balance" => $o_balance, "new_balance" => $n_balance]]);
			}

		}
	}

	public function bot_game()
	{
		$user = User::where('is_bot', 1)->orderByRaw("RAND()")->first();

		if($user == false)
		{
			return json_encode(["error" => "Боты не найдены. Добавьте в базу ботов!"]);
		}

		$betPercent = mt_rand(1,85);

		$settings = \DB::table('settings')->where('id', 1)->first();
		$time = mt_rand($settings->bot_deop1, $settings->bot_deop2);

		$r = mt_rand(0,1);
		if($r == 0)
		{
			$e = mt_rand(0,1);
			if($e == 0)
			{
				$s = mt_rand(1, 9);
				$summ = $s*100;
			}
			else
			{
				$summ = 1;
			}
		}
		else
		{
			$e = mt_rand(0,1);
			if($e == 0)
			{
				$s = mt_rand(1, 1);
				$summ = $s*1000;
			}
			else
			{
				$summ = 1;
			}
		}

		$hash = json_decode($this->hash_generate());
		$rand = mt_rand(0,1);
		if($rand == 0)
		{
			if(in_array($hash->number, range((int)ceil(999999-$betPercent/100*999999), 999999))) // BETMAX
			{
				$game = \DB::table('games')->insertGetId([
					'salt1' => $hash->salt_1,
					'number' => $hash->number,
					'salt2' => $hash->salt_2,
					'hash' => hash('sha512', $hash->salt_1.$hash->number.$hash->salt_2),
					'arrow' => (int)ceil(999999-$betPercent/100*999999).'-999999',
					'chance' => $betPercent,
					'summ' => $summ,
					'profit' => round(100/$betPercent*$summ, 2),
					'user_id' => $user->id
					]);

					return json_encode(["game" => $game, "time" => $time]);
			}
			else
			{
				$game = \DB::table('games')->insertGetId([
					'salt1' => $hash->salt_1,
					'number' => $hash->number,
					'salt2' => $hash->salt_2,
					'hash' => hash('sha512', $hash->salt_1.$hash->number.$hash->salt_2),
					'arrow' => (int)ceil(999999-$betPercent/100*999999).'-999999',
					'chance' => $betPercent,
					'summ' => $summ,
					'profit' => '0.00',
					'user_id' => $user->id
				]);

				return json_encode(["game" => $game, "time" => $time]);
			}
		}
		else
		{
			if(in_array($hash->number, range(0, $betPercent/100*999999)))
			{
				$game = \DB::table('games')->insertGetId([
					'salt1' => $hash->salt_1,
					'number' => $hash->number,
					'salt2' => $hash->salt_2,
					'hash' => hash('sha512', $hash->salt_1.$hash->number.$hash->salt_2),
					'arrow' => '0-'.(int)floor($betPercent/100*999999),
					'chance' => $betPercent,
					'summ' => $summ,
					'profit' => round(100/$betPercent*$summ,2),
					'user_id' => $user->id
				]);

				return json_encode(["game" => $game, "time" => $time]);
			}
			else
			{
				$game = \DB::table('games')->insertGetId([
					'salt1' => $hash->salt_1,
					'number' => $hash->number,
					'salt2' => $hash->salt_2,
					'hash' => hash('sha512', $hash->salt_1.$hash->number.$hash->salt_2),
					'arrow' => '0-'.(int)floor($betPercent/100*999999),
					'chance' => $betPercent,
					'summ' => $summ,
					'profit' => '0.00',
					'user_id' => $user->id
				]);

				return json_encode(["game" => $game, "time" => $time]);
			}
		}
	}

	public function getGame(Request $r)
	{
		$game = Game::where("id", $r->id)->first();
    $settings = \DB::table('settings')->where('id', 1)->first();

		$user = User::where('id', $game->user_id)->first();
		$url = "'/game/?id=".$game->id."'";

		if($game->chance < 20)
		{
			$color = 'danger';
		}
		elseif($game->chance >=20 && $game->chance < 49)
		{
			$color='warning';
		}
		else
		{
			$color = "success";
		}

		if($game->profit > 0)
		{
			$color2 = "success";
		}
		else
		{
			$color2 = "danger";
		}

		return json_encode(["text" => '<tr data-user="'.$game->user_id.'" data-game="1" onclick="window.open('.$url.');">
														<td class="text-truncate" style="font-weight:600">'.$user->name.'</td>
														<td class="text-truncate '.$color2.'" style="font-weight:600">'.$game->number.'</td>
														<td class="text-truncate " style="font-weight:600">'.$game->arrow.'</td>
														<td class="text-truncate" style="font-weight:600">'.$game->summ.' '.$settings->sitewallet.'</td>
														<td class="text-xs-center font-small-2">
															<span>
																<progress style="margin-top:8px" class="progress progress-sm progress-'.$color.' mb-0" value="'.$game->chance.'" max="100"></progress>
															</span>
														</td>
														<td class="text-truncate '.$color2.'" style="font-weight:600">'.$game->profit.' '.$settings->sitewallet.'</td>
													</tr>"}']);
		//{"new":"{"ddd":"asfwewer","text\":"<tr data-user="41303" data-game="1" onclick='window.open("/game/?id=118445031");'><td class="text-truncate" style="font-weight:600">Excelentzaebal</td><td class="text-truncate success" style="font-weight:600">600382</td><td class="text-truncate " style="font-weight:600">560000 - 999999</td><td class="text-truncate" style="font-weight:600">1 N</td><td class="text-xs-center font-small-2"><span><progress style="margin-top:8px" class="progress progress-sm progress-warning mb-0" value="44" max="100"></progress></span></td><td class="text-truncate success" style="font-weight:600">2.27 N</td></tr>"}","count":608}
	}


	public function game(Request $r)
	{
		if(!isset($r->id))
		{
			abort(404);
		}
		else
		{
			$game = Game::where('id', $r->id)->first();
			if($game == false)
			{
				abort(404);
			}
			return view('checkgame', compact('game'));
		}
	}

	public function getdrops(){
		$games = Game::orderBy('id', 'desc')->limit(20)->get();
		$text="";

		foreach($games as $game)
		{
			$user = User::where('id', $game->user_id)->first();
      $settings = \DB::table('settings')->where('id', 1)->first();
			$url = "'/game/?id=".$game->id."'";

			if($game->chance < 20)
			{
				$color = 'danger';
			}
			elseif($game->chance >=20 && $game->chance < 49)
			{
				$color='warning';
			}
			else
			{
				$color = "success";
			}

			if($game->profit > 0)
			{
				$color2 = "success";
			}
			else
			{
				$color2 = "danger";
			}

			$text = $text.'<tr data-user="'.$game->user_id.'" data-game="1" onclick="window.open('.$url.');">
															<td class="text-truncate" style="font-weight:600">'.$user->name.'</td>
															<td class="text-truncate '.$color2.'" style="font-weight:600">'.$game->number.'</td>
															<td class="text-truncate " style="font-weight:600">'.$game->arrow.'</td>
															<td class="text-truncate" style="font-weight:600">'.$game->summ.' '.$settings->sitewallet.'</td>
															<td class="text-xs-center font-small-2">
																<span>
																	<progress style="margin-top:8px" class="progress progress-sm progress-'.$color.' mb-0" value="'.$game->chance.'" max="100"></progress>
																</span>
															</td>
															<td class="text-truncate '.$color2.'" style="font-weight:600">'.$game->profit.' '.$settings->sitewallet.'</td>
														</tr>"}';
		}
		return json_encode(["text" => $text]);
	}

	public function captcha($secret, $resp)
	{
		$params = [
			'secret' => $secret,
			'response' => $resp,
			'remoteip' => $_SERVER['REMOTE_ADDR']
		];
		$curl = curl_init('https://www.google.com/recaptcha/api/siteverify?' . http_build_query($params));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$response = json_decode(curl_exec($curl));
		curl_close($curl);
		if (isset($response->success) && $response->success == true) {
			return 'true';
		} else {
			return 'false';
		}
	}

	public function getonline()
	{
		$settings = \DB::table('settings')->where('id', 1)->first();
		return json_encode(["online" => $settings->online]);
	}

	function test(Request $r)
	{
		$max = 999999-$r->percent/100*999999;
		$min = $r->percent/100*999999;

		$stavka = $r->stavka;
		$profit = (100/$r->percent*$stavka);
		dd((int)ceil($max), (int)$profit, (int)$min);
	}


    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

	public function hash_generate()
	{
		$salt_1 = $this->generate_salt1();
		$number = mt_rand(0,999999);
		$salt_2 = $this->generate_salt2();

		$hash = hash('sha512', $salt_1.$number.$salt_2);


		return json_encode(["salt_1" => $salt_1, "number" => $number, "salt_2" => $salt_2, "hash" => $hash]);
	}


	public function generate_salt1()
    {

        $length = mt_rand(10,25);
        $chars = 'abcdefhiknrstyzABCDEFGHKNQRSTYZ0123456789[]/*-=$%#@!^&(){}:;\.';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $string.'|';
    }

	public function generate_salt2()
	{
		$length = mt_rand(10,25);
        $chars = 'abcdefhiknrstyzABCDEFGHKNQRSTYZ0123456789[]/*-=$%#@!^&(){}:;\.';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return '|'.$string;
	}
	public function curl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
	public function yauspel()
	{
		return redirect('/');
	}

}
