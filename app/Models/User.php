<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 * 
 * @property int $user_id
 * @property int $role_type
 * @property string|null $email
 * @property string|null $full_name
 * @property string|null $user_name
 * @property string $password
 * @property string|null $phone_number
 * @property string|null $province_city
 * @property string|null $district
 * @property string|null $commune_ward
 * @property string|null $address
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
 * @property Wishlist $wishlist
 *
 * @package App\Models
 */
class User extends Model
{
	protected $table = 'users';
	protected $primaryKey = 'user_id';

	protected $casts = [
		'role_type' => 'int',
		'date_of_birth' => 'datetime',
		'card_id' => 'int'
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
		'card_id'
	];

	public function cart()
	{
		return $this->belongsTo(Cart::class, 'card_id');
	}

	public function orders()
	{
		return $this->hasMany(Order::class);
	}

	public function product_feedbacks()
	{
		return $this->hasMany(ProductFeedback::class);
	}

	public function return_refund_items()
	{
		return $this->hasMany(ReturnRefundItem::class);
	}

	public function wishlist()
	{
		return $this->hasOne(Wishlist::class);
	}
}
