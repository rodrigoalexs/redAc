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
    protected $fillable  = [
        'booking_id',
        'note',
        'private',
    ];

    public function bookingData()
    {
        return $this->belongsTo('\App\BookingData');
    }
}
