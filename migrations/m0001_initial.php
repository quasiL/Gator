<?php

use Gator\Core\Database\Burt;

class m0001_initial
{
    public function up(): void
    {
        Burt::createAuthTables();
    }

    public function down(): void
    {
        Burt::table('users')->drop();
        Burt::table('users_2fa')->drop();
        Burt::table('users_confirmations')->drop();
        Burt::table('users_otps')->drop();
        Burt::table('users_remembered')->drop();
        Burt::table('users_resets')->drop();
        Burt::table('users_throttling')->drop();
    }
}