<?php

namespace Classes\Resource;

use Despark\Cms\Admin\Form;
use Despark\Cms\Admin\FormBuilder;
use Despark\Cms\Http\Controllers\EntityController;
use Despark\Cms\Resource\EntityManager;
use Despark\Tests\Cms\AbstractTestCase;
use resources\TestController;
use resources\TestModel;
use resources\TestModelWithTranslations;

class EntityManagerTest extends AbstractTestCase
{
    public function testGetForm()
    {
        // With translations
        $modelMock = \Mockery::mock(TestModelWithTranslations::class)->makePartial();

        $modelMock->shouldReceive('getTranslation')->andReturn('test_translations');

        /** @var EntityManager|\Mockery\Mock $entityManagerMock */
        $entityManagerMock = \Mockery::mock(EntityManager::class, [new FormBuilder(), new Form()])
                                     ->makePartial();

        $fields = [
            'test' => [
                'type' => 'text',
                'label' => 'Test',
            ],
        ];

        $config = [
            'test' => [
                'id' => 'test',
                'actions' => ['store'],
                'model' => get_class($modelMock),
                'adminFormFields' => $fields,
            ],
        ];

        $entityManagerMock->setResources($config);

        $entityManagerMock->shouldReceive('getFormAction')
            ->andReturn('http://example.com');

        $modelMock->translatable = [
            'test',
        ];

        $form = $entityManagerMock->getForm($modelMock);

        $this->assertNotEmpty($form);

        // Without translations
        $modelMock = \Mockery::mock(TestModel::class)->makePartial();

        /** @var EntityManager|\Mockery\Mock $entityManagerMock */
        $entityManagerMock = \Mockery::mock(EntityManager::class, [new FormBuilder(), new Form()])
                                     ->makePartial();

        $fields = [
            'test' => [
                'type' => 'text',
                'label' => 'Test',
            ],
        ];

        $config = [
            'test' => [
                'id' => 'test',
                'actions' => ['store'],
                'model' => get_class($modelMock),
                'adminFormFields' => $fields,
            ],
        ];

        $entityManagerMock->setResources($config);

        $entityManagerMock->shouldReceive('getFormAction')
            ->andReturn('http://example.com');

        $form = $entityManagerMock->getForm($modelMock);

        $this->assertNotEmpty($form);
    }

    public function testGetByModel()
    {
        $testId = [
            'model' => TestModel::class,
        ];

        $testId2 = [
            'model' => TestModel::class,
            'default' => true,
        ];

        $testModel = new TestModel();

        /** @var EntityManager|\Mockery\Mock $mock */
        $mock = \Mockery::mock(EntityManager::class)->makePartial();

        $mock->shouldReceive('getById')
             ->andReturn($testId);

        $resource = $mock->getByModel($testModel, 'test_id');

        $this->assertEquals($testId, $resource);

        $mock->shouldReceive('all')
             ->andReturn([
                 'test_id' => $testId,
                 'test_id_2' => $testId2,
             ]);

        $resource = $mock->getByModel($testModel);

        $this->assertEquals($testId2, $resource);

        $mock = \Mockery::mock(EntityManager::class)->makePartial();
        $mock->shouldReceive('all')
             ->andReturn([
                 'test_id' => $testId,
             ]);

        $resource = $mock->getByModel($testModel);

        $this->assertEquals($testId, $resource);
    }

    public function testGetByController()
    {
        $testController = new TestController();

        $testId = [
            'controller' => TestController::class,
        ];

        $testId2 = [
            'controller' => TestController::class,
            'default' => true,
        ];

        /** @var EntityManager|\Mockery\Mock $mock */
        $mock = \Mockery::mock(EntityManager::class)->makePartial();

        $mock->shouldReceive('all')
             ->andReturn([
                 'test_id' => $testId,
                 'test_id_2' => $testId2,
             ]);

        $resource = $mock->getByController($testController);

        $this->assertEquals($testId, $resource);

        $testController = new EntityController($mock);

        $testId = [
            'controller' => EntityController::class,
        ];

        $testId2 = [
            'controller' => EntityController::class,
            'default' => true,
        ];

        $mock->shouldReceive('getByRoute')
             ->andReturn($testId);

        $resource = $mock->getByController($testController);

        $this->assertEquals($testId, $resource);
    }

