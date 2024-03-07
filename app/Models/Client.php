<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'birthday', 'phone'];

    protected $casts = [
        'birthday' => 'immutable_datetime:Y-m-d'
    ];

    public function segments()
    {
        return $this->belongsToMany(Segment::class, 'segments_clients_pivot')->orderBy('created_at', 'desc');;
    }
}
