<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gig extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'title',
    'company',
    'location',
    'website',
    'email',
    'tags',
    'logo',
    'description'
  ];

  public function scopeFilter($query, array $filters)
  {
    if ($filters['tags'] ?? false) {
      $query->where('tags', 'like', '%' . $filters['tags'] . '%');
    }
    if ($filters['search'] ?? false) {
      $query->where('title', 'like', '%' . $filters['search'] . '%')
        ->orWhere('location', 'like', '%' . $filters['search'] . '%')
        ->orWhere('tags', 'like', '%' . $filters['search'] . '%');
    }
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }
}
