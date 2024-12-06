<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Cart
 * 
 * @property int $cart_id
 * @property int|null $items_count
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class Cart extends Model
{
	protected $table = 'carts';
	protected $primaryKey = 'cart_id';
	public $incrementing = false;

	protected $casts = [
		'cart_id' => 'int',
		'items_count' => 'int'
	];

	protected $fillable = [
		'items_count'
	];

	public function users()
	{
		return $this->hasMany(User::class, 'card_id');
	}
}
