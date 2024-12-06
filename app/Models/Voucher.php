<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Voucher
 * 
 * @property int $voucher_id
 * @property string|null $voucher_name
 * @property string|null $voucher_type
 * @property string|null $description
 * @property Carbon|null $voucher_start_date
 * @property Carbon|null $voucher_end_date
 * @property int|null $value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Order[] $orders
 *
 * @package App\Models
 */
class Voucher extends Model
{
	protected $table = 'vouchers';
	protected $primaryKey = 'voucher_id';

	protected $casts = [
		'voucher_start_date' => 'datetime',
		'voucher_end_date' => 'datetime',
		'value' => 'int'
	];

	protected $fillable = [
		'voucher_name',
		'voucher_type',
		'description',
		'voucher_start_date',
		'voucher_end_date',
		'value'
	];

	public function orders()
	{
		return $this->hasMany(Order::class);
	}
}
