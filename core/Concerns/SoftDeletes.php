<?php

declare(strict_types=1);

namespace Core\Concerns;

/**
 * Soft Delete trait — Uses Eloquent's SoftDeletes.
 * Soft Delete trait — Eloquent'in SoftDeletes'ini kullanır.
 *
 * Usage in model / Modelde kullanım:
 *   class Post extends Model
 *   {
 *       use \Core\Concerns\SoftDeletes;
 *   }
 *
 * Migration — deleted_at column must be added / deleted_at sütunu eklenmeli:
 *   $table->softDeletes();  // Eloquent schema builder
 *   // or raw SQL / veya raw SQL:
 *   ALTER TABLE `posts` ADD COLUMN `deleted_at` TIMESTAMP NULL DEFAULT NULL;
 *
 * API:
 *   $post->delete()             → soft delete (deleted_at = now())
 *   $post->forceDelete()        → permanently delete / kalıcı sil
 *   $post->restore()            → restore / geri al (deleted_at = null)
 *   $post->trashed()            → bool
 *
 *   Post::all()                 → only where deleted_at IS NULL / deleted_at IS NULL olanlar
 *   Post::withTrashed()->get()  → includes soft deleted / silinmişler dahil
 *   Post::onlyTrashed()->get()  → only soft deleted / sadece silinmişler
 */
trait SoftDeletes
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
}
