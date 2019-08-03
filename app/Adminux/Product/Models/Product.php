<?php

namespace App\Adminux\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'partner_id', 'service_id', 'product', 'domain', 'currency_id', 'price'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at'
    ];

    /**
     * Get the partner.
     */
    public function partner()
    {
        return $this->belongsTo('App\Adminux\Partner\Models\Partner')->withTrashed();
    }

    /**
     * Get the service.
     */
    public function service()
    {
        return $this->belongsTo('App\Adminux\Service\Models\Service')->withTrashed();
    }

    /**
     * Get the currency.
     */
    public function currency()
    {
        return $this->belongsTo('App\Adminux\Admin\Models\Currency')->withTrashed();
    }

    /**
     * Get the plans.
     */
    public function plans()
    {
        return $this->hasMany('App\Adminux\Product\Models\Plan');
    }
}
