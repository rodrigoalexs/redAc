<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Note
 * @package App
 *
 * @property integer $id
 * @property integer $booking_id
 * @property string $note
 * @property integer $private
 * @property integer $create_at
 * @property integer $updated_at
 */
class Note extends Model
{
    public function bookingData()
    {
        return $this->belongsTo('\App\BookingData', 'booking_id');
    }
}
