<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Database;
use Tests\TestCase;

class DatabaseFixTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->markTestSkipped('Database test requires MySQL connection, cannot run in :memory: with hardcoded mysql PDO driver.');
    }

    public function test_insert_returns_last_insert_id_correctly()
    {
        $id1 = Database::insert('INSERT INTO users (name, email) VALUES (?, ?)', ['Ali', 'ali@example.com']);
        $id2 = Database::insert('INSERT INTO users (name, email) VALUES (?, ?)', ['Veli', 'veli@example.com']);

        $this->assertIsNumeric($id1);
        $this->assertIsNumeric($id2);
        $this->assertGreaterThan(0, $id1);
        $this->assertGreaterThan($id1, $id2);

        $user1 = Database::selectOne('SELECT * FROM users WHERE id = ?', [$id1]);
        $this->assertEquals('Ali', $user1->name);
    }
}
