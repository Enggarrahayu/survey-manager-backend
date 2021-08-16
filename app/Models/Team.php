<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mpociot\Teamwork\TeamworkTeam;

class Team extends TeamworkTeam
{
    use HasFactory;

    protected $table = 'teams';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name','owner_id', 
    ];
    
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function surveys()
    {
        return $this->hasMany('app\Models\Survey');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($team) {
            $team->name = str_slug($team->name);

            $latestSlug = static::whereRaw("name = '$team->name' or name LIKE '$team->name-%'")
                                ->latest('id')
                                ->value('name');
            if ($latestSlug) {
                $pieces = explode('-', $latestSlug);

                $number = intval(end($pieces));

                $team->name .= '-'.($number + 1);
            }
        });
    }
}
