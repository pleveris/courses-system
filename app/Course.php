<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Course extends Model
{

    protected $fillable = ['name', 'schedule', 'start_date', 'end_date'];

    /**
     * Get students belongs to course
     * @return App\Student
     */
    public function students()
    {
        return $this->belongsToMany('App\Student')->withTimestamps();
    }

    public function folders(): HasMany
    {
        return $this->hasMany(Folder::class);
    }

    /**
     * A course can have many files
     */

    public function files(): HasManyThrough
    {
        return $this->hasManyThrough(File::class, Folder::class);
    }

}
