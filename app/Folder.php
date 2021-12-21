<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;


class Folder extends Model
{
    protected $fillable = ['course_id', 'user_id', 'name'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

}
