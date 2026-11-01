<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Override;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    #[Override]
    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("DELETE FROM addresses");
        DB::delete("DELETE FROM contacts");
        DB::delete("DELETE FROM users");
    }
}
