<?php

namespace Tests\Feature;

use Illuminate\Database\Connection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use ProcessMaker\Models\Comment;
use ProcessMaker\Models\ProcessRequest;
use Tests\Feature\Shared\RequestHelper;
use Tests\TestCase;

/**
 * Test edit data
 */
class ExternalConnectionTest extends TestCase
{
    use RequestHelper;

    /**
     * Test external connection
     */
    public function testVerifyExternalConnection()
    {
        if (!config('database.enable_external_connection')) {
            $this->markTestSkipped('ENABLE_EXTERNAL_CONNECTION is not enabled');
        }
        // Test the DATA connection is valid
        $connection = DB::connection('data');
        $this->assertInstanceOf(Connection::class, $connection);

        // Check that the table process_requests exists in the DATA connection
        $collection = $connection->table('process_requests')->get();
        $this->assertInstanceOf(Collection::class, $collection);

        // Check that the table comments exists in the DATA connection
        $collection = $connection->table('comments')->get();
        $this->assertInstanceOf(Collection::class, $collection);

        // Check the ProcessRequest model uses the DATA connection
        $processRequest = new ProcessRequest();
        $this->assertEquals($connection, $processRequest->getConnection());

        // Check the Comment model uses the DATA connection
        $comment = new Comment();
        $this->assertEquals($connection, $comment->getConnection());
    }
}
