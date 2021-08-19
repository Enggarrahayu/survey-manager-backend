<?php

namespace App\Http\Resources;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
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
            'id'             => $this->team_id,
            'member_name'    =>  User::where('id', $this->user_id)->first()->username,
            'email'          =>  User::where('id', $this->user_id)->first()->email,
            'role'           => 'member',
            'team_id'        => $this->team_id,
            'user_id'        => $this->user_id,
        ];
    }
}
