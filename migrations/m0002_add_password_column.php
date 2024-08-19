<?php

use Gator\Core\Database\Burt;

class m0002_add_password_column
{
    public function up(): void
    {
        Burt::table('users')
            ->string('password')->notNull()
            ->modify();
    }

    public function down(): void
    {
        Burt::table('users')->dropColumn('password');
    }
}