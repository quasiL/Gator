<?php

use app\src\DB;

class m0002_add_password_column {
	public function up(): void
	{
		DB::table('users')
			->string('password')->notNull()
			->modify();
	}

	public function down(): void
	{
		DB::table('users')->dropColumn('password');
	}
}