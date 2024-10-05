<?php

namespace Tests\Functional;
use App\Helpers\HttpClient;
use PHPUnit\Framework\TestCase;

class HomepageTest extends TestCase{

    public function testItCanVisitHomepageAndSeeRelevantData(){
        $client = new HttpClient;
       
        $response = $client->get("http://localhost/oop/index.php");
        $res = json_decode($response, true);
        self::assertEquals(200, $res["statusCode"]);
        self::assertStringContainsString("Report Type", $res["content"]);
    }

}