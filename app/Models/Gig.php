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

    if ($filters['sort'] ?? false) {
      $sortColumn = $filters['sort'];
      if ($sortColumn === 'title') {
        $query->orderBy('title');
      } elseif ($sortColumn === 'company') {
        $query->orderBy('company');
      } elseif ($sortColumn === 'location') {
        $query->orderBy('location');
      } else {
        $query->orderBy('title');
      }
    } else {
      $query->orderBy('title');
    }
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }
}
