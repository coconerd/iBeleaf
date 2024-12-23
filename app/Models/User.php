<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 *
 * @property int $user_id
 * @property int $role_type
 * @property string|null $email
 * @property string $full_name
 * @property string|null $user_name
 * @property string|null $password
 * @property string $phone_number
 * @property string $province_city
 * @property string $district
 * @property string|null $commune_ward
 * @property string $address
 * @property string|null $gender
 * @property Carbon|null $date_of_birth
 * @property string|null $avatar
 * @property int|null $card_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Cart|null $cart
 * @property Collection|Order[] $orders
 * @property Collection|ProductFeedback[] $product_feedbacks
 * @property Collection|ReturnRefundItem[] $return_refund_items
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	use HasFactory;

	use AuthenticatableTrait;
	use Notifiable;

	protected $table = 'users';
	protected $primaryKey = 'user_id';
	public $incrementing = true;

	protected $casts = [
		'user_id' => 'int',
		'role_type' => 'int',
		'date_of_birth' => 'datetime',
		'cart_id' => 'int',
		'password' => 'hashed'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'role_type',
		'email',
		'full_name',
		'user_name',
		'password',
		'phone_number',
		'province_city',
		'district',
		'commune_ward',
		'address',
		'gender',
		'date_of_birth',
		'avatar',
		'cart_id'
	];

	public function cart()
	{
		return $this->belongsTo(Cart::class, 'cart_id', 'cart_id');
	}

	public function orders()
	{
		return $this->hasMany(Order::class, 'user_id', 'user_id');
	}

	public function product_feedbacks()
	{
		return $this->hasMany(ProductFeedback::class, 'user_id', 'user_id');
	}

	public function return_refund_items()
	{
		return $this->hasMany(ReturnRefundItem::class);
	}

	public function wishlist()
	{
		return $this->belongsToMany(
			Product::class,
			'wishlists',
			'user_id',     // Foreign key on the pivot table referencing the user
			'product_id'   // Foreign key on the pivot table referencing the product
		);
	}
}
