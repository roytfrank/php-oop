<?php declare(strict_types = 1);

namespace Tests\Units;
use PHPUnit\Framework\TestCase;
use App\Helpers\QueryBuilderFactory;
use App\Repository\BugReportRepository;
use App\Entity\BugReport;

class RepositoryTest extends TestCase{
    //test we can insert record in database by creating instance 
    //of entity and passing instance to the repository

    private $queryBuilder;
    private $bugReportRepository;
    private $bugReport;

    public function setUp(){
         $this->queryBuilder = QueryBuilderFactory::make("database", "pdo", 
                                 ['dbname' => 'bug_app_testing']);

    $this->bugReport = new BugReport();

    //if we want to mock, we can pass in a mock querybuilder.
    $this->bugReportRepository = new BugReportRepository($this->queryBuilder);
    //in some cases we may want to think if we want to pass a query builder as a dependecy to this
    //repository.This is give us the flexibility to have another kind of query builder. If we want
    //to use a query builder that doesnt really talk to the database, we cam pass that in.
    //We can get the querybuilder instance an in the repository we do queries and other operations.


         $this->queryBuilder->beginTransaction();
         parent::setUp();
    }


    public function testItCanCreateRecordWithEntity()
    {

        $this->bugReport->setReportType("Type 2")->setLink("https://test.com")
                   ->setMessage("This is a dummy message")->setEmail("email@mail.com");
                   //created_at will be within the entity

        $newBugReport = $this->bugReportRepository->create($this->bugReport);
        self::assertInstanceOf(BugReport::class, $newBugReport);
        self::assertNotNull($newBugReport->getId());
        self::assertSame('Type 2', $newBugReport->getReportType());
        self::assertSame('This is a dummy message', $newBugReport->getMessage());
        self::assertSame('email@mail.com', $newBugReport->getEmail());


    }

    public function testItCanUpdateAGivenEntity(){

         $newBugReport = $this->createBugReport();

         $bugReport = $this->bugReportRepository->find($newBugReport->getId());
         $bugReport->setMessage("This is an updated message")->setLink("https://newlink.com");

         $updatedReport = $this->bugReportRepository->update($bugReport);
         self::assertSame('This is an updated message',  $updatedReport->getMessage());
         self::assertSame('https://newlink.com', $updatedReport->getLink());
    }

    public function testItCanDeleteAGivenEntity(){

        $newBugReport = $this->createBugReport();
         $this->bugReportRepository->delete($newBugReport);

        $bugReport = $this->bugReportRepository->find($newBugReport->getId());
        self::assertNull($bugReport);

    }

    public function testItCanFindByCriteria(){
        $newBugReport = $this->createBugReport();
        $report = $this->bugReportRepository->findBy([["email", "=", "email@mail.com"], ["report_type", "=", "Type 2"]]);

        self::assertIsArray($report);

        $bugReport = $report[0];
        self::assertSame("email@mail.com", $bugReport->getEmail());
        self::assertSame("This is a dummy message", $bugReport->getMessage());
    }

    public function createBugReport(): BugReport{

        $this->bugReport->setReportType("Type 2")->setLink("https://test.com")
                   ->setMessage("This is a dummy message")->setEmail("email@mail.com");
                   //created_at will be within the entity
        return $this->bugReportRepository->create($this->bugReport);

    }

    public function tearDown(){
        $this->queryBuilder->rollback();
        parent::tearDown();
      }


}


?>