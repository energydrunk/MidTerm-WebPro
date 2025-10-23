<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','name','slug','category','description','price','stock','image_url','is_active'
    ];

    protected $casts = [
        'price'     => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($p) {
            if (empty($p->slug)) $p->slug = Str::slug($p->name.'-'.substr(sha1(uniqid()),0,6));
        });
        static::updating(function ($p) {
            if ($p->isDirty('name') && empty($p->slug)) {
                $p->slug = Str::slug($p->name.'-'.substr(sha1(uniqid()),0,6));
            }
        });
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