    public function testGetRouteName()
    {
        $routes = [
            'create' => 'testmodel.create',
            'store' => 'testmodel.store',
            'index' => 'testmodel.index',
        ];

        $testModel = new TestModel();

        /** @var EntityManager|\Mockery\Mock $mock */
        $mock = \Mockery::mock(EntityManager::class)->makePartial();

        $mock->shouldReceive('getModelRoutes')
             ->andReturn($routes);

        $action = $mock->getRouteName($testModel, 'create');

        $this->assertEquals('testmodel.create', $action);
    }

    public function testGetModelRoutes()
    {
        $testId = [
            'model' => TestModel::class,
            'actions' => ['create'],
            'id' => 'testmodel',
        ];

        $routes = [
            'testmodel' => [
                'create' => 'testmodel.create',
                'store' => 'testmodel.store',
                'index' => 'testmodel.index',
            ],
        ];

        $shouldSee = [
            'create' => 'testmodel.create',
            'store' => 'testmodel.store',
            'index' => 'testmodel.index',
        ];

        $testModel = new TestModel();

        /** @var EntityManager|\Mockery\Mock $mock */
        $mock = \Mockery::mock(EntityManager::class)->makePartial();

        $mock->shouldReceive('getByModel')
             ->andReturn($testId);

        $mock->shouldReceive('getRoutes')
             ->andReturn($routes);

        $modelRoutes = $mock->getModelRoutes($testModel, null);

        $this->assertEquals($shouldSee, $modelRoutes);
    }

    public function testRenderField()
    {
        $testModel = new TestModel();

        $fields = [
            'test_1' => [
                'type' => 'text',
                'label' => 'Test 1',
            ],
            'test_2' => [
                'type' => 'textarea',
                'label' => 'Test 2',
            ],
        ];

        /** @var EntityManager|\Mockery\Mock $entityManagerMock */
        $mock = \Mockery::mock(EntityManager::class, [new FormBuilder(), new Form()])
                                     ->makePartial();

        $mock->shouldReceive('getFields')
            ->andReturn($fields);

        $field = $mock->renderField($testModel, 'test_2');

        $this->assertNotEmpty($field);

        $field = $mock->renderField($testModel, 'test_3');

        $this->assertEmpty($field);
    }

    public function testGetFieldsThrowsException()
    {
        $this->expectException(\Exception::class);

        $testModel = new TestModel();

        /** @var EntityManager|\Mockery\Mock $entityManagerMock */
        $mock = \Mockery::mock(EntityManager::class, [new FormBuilder(), new Form()])
                                     ->makePartial();

        $mock->shouldReceive('findResourceConfig')
            ->andReturn(null);

        $mock->getFields($testModel);
    }

    public function testGetFieldsReturnsNothing()
    {
        $testModel = new TestModel();

        /** @var EntityManager|\Mockery\Mock $entityManagerMock */
        $mock = \Mockery::mock(EntityManager::class, [new FormBuilder(), new Form()])
                                     ->makePartial();

        $config = [
            'test' => [
                'id' => 'testmodel',
                'actions' => ['store'],
                'model' => get_class($testModel),
            ],
        ];

        $mock->shouldReceive('findResourceConfig')
            ->andReturn($config);

        $fields = $mock->getFields($testModel);

        $this->assertEmpty($fields);
    }

    public function testFindResourceConfig()
    {
        $testModel = new TestModel();

        /** @var EntityManager|\Mockery\Mock $entityManagerMock */
        $mock = \Mockery::mock(EntityManager::class, [new FormBuilder(), new Form()])
                                     ->makePartial();

        $config = [
            'test' => [
                'id' => 'testmodel',
                'actions' => ['store'],
                'model' => get_class($testModel),
            ],
        ];

        $mock->shouldReceive('getById')
            ->andReturn($config);

        $resourceConfig = $mock->findResourceConfig($testModel, 'testmodel');

        $this->assertNotEmpty($resourceConfig);
    }
}
