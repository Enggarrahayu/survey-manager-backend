<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
class TeamResource extends JsonResource
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
            'id'               => $this->id,
            'team_name'        =>  $this->name,
            'created_at'       => $this->created_at,
            'membership'       => DB::table('team_user')
                                ->groupBy(DB::raw("team_id"))
                                ->where('team_id', $this->id)
                                ->count('*'),
            'survey_number'    => DB::table('surveys')
                                ->groupBy(DB::raw("team_id"))
                                ->where('team_id', $this->id)
                                ->count('*'),
        ];
    }
}
