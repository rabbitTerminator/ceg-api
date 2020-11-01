<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property int $sensor_id
 * @property string $experiment_name
 * @property string $sensor_data
 * @property User $user
 */
class TrackingSensors extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'UserTrackingSensorList';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'sensor_id', 'experiment_name', 'sensor_data'];
    public $timestamps = false;
    protected $casts = [
        'sensor_data' => 'array',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
