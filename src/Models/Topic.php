<?php

namespace Azuriom\Plugin\KoriaForum\Models;

use Azuriom\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $title
 * @property string|null $status pending | accepted | refused
 */
class Topic extends Model
{
    public const STATUSES = [
        'pending' => ['En attente du staff', 'warning'],
        'accepted' => ['Validé : ban + remboursement', 'success'],
        'refused' => ['Refusé', 'danger'],
    ];

    protected $table = 'koriaforum_topics';

    protected $fillable = [
        'category_id', 'author_id', 'title', 'traitor', 'faction', 'betrayal_date',
        'losses', 'proofs', 'status', 'staff_note', 'staff_id', 'last_post_at',
    ];

    protected $casts = [
        'betrayal_date' => 'date',
        'last_post_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function isReport(): bool
    {
        return $this->status !== null;
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status][0] ?? '';
    }

    public function statusColor(): string
    {
        return self::STATUSES[$this->status][1] ?? 'secondary';
    }
}
