<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamInvites extends Model
{
    use HasFactory;

    protected $table = 'surveys';
    protected $primaryKey = 'id';
    public $incrementing = false;
}
