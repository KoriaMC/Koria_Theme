<?php

namespace Azuriom\Plugin\KoriaForum\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string $description
 * @property bool $is_reports
 */
class Category extends Model
{
    protected $table = 'koriaforum_categories';

    protected $fillable = ['slug', 'name', 'description', 'is_reports', 'position'];

    protected $casts = ['is_reports' => 'boolean'];

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class);
    }
}
