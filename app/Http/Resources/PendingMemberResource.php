<?php

namespace App\Http\Resources;
use App\Models\User;
use App\Models\Team;
use Illuminate\Http\Resources\Json\JsonResource;

class PendingMemberResource extends JsonResource
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
            'team_id'        =>  $this->team_id,
            'team_name'      =>  Team::where('id', $this->team_id)->first()->name,
            'member_name'    =>  User::where('id', $this->user_id)->first()->username,
            'status'         => 'Pending',
        ];
    }
}
