<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;


class PageControllerTest extends WebTestCase
{
    public function testDashboard()
    {
        $client = self::createClient();
        $client->request('GET', '/');

//        $this->assertTrue($client->getResponse()->isSuccessful());
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//        $this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')->text());
    }
}
