<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\Request;

class Event extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const ACTIVE = 'Active';
    public const POSTPONE = 'Postpone';
    public const CANCELLED = 'Cancelled';

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function flyer(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function prices()
    {
        return $this->belongsToMany(PriceType::class, 'event_prices')
            ->withPivot('price')
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'payments',
            'event_id',
            'user_id'
        );
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function change(Request $request): bool
    {
        return $this->update([
            'name' => $request->name,
            'description' => $request->description,
            'starts' => $request->starts,
            'ends' => $request->ends,
            'genre' => $request->genre,
        ]);
    }

    public function fileDelete(string $dir): self
    {
        $path = $dir . '/' . $this->flyer->link;
        File::removeFile($path);

        return $this;
    }

    public function scopeNotExpired($query)
    {
        return $query->where('ends', '>', now());
    }

    public function scopeNotCancelled($query)
    {
        return $query->where('status', '!=', self::CANCELLED);
    }

    public function scopeNotBlocked($query)
    {
        if ($user = request()->user()) {
            return $query->whereNotIn('id', function ($query) use ($user) {
                $query->select('event_id')
                    ->from(with(new UserBlockEvent())->getTable())
                    ->where('user_id', $user->id);
            });
        }
    }

    public function scopeWithSearch($query, $keyword)
    {
        return $query->where(function ($query) use ($keyword) {
            $query->where('name', 'like', '%' . $keyword . '%')
                ->orWhere('description', 'like', '%' . $keyword . '%')
                ->orWhere('genre', 'like', '%' . $keyword . '%')
                ->orwhereHas('address', function ($q) use ($keyword) {
                    $q->where('city', 'like', '%' . $keyword . '%');
                });
        });
    }
}
