<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

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
class User extends Model implements Authenticatable
{
    use AuthenticatableTrait;

	protected $table = 'users';
	protected $primaryKey = 'user_id';
	public $incrementing = false;

	protected $casts = [
		'user_id' => 'int',
		'role_type' => 'int',
		'date_of_birth' => 'datetime',
		'card_id' => 'int',
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
}
