<?php

namespace App\Models;

use \DateTimeInterface;
// use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Unit extends Model implements Auditable
{
   // use SoftDeletes;
   // use MultiTenantModelTrait;

    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    // protected $connection = 'pactis_mysql';
    public $table = 'tr_unit';

    public static $searchable = [
        'unit_code',
        'description',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'unit_code',
        'description',
        'created_at',
        'pamu_code',
        'pamu_id',
        'updated_at',
        'deleted_at',
        'team_id',
    ];

    public function pamu()
    {
        return $this->belongsTo(Pamu::class, 'PAMU', 'pamu');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
