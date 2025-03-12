<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    use HasRoles;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'last_name',
        'username',
        'date_of_birth',
        'email',
        'password',
        'phone',
        'address',   
        'zip_code',
        'city',
        'state',
        'country',
        'gender',
        'profile_photo_path',
        'terms_and_conditions',
        'latitude',
        'longitude',     
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the company data associated with the user.
     */
    public function companyData(): HasOne
    {
        return $this->hasOne(CompanyData::class);
    }

    /**
     * Get the blog categories created by the user.
     */
    public function blogCategories(): HasMany
    {
        return $this->hasMany(BlogCategory::class);
    }

    /**
     * Get the posts created by the user.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the email data created by the user.
     */
    public function emailData(): HasMany
    {
        return $this->hasMany(EmailData::class);
    }
}
