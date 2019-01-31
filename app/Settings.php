<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Settings extends Model
{
    protected $table = 'settings';
	
	protected $fillable = ['vk_id', 'vk_secret', 'vk_redirect_uri', 'fk_id', 'fk_secret1', 'fk_secret2' ,'ref_percent', 'adm_email', 'vk_group', 'vk_token', 'vk_group_id', 'min_dep', 'min_width', 'online', 'bot_deop1', 'bot_deop2'];
    
    protected $hidden = ['created_at', 'updated_at'];
    
}
