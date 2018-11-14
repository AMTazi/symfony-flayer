<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FlayerControllerTest extends WebTestCase
{
    public function testSomething()
    {

    	$this->assertTrue( self::$container->get( 'App\Service\FlayerService')->storeFlayers());
    }
}
