<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Intervention\Image\Image;

//use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    //use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
protected $fillable = [
    'role',
    'name',
    'url_name',     // <-- ADD THIS
    'email',
    'password',
    'twitter_link',
    'image',
    'description',  // <-- ADD THIS
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
    ];

    /**
     * Relationship: User has many Blogs (as an author)
     */
    public function blogs()
    {
        return $this->hasMany(Blog::class, 'author');
    }
}
