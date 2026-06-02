<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait HasSoftDeleteFlag
{
    protected bool $forceDeleting = false;

    /** @var array<string, bool> */
    protected static array $deletedColumnCache = [];

    public static function bootHasSoftDeleteFlag(): void
    {
        $instance = new static();

        if (! $instance->supportsDeletedFlag()) {
            return;
        }

        static::addGlobalScope('not_deleted', function (Builder $builder) use ($instance): void {
            $builder->where($instance->qualifyColumn('deleted'), 0);
        });

        static::deleting(function ($model) {
            if ($model->forceDeleting || ! $model->supportsDeletedFlag()) {
                return true;
            }

            $model->newQueryWithoutScopes()
                ->whereKey($model->getKey())
                ->update(['deleted' => 1]);

            return false;
        });
    }

    public function forceDelete(): bool|null
    {
        $this->forceDeleting = true;

        return $this->delete();
    }

    public function restore(): bool
    {
        if (! $this->supportsDeletedFlag()) {
            return false;
        }

        return (bool) $this->newQueryWithoutScopes()
            ->whereKey($this->getKey())
            ->update(['deleted' => 0]);
    }

    public function trashed(): bool
    {
        return $this->supportsDeletedFlag() && (int) ($this->deleted ?? 0) === 1;
    }

    protected function supportsDeletedFlag(): bool
    {
        $table = $this->getTable();

        if (! array_key_exists($table, static::$deletedColumnCache)) {
            static::$deletedColumnCache[$table] = Schema::hasColumn($table, 'deleted');
        }

        return static::$deletedColumnCache[$table];
    }
}
