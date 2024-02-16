<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "last_name" => $this->last_name,
            "first_name" => $this->first_name,
            "birthday"=> $this->birthday,
            "gender"=> $this->gender,
            "on_board_date"=> $this->on_board_date,
            "exit_date"=> $this->exit_date,
            "is_active"=> $this->is_active,
            "email"=> $this->email,
        ];
    }
}
