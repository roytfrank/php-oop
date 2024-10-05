<?php

namespace Tests\Units;
use PHPUnit\Framework\TestCase;
use App\Helpers\QueryBuilderFactory;

class QueryBuilderTest extends TestCase{

    private $queryBuilder;

    public function setUp(){
         $this->queryBuilder = QueryBuilderFactory::make("database", "pdo", 
                                 ['dbname' => 'bug_app_testing']);
         $this->queryBuilder->beginTransaction();
         parent::setUp();
    }

    public function insertIntoTable(){
      $data = ['report_type' => 'Test report 1',
                  'message' => 'create test report type',
                  'link' => 'https://testlink.com',
                  'email' => 'test@email.com',
                  'created_at' => date('Y-m-d H:i:s')];
        $result = $this->queryBuilder->table('reports')->create($data);
        return $id = $this->queryBuilder->lastInsertId();
    }

    public function testItCanCreateRecord(){
        $id = $this->insertIntoTable();
        self::assertNotNull($id);
        return $id;
    }

    public function testItCanPerformRawQuery(){
      $result = $this->queryBuilder->raw("SELECT * FROM reports")->get();
       self::assertNotNull($result); 
  }

    public function testSelectQuery(){
      $id = $this->insertIntoTable();
      $result = $this->queryBuilder->table('reports')->select('*')->where('id', $id)->first();
       self::assertNotNull($result);
    }

    public function testItCanPerformSelectQuery(){
         $id = $this->insertIntoTable();
        $result = $this->queryBuilder->table('reports')->select("*")->where('id',  $id)->first();
        self::assertSame($id, $result->id);
    }

    public function testItCanPerformMultipleSelectQuery(){
         $id = $this->insertIntoTable();
         $result = $this->queryBuilder->table('reports')->select("*")->where('report_type', 'Test report 1')->where('id',  $id)->first();
         self::assertSame($id, $result->id);
    }

    public function testItCanFindById(){
        $id = $this->insertIntoTable();
        $result = $this->queryBuilder->table('reports')->find($id);
        self::assertSame($id, $result->id);
    }

    public function testItCanFindOneByGivenValue(){
      $id = $this->insertIntoTable();
      $result = $this->queryBuilder->table('reports')->findOneBy('report_type', 'Test report 1');
      self::assertNotNull($result);
  }

    public function testItCanUpdateRecord(){
      $id = $this->insertIntoTable();
      $result = $this->queryBuilder->table('reports')
                      ->update(['report_type' => 'Test report 1 updated'])
                      ->where('id', $id);
      self::assertNotNull($result);
    }

    public function testItCanDeleteRecord(){
      $id = $this->insertIntoTable();
      $result = $this->queryBuilder->table('reports')
                      ->delete(['report_type' => 'Test report 1 updated'])->where('id', $id);
     
      $result = $this->queryBuilder->table('reports')->find($id);
      self::assertNull($result);

    }

    public function tearDown(){
      $this->queryBuilder->rollback();
      parent::tearDown();
    }

}


?>