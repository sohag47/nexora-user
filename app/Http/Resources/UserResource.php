<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'photo' => $this->photo,
            'email_verified_at' => $this->email_verified_at,
            'gender' => $this->gender,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,

            // Conditional relationship loading
            'branch_id' => $this->branch_id,
            'branch' => $this->relationLoaded('branch') && $this->branch ? $this->branch->name : null,

            'designation_id' => $this->designation_id,
            'designation' => $this->relationLoaded('designation') && $this->designation ? $this->designation->name : null,

            'department_id' => $this->department_id,
            'department' => $this->relationLoaded('department') && $this->department ? $this->department->name : null,

            // 'branch' => $this->relationLoaded('branch') ? $this->branch : null,
            // 'designation' => $this->relationLoaded('designation') ? $this->designation : null,
            // 'department' => $this->relationLoaded('department') ? $this->department : null,

        ];
    }
}
