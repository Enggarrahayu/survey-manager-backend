<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Team;
use App\Models\User;
class TeamUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $owner_id = Team::where('id', $this->team_id)->first()->owner_id;
        return [
            'id'               =>  $this->team_id,
            'team_name'        =>  Team::where('id', $this->team_id)->first()->name,
            'owner_id'         =>  Team::where('id', $this->team_id)->first()->owner_id,
            'team_owner'       =>  User::where('id', $owner_id)->first()->username,
        ];
    }
}
