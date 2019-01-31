<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Withdraw extends Model
{
    protected $table = 'withdraws';
	
	protected $fillable = ['user_id', 'system', 'amount', 'wallet', 'status'];
    
    protected $hidden = ['created_at', 'updated_at'];
    
}
