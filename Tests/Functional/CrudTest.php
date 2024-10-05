<?php  

namespace Tests\Functional;
use PHPUnit\Framework\TestCase;
use App\Helpers\QueryBuilderFactory;
use App\Repository\BugReportRepository;
use App\Entity\BugReport;
use App\Helpers\HttpClient;

class CrudTest extends TestCase{

    private $repository;
    private $queryBuilder;
    private $client;

    public function setUp(){   
        $this->queryBuilder = QueryBuilderFactory::make();
        $this->repository = new BugReportRepository($this->queryBuilder);

      // $this->queryBuilder->beginTransaction();
       $this->client = new HttpClient();
        parent::setUp();
    }

    public function testItCanCreateReportUsingPostRequest(){
          $postData = $this->getPostData(['add' => true]);
          $response = $this->client->post("http://localhost/oop/src/add.php", $postData);

          self::assertEquals(200, json_decode($response, true)['statusCode']);

          $result = $this->repository->findBy([
              ["report_type", "=", "audio bug"],
              ["email", "=", "testaudio@example.com"]
          ]);

        $bugReport = $result[0]?? [];

        self::assertInstanceOf(BugReport::class, $bugReport);
        self::assertSame("testaudio@example.com", $bugReport->getEmail());
        self::assertSame("https://example.com", $bugReport->getLink());

        return  $bugReport;
    }

    /** @depends testItCanCreateReportUsingPostRequest */
    public function testItCanUpdateReportUsingPostRequest(BugReport $bugReport){
        $postData = ['update' => true,
                    "message" => "The audio doesnt work good updated data",
                    "email" => "newupdatetestaudio@example.com",
                    "link" => "https://updateexample.com",
                    "id" => $bugReport->getId()
         ];

       $response =  $this->client->post("http://localhost/oop/src/update.php", $postData);
       self::assertEquals(200, json_decode($response, true)['statusCode']);

        $bugReport = $this->repository->find($bugReport->getId());

        self::assertInstanceOf(BugReport::class, $bugReport);
        self::assertSame("newupdatetestaudio@example.com", $bugReport->getEmail());
        self::assertSame("https://updateexample.com", $bugReport->getLink());

        return $bugReport;
   }

     /** @depends testItCanUpdateReportUsingPostRequest */
     public function testItCanDeleteReportUsingPostRequest(BugReport $bugReport){
        $postData = ['delete' => true,
                    "message" => "The audio doesnt work good updated data",
                    "email" => "newupdatetestaudio@example.com",
                    "link" => "https://updateexample.com",
                    "id" => $bugReport->getId()
         ];

        $response = $this->client->post("http://localhost/oop/src/delete.php", $postData);
        self::assertEquals(200, json_decode($response, true)['statusCode']);

        $bugReport = $this->repository->find($bugReport->getId());
        self::assertNull($bugReport);
   }

   public function testItCanReadReportUsingPostRequest(){
       $response = $this->client->get("http://localhost/oop/src/read.php");
       self::assertEquals(200, json_decode($response, true)['statusCode']);
   }

    private function getPostData(array $options = []): array{
       return array_merge([
           "report_type" => "audio bug",
           "message" => "The audio doesnt work good",
           "email" => "testaudio@example.com",
           "link" => "https://example.com"
       ], $options);
    }

    public function tearDown(){
     // $this->queryBuilder->rollback();
        parent::tearDown();
    }

}













?>