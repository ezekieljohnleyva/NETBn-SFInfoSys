<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    public $table = 'audits';

    protected $fillable = [
        'user_id',
        'event',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent'

    ];

    // protected $casts = [
    //     'properties' => 'collection',
    // ];

    // protected function serializeDate(DateTimeInterface $date)
    // {
    //     return $date->format('Y-m-d H:i:s');
    // }

    public function creator()
    {
        return $this->setConnection('mysql')->belongsTo(User::class, 'user_id',  'id');
    }
}
