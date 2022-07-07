<?php 

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Log Count API Test
 */
class LogsControllerTest extends WebTestCase
{
    /**
     * Test count endpoint
     *
     * @return void
     */
    public function testCount(): void
    {
        //Create client
        $client = static::createClient();
        //Call get request
        $client->request('GET', '/count');
        //Getting response
        $response = $client->getResponse();

        //Validate response
        $this->assertSame(200, $response->getStatusCode());        
        $this->assertStringContainsString('count', $response->getContent());        
    }
}