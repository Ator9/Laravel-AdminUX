<?php

namespace App\Adminux\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency'
    ];

    /**
     * Get the services for the currency.
     */
    // public function services()
    // {
    //     return $this->hasMany('App\Adminux\Service\Models\Service');
    // }
}
