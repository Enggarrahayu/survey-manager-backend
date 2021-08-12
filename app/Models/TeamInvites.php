<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamInvites extends Model
{
    use HasFactory;

    protected $table = 'team_invites';
    protected $primaryKey = 'invitation_key';
    public $incrementing = false;
}
