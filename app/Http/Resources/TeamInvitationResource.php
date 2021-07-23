<?php

namespace App\Http\Resources;
use Mpociot\Teamwork\TeamInvite;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
class TeamInvitationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // accessing:
        return [
            'team name'     =>  $this->name,
            'team owner'    =>  User::where('id', $this->owner_id)->first()->username,
        ];
    }
}
