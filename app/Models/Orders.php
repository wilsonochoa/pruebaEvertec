<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users;


class Orders extends Model
{
    use HasFactory;

    const statesTra = [
        '1' => 'Creada',
        '2' => 'Rechazada',
        '3' => 'Pagada',
        '4' => 'Pendiente'
    ];

    protected $fillabel = ['status', 'id_user', 'id_request', 'process_url'];

    public function users()
    {
        return $this->belongsTo(Users::class, 'id_user');
    }
}
