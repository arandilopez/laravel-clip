<?php

namespace Clip\Tests;

use PHPUnit\Framework\TestCase;
use Mockery as m;
use Clip\Clip;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Clip\Model\Attachment;

class ClipTest extends TestCase
{
    public function test_can_attach_from_uploaded_file()
    {
        Storage::fake('test');
        $uploadedFile = UploadedFile::fake()->create('document.pdf', 3000);
        $clip = new Clip();
        $attachment = $clip->attachFromUploadedFile($uploadedFile);
        $this->assertInstanceOf(Attachment, $attachment);
    }

    public function setUp()
    {
        parent::setUp();
        $this->setUpDb();
    }
    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }

    /**
     * Configure a new sqlite db
     */
    protected function setUpDb()
    {
        $db = new DB;
        $db->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:'
        ]);
        $db->bootEloquent();
        $db->setAsGlobal();
    }

    /**
     * Get a database connection instance.
     *
     * @return Illuminate\Database\Connection
     */
    protected function connection()
    {
        return Eloquent::getConnectionResolver()->connection();
    }
    /**
     * Get a schema builder instance.
     *
     * @return Illuminate\Database\Schema\Builder
     */
    protected function schema()
    {
        return $this->connection()->getSchemaBuilder();
    }
}
