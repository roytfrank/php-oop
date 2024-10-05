<?php declare(strict_types = 1);

require_once __DIR__.'/../vendor/autoload.php';

use App\Entity\BugReport;
use App\Repository\BugReportRepository;
use App\Helpers\QueryBuilderFactory;
use App\Exception\BadRequestException;
use App\Logger\Logger;

if(isset($_POST, $_POST['delete'])){
    
    $logger = new Logger;

    try{
    $queryBuilder = QueryBuilderFactory::make();
    $repository = new BugReportRepository($queryBuilder);

    //find the bug report with the id posted
    $theBugReport = $repository->find((int)$_POST['id']);
    $repository->delete($theBugReport);

    }catch(\Throwable $exception){
      $logger->critical($exception->getMessage(), ["exception" => $exception, "post" => $_POST]);
      throw new BadRequestException($exception->getMessage(), ["exception" => $exception], 400);

    }

    $logger->info("Bug report deleted", []);
    //$bugReport = $repository->findAll();

}

?>
