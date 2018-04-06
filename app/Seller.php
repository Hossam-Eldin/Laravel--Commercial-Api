<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\SellersScope;
use App\Transformers\SellersTransformer;

class Seller extends User
{
	public $transformer = SellersTransformer::class;

	protected static function boot(){
		parent::boot();
		static::addGlobalScope(new SellersScope);
	}

     public function products()
    {
    	return $this->hasMany(Product::class);
    }
}
