<?php

namespace Spatie\MediaLibrary\Test\Conversion;

use Illuminate\Support\Facades\Artisan;
use Spatie\MediaLibrary\Test\TestCase;
use Spatie\MediaLibrary\Test\TestModel;

class ClearCommandTest extends TestCase
{
    /** @var array */
    protected $media;

    public function setUp()
    {
        parent::setUp();

        $this->media['model1']['collection1'] = $this->testModel
            ->addMedia($this->getTestJpg())
            ->preservingOriginal()
            ->toCollection('collection1');

        $this->media['model1']['collection2'] = $this->testModel
            ->addMedia($this->getTestJpg())
            ->preservingOriginal()
            ->toCollection('collection2');

        $this->media['model2']['collection1'] = $this->testModelWithConversion
            ->addMedia($this->getTestJpg())
            ->preservingOriginal()
            ->toCollection('collection1');

        $this->media['model2']['collection2'] = $this->testModelWithConversion
            ->addMedia($this->getTestJpg())
            ->preservingOriginal()
            ->toCollection('collection2');

        $this->assertFileExists($this->getMediaDirectory("{$this->media['model1']['collection1']->id}/test.jpg"));
        $this->assertFileExists($this->getMediaDirectory("{$this->media['model1']['collection2']->id}/test.jpg"));
        $this->assertFileExists($this->getMediaDirectory("{$this->media['model2']['collection1']->id}/test.jpg"));
        $this->assertFileExists($this->getMediaDirectory("{$this->media['model2']['collection2']->id}/test.jpg"));
    }

    /** @test */
    public function it_can_clear_all_media()
    {
        Artisan::call('medialibrary:clear');

        $this->assertFileNotExists($this->getMediaDirectory("{$this->media['model1']['collection1']->id}/test.jpg"));
        $this->assertFileNotExists($this->getMediaDirectory("{$this->media['model1']['collection2']->id}/test.jpg"));

        $this->assertFileNotExists($this->getMediaDirectory("{$this->media['model2']['collection1']->id}/test.jpg"));
        $this->assertFileNotExists($this->getMediaDirectory("{$this->media['model2']['collection2']->id}/test.jpg"));
    }

    /** @test */
    public function it_can_clear_media_from_a_specific_model_type()
    {
        Artisan::call('medialibrary:clear', [
            'modelType' => TestModel::class,
        ]);

        $this->assertFileNotExists($this->getMediaDirectory("{$this->media['model1']['collection1']->id}/test.jpg"));
        $this->assertFileNotExists($this->getMediaDirectory("{$this->media['model1']['collection2']->id}/test.jpg"));

        $this->assertFileExists($this->getMediaDirectory("{$this->media['model2']['collection1']->id}/test.jpg"));
        $this->assertFileExists($this->getMediaDirectory("{$this->media['model2']['collection2']->id}/test.jpg"));
    }

    /** @test */
    public function it_can_clear_media_from_a_specific_collection()
    {
        Artisan::call('medialibrary:clear', [
            'collectionName' => 'collection2',
        ]);

        $this->assertFileExists($this->getMediaDirectory("{$this->media['model1']['collection1']->id}/test.jpg"));
        $this->assertFileNotExists($this->getMediaDirectory("{$this->media['model1']['collection2']->id}/test.jpg"));

        $this->assertFileExists($this->getMediaDirectory("{$this->media['model2']['collection1']->id}/test.jpg"));
        $this->assertFileNotExists($this->getMediaDirectory("{$this->media['model2']['collection2']->id}/test.jpg"));
    }

    /** @test */
    public function it_can_clear_media_from_a_specific_model_type_and_collection()
    {
        Artisan::call('medialibrary:clear', [
            'modelType' => TestModel::class,
            'collectionName' => 'collection2',
        ]);

        $this->assertFileExists($this->getMediaDirectory("{$this->media['model1']['collection1']->id}/test.jpg"));
        $this->assertFileNotExists($this->getMediaDirectory("{$this->media['model1']['collection2']->id}/test.jpg"));

        $this->assertFileExists($this->getMediaDirectory("{$this->media['model2']['collection1']->id}/test.jpg"));
        $this->assertFileExists($this->getMediaDirectory("{$this->media['model2']['collection2']->id}/test.jpg"));
    }
}
