<?php

use app\src\DB;

class m0001_initial
{
	public function up(): void
	{
		DB::table('users')
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
		DB::table('users')->drop();
	}
}