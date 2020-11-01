<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $url_id
 * @property string $chart_data
 * @property User $user
 */
class ExportedCharts extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'UserExportedCharts';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'url_id', 'chart_data'];
    protected $casts = [
        'chart_data' => 'array',
    ];
    public $timestamps = false;
    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
