<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageControllerTest extends WebTestCase
{
    public function testDashboard()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//        $this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')->text());
    }
}
