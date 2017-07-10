<?php

namespace Despark\Tests\Cms;

use Despark\Cms\Providers\AdminServiceProvider;
use Despark\Cms\Providers\EntityServiceProvider;
use Despark\Cms\Providers\IgniServiceProvider;
use Despark\Cms\Providers\JavascriptServiceProvider;
use GrahamCampbell\TestBench\AbstractPackageTestCase;
use ReflectionClass;

/**
 * Class AbstractTestCase.
 */
abstract class AbstractTestCase extends AbstractPackageTestCase
{
    /**
     * @var
     */
    protected $migrationPath;


    /**
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return array
     */
    protected function getRequiredServiceProviders($app)
    {
        return [
            AdminServiceProvider::class,
            EntityServiceProvider::class,
            IgniServiceProvider::class,
            JavascriptServiceProvider::class,
        ];
    }

    /**
     * Sets a protected property on a given object via reflection.
     *
     * @param $object - instance in which protected value is being modified
     * @param $property - property on instance being modified
     * @param $value - new value of the property being modified
     *
     * @return void
     */
    public function setProtectedProperty($object, $property, $value)
    {
        $reflection = new ReflectionClass($object);
        $reflection_property = $reflection->getProperty($property);
        $reflection_property->setAccessible(true);
        $reflection_property->setValue($object, $value);
    }





    /*
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    //    protected function getEnvironmentSetUp($app)
    //    {
    //
    //       parent::getEnvironmentSetUp($)
    //    }
}
