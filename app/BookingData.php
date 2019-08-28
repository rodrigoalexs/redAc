<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BookingData
 * @package App
 *
 * @property integer $id
 * @property integer $pharmacy_id
 * @property string $role
 * @property double $booking_fee
 * @property double $rate
 * @property integer $start
 * @property integer $finish
 * @property integer $public
 * @property integer $created_at
 * @property integer $updated_at
 */
class BookingData extends Model
{
    protected $table = 'booking_data';

    public function pharmacy()
    {
        return $this->belongsTo('\App\Pharmacy');
    }

    public function note()
    {
        return $this->hasOne('\App\Note', 'booking_id');
    }
}
