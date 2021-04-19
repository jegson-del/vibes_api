<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    public const ROLE_SUBSCRIBER = 'subscriber';
    public const ROLE_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function events()
    {
        return $this->belongsToMany(
            Event::class,
            'payments',
            'user_id',
            'event_id'
        );
    }

    public function paidEvents(Event $event)
    {
        return $this->events()
                ->where('event_id', $event->id)
                ->with('address', 'flyer', 'prices')
                ->get();
    }

    public function profilePics(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public static function make(Request $request): self
    {
        return User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'name' => $request->name
        ]);
    }

    public function blockedEvents()
    {
        return $this->belongsToMany(Event::class, 'user_block_events')
            ->with('address', 'flyer', 'prices');
    }
}
