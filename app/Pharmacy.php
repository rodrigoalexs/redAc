<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Pharmacy
 * @package App
 *
 * @property integer $pharmacy_id
 * @property string $branch_identifier
 * @property double $pharmacist_booking_fee
 * @property double $pharmacist_rate
 * @property double $pharmacist_saturday_rate
 * @property double $pharmacist_sunday_rate
 * @property integer $created_at
 * @property integer $updated_at
 */
class Pharmacy extends Model
{
    protected $primaryKey = 'pharmacy_id';
    protected $table = 'pharmacy';
    protected $fillable  = [
        'branch_identifier',
        'pharmacist_booking_fee',
        'pharmacist_rate',
        'pharmacist_saturday_rate',
        'pharmacist_saturday_rate',
        'pharmacist_sunday_rate',
    ];

    public function bookings()
    {
        return $this->hasMany('\App\BookingData');
    }
}
