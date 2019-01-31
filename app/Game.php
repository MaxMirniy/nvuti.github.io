<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Game extends Model
{
    protected $table = 'games';
	
	protected $fillable = ['salt1', 'number', 'salt2', 'hash', 'arrow', 'chance' ,'summ', 'profit', 'user_id'];
    
    protected $hidden = ['created_at', 'updated_at'];
    
}
