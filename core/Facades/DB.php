<?php

declare(strict_types=1);

namespace Core\Facades;

use Core\Database;
use Core\Support\Facade;

/**
 * DB Facade — static proxy for Core\Database.
 * DB Facade — Core\Database için statik proxy.
 *
 * Core\Database artık Eloquent Capsule üzerinden çalışır.
 * Tüm metotlar Eloquent Connection nesnesine delege edilir.
 *
 * Usage / Kullanım:
 *   DB::select('SELECT * FROM users WHERE id = ?', [1]);
 *   DB::selectOne('SELECT * FROM users WHERE email = ?', [$email]);
 *   DB::insert('INSERT INTO logs (message) VALUES (?)', ['test']);
 *   DB::update('UPDATE users SET name = ? WHERE id = ?', ['Ali', 1]);
 *   DB::delete('DELETE FROM sessions WHERE user_id = ?', [5]);
 *   DB::statement('SET FOREIGN_KEY_CHECKS=0');
 *   DB::transaction(fn() => ...);
 *   DB::beginTransaction();
 *   DB::commit();
 *   DB::rollBack();
 *
 * Eloquent Query Builder kullanımı için:
 *   use Illuminate\Database\Capsule\Manager as DB;
 *   DB::table('users')->where('id', 1)->first();
 *
 * @method static array select(string $sql, array $bindings = [])
 * @method static object|null selectOne(string $sql, array $bindings = [])
 * @method static bool insert(string $sql, array $bindings = [])
 * @method static int update(string $sql, array $bindings = [])
 * @method static int delete(string $sql, array $bindings = [])
 * @method static bool statement(string $sql, array $bindings = [])
 * @method static mixed transaction(callable $callback)
 * @method static void beginTransaction()
 * @method static void commit()
 * @method static void rollBack()
 * @method static \Illuminate\Database\Connection getConnection(string $name = 'default')
 *
 * @see Database
 */
class DB extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Database::class;
    }
}
