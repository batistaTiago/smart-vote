<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $guarded = [];

    public const AVAILABLE_FILTERS = [
        'session_id' => '=',
        'document_id' => '=',
        'user_id' => '=',
        'vote_category_id' => '='
    ];

    public static function findWithFilters(array $filters)
    {
      $query = self::query();

      foreach ($filters as $key => $value) {
          if (array_key_exists($key, self::AVAILABLE_FILTERS)) {
              $operator = self::AVAILABLE_FILTERS[$key];
              $query->where($key, $operator, $value);
          }
      }

      return $query->get();
    }
}
