<?php


namespace Classes\Resource;


use Despark\Cms\Admin\Form;
use Despark\Cms\Admin\FormBuilder;
use Despark\Cms\Resource\EntityManager;
use Despark\Tests\Cms\AbstractTestCase;
use resources\TestModel;

class EntityManagerTest extends AbstractTestCase
{
    /**
     * @group debug
     */
    public function testGetForm(){

        $modelMock = \Mockery::mock(TestModel::class)->makePartial();

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
                'adminFormFields' => $fields
            ]

        ];

        $entityManagerMock->setResources($config);

        $entityManagerMock->shouldReceive('getFormAction')
            ->andReturn('http://example.com');


        $modelMock->translatable = [
            'test'
        ];

       $form = $entityManagerMock->getForm($modelMock);

       $this->assertNotEmpty($form);

    }

    public function testGetByModel()
    {

        $testId = [
            'model' => TestModel::class,
        ];

        $testId2 = [
            'model'   => TestModel::class,
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
                 'test_id'   => $testId,
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

}