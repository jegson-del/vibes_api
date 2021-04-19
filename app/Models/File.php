<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class File extends Model
{
    use HasFactory;

    public const DIR_PROFILE = 'Profile';
    public const DIR_EVENT = 'Event';

    protected $guarded = [];

    protected $hidden = ['fileable_id', 'fileable_type', 'created_at', 'updated_at'];

    public function fullFilePath()
    {
        return config('app.url') . '/'. str_replace('public', 'storage', $this->link);
    }

    public function fileable()
    {
        return $this->morphTo();
    }

    public static function storeFile(Request $request, string $directory): self
    {
        //TODO: resize this image later
        $file = $request->file('image');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $link = $file->storeAs('public/' . $directory, $filename);

        return new File([
            'link' => $link
        ]);
    }

    public static function removeFile(string $path): void
    {
        Storage::disk('public')->delete($path);
    }
}
