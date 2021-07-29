<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Survey extends Model
{
    use SoftDeletes;

    protected $table = 'surveys';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',  'user_id', 'slug', 'json', 'team_id',
    ];
    
    protected $casts = [
        'json'  =>  'array',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($survey) {
            $survey->slug = Str::slug($survey->name);

            $latestSlug = static::whereRaw("slug = '$survey->slug' or slug LIKE '$survey->slug-%'")
                                ->latest('id')
                                ->value('slug');
            if ($latestSlug) {
                $pieces = explode('-', $latestSlug);

                $number = intval(end($pieces));

                $survey->slug .= '-'.($number + 1);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(config('survey-manager.user_model'), 'user_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
    
}
