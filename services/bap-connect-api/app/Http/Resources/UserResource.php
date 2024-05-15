<?php

namespace App\Http\Resources;

use App\Utils\Constants;
use Carbon\Carbon;
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
        $birthday = null;
        if ($this->birthday_day && $this->birthday_year && $this->birthday_month) {
            $birthday = Carbon::parse(implode('-', [$this->birthday_year, $this->birthday_month, $this->birthday_day]))->utc()
                ->format(Constants::DATE_FORMAT_ISO);
        }

        return [
            'id' => $this->id,
            'avatar' => $this->avatar,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'birthday' => $birthday,
            'province' => $this->province,
            'district' => $this->district,
            'ward' => $this->ward,
            'address' => $this->address,
            'gender' => $this->gender,
            'phone' => $this->phone,
            'username' => $this->username,
            'email' => $this->email,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'creator_name' => $this->creator_name,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'updater_name' => $this->updater_name,
        ];
    }
}
