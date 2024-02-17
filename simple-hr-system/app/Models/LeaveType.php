<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LeaveType extends Model
{
    use HasFactory;
    protected $fillable = ["name", "in_use"];

    protected $casts = [
        "in_use" => "bool",
    ];

    public function userLeaveRequests(): BelongsToMany
    {
        return $this->belongsToMany(UserLeaveRequest::class);
    }

    public function scopeFilterInUse($query, $value)
    {
        if (strtolower($value) == "yes") return $query->where("in_use", 1);
        return $query;
    }
}
