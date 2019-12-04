<?php

namespace App\Adminux\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use SoftDeletes;

    protected $table = 'admins_currencies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency'
    ];

    /**
     * Get the software for the currency.
     */
    // public function software()
    // {
    //     return $this->hasMany('App\Adminux\Software\Models\Software');
    // }
}
