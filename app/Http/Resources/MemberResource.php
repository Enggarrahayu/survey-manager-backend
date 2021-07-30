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
            'member name'    =>  User::where('id', $this->user_id)->first()->username,
        ];
    }
}
