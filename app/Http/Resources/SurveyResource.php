<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SurveyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            =>  $this->id,
            'name'          =>  $this->name,
            'slug'          =>  $this->slug,
            'json'          =>  $this->json,
            'user_id'       =>  $this->user_id,
            'team_id'       =>  $this->team_id,
            'created_at'    =>  $this->created_at->formatLocalized('%A %d %B %Y'),
        ];
    }
}
