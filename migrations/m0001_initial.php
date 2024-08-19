<?php

use Gator\Core\Database\Burt;

class m0001_initial
{
    public function up(): void
    {
        Burt::table('users')
            ->id()
            ->string('email')->notNull()
            ->string('firstname')->notNull()
            ->string('lastname')->notNull()
            ->int('status')
            ->timestamp('created_at')->default('CURRENT_TIMESTAMP')
            ->create();
    }

    public function down(): void
    {
        Burt::table('users')->drop();
    }
}