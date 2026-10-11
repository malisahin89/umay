<?php

declare(strict_types=1);

namespace Core;

use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * Umay Base Model — Full Eloquent Power
 * Umay Temel Model — Tam Eloquent Gücü
 *
 * All Eloquent features are available out of the box:
 * Tüm Eloquent özellikleri kutudan çıkar çıkmaz kullanılabilir:
 *
 *  - Eager Loading:       User::with('posts')->get()
 *  - Scopes:              User::active()->get()
 *  - Accessors/Mutators:  getFullNameAttribute / setPasswordAttribute
 *  - Attribute Casting:   protected $casts = ['data' => 'array']
 *  - Soft Deletes:        use SoftDeletes trait
 *  - Polymorphic:         morphTo, morphMany, morphToMany
 *  - BelongsToMany:       $this->belongsToMany(Role::class)
 *  - HasManyThrough:      $this->hasManyThrough(Post::class, User::class)
 *  - Model Events:        creating, saving, deleting, etc.
 *  - Collections:         ->pluck(), ->groupBy(), ->filter()...
 */
abstract class Model extends EloquentModel
{
    public $timestamps = true;
}
