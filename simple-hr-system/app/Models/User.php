<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Contracts\GenericModel;

class User extends Authenticatable implements GenericModel
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'bool',
    ];

    public function yearlyAnnualLeaves(): HasMany
    {
        return $this->hasMany(UserYearlyAnnualLeave::class);
    }

    public function leaveRequests(): HasMany
    {
        return $this->hasMany(UserLeaveRequest::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->role->permissions();
    }

    public function hasRole($roleName)
    {
        return $this->role?->name === $roleName;
    }

    public function hasPermission($permissionSlug): bool
    {
        return $this->role?->containPermission($permissionSlug) ??  false;
    }

    public function scopeFilterName($query, $value)
    {
        if (empty($value)) return $query;
        return $query->where('name', 'like', '%' . $value . '%');
    }

    public function saveManyYearlyAnnualLeaves($yearlyAnnualLeaves)
    {
        // Perform the upsert operation
        foreach ($yearlyAnnualLeaves as $leave) {
            UserYearlyAnnualLeave::updateOrCreate(
                ['user_id' => $this->id, 'year' => $leave['year']], // Match the year for this user
                ['number_of_day' => $leave['number_of_day'], 'additional_number_of_day' => $leave['additional_number_of_day']] // Update or insert the number_of_day and additional_number_of_day
            );
        }
    }
}
