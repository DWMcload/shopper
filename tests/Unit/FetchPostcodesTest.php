<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class FetchPostcodesTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_if_missing_url_argument(): void
    {
        $this->artisan('app:fetch-postcodes', ['url' => ''])->assertExitCode(1);
        Log::shouldReceive('error')
            ->withSomeOfArgs('URL rejected: Malformed input to a URL function');
    }

    public function test_if_wrong_url_argument(): void
    {
        $this->artisan('app:fetch-postcodes', ['url' => 'abcdefghijklm'])->assertExitCode(1);
        Log::shouldReceive('error')
            ->withSomeOfArgs('Could not resolve host');
    }

    public function test_if_command_succeeds(): void
    {
        $this->artisan('app:fetch-postcodes', ['url' => 'http://shopper.test/test_sql.zip'])->assertExitCode(0);
    }
}
