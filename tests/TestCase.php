<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Tidak perlu manifest Vite saat menjalankan test (belum di-build).
        $this->withoutVite();
    }
}
