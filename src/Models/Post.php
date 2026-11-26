<?php

namespace Azuriom\Plugin\KoriaForum\Models;

use Azuriom\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\HtmlString;

class Post extends Model
{
    protected $table = 'koriaforum_posts';

    protected $fillable = ['topic_id', 'author_id', 'content', 'is_verdict'];

    protected $casts = ['is_verdict' => 'boolean'];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Texte brut -> HTML sûr : tout est échappé, les liens http(s) deviennent cliquables.
     */
    public static function render(?string $text): HtmlString
    {
        $html = e($text ?? '');
        $html = preg_replace(
            '~(https?://[^\s<]+)~i',
            '<a href="$1" target="_blank" rel="nofollow noopener noreferrer">$1</a>',
            $html
        );

        return new HtmlString(nl2br($html));
    }
}
