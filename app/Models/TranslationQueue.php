<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TranslationQueue extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'translation_queue';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'text_hash',
        'original_text',
        'context',
        'file_path',
        'line_number',
        'processed',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'processed' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope per elementi non processati
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('processed', false);
    }

    /**
     * Scope per elementi processati
     */
    public function scopeProcessed(Builder $query): Builder
    {
        return $query->where('processed', true);
    }

    /**
     * Scope per filtrare per contesto
     */
    public function scopeContext(Builder $query, string $context): Builder
    {
        return $query->where('context', 'like', "%{$context}%");
    }

    /**
     * Scope per filtrare per file
     */
    public function scopeFile(Builder $query, string $filePath): Builder
    {
        return $query->where('file_path', 'like', "%{$filePath}%");
    }

    /**
     * Scope per cercare nel testo
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where('original_text', 'like', "%{$search}%");
    }

    /**
     * Verifica se l'elemento è stato processato
     */
    public function isProcessed(): bool
    {
        return $this->processed;
    }

    /**
     * Verifica se l'elemento è in attesa
     */
    public function isPending(): bool
    {
        return !$this->processed;
    }

    /**
     * Marca come processato
     */
    public function markAsProcessed(): bool
    {
        return $this->update(['processed' => true]);
    }

    /**
     * Ottiene il nome del file senza percorso completo
     */
    public function getFileNameAttribute(): string
    {
        return basename($this->file_path ?? '');
    }

    /**
     * Ottiene il percorso relativo del file
     */
    public function getRelativePathAttribute(): string
    {
        if (!$this->file_path) {
            return '';
        }

        $basePath = base_path();
        return str_replace($basePath . '/', '', $this->file_path);
    }
}
