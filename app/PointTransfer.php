<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PointTransfer extends Model
{
    protected $table = 'point_transfers';

    protected $fillable = [
        'dealer_id', 
        'dealer_code',	
        'painter_id', 
        'painter_code', 
        'transaction_id',	
        'point',	
        'month',	
        'remarks',	
        'created_at',
        'updated_at',
	];
}
