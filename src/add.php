<?php declare(strict_types = 1);

require_once __DIR__.'/../vendor/autoload.php';

use App\Entity\BugReport;
use App\Repository\BugReportRepository;
use App\Helpers\QueryBuilderFactory;
use App\Exception\BadRequestException;
use App\Logger\Logger;

if(isset($_POST, $_POST['add'])){

    $respotType = $_POST['report_type'];
    $message = $_POST['message'];
    $email= $_POST['email'];
    $link = $_POST['link'];

    $bugReport = new BugReport;
    $bugReport->setReportType($respotType);
    $bugReport->setEmail($email);
    $bugReport->setLink($link);
    $bugReport->setMessage($message);
  
    
    $logger = new Logger;

    try{
    $queryBuilder = QueryBuilderFactory::make();
    $repository = new BugReportRepository($queryBuilder);

    $newReport = $repository->create($bugReport);

    }catch(\Throwable $exception){
      $logger->critical($exception->getMessage(), ["exception" => $exception, "post" => $_POST]);
      throw new BadRequestException($exception->getMessage(), ["exception" => $exception], 400);

    }

    $logger->info("New bug report created", ['id', $newReport->getId(), 'type' => $newReport->getReportType()]);
    $bugReport = $repository->findAll();
    

}








?>