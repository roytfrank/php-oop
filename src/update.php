<?php declare(strict_types = 1);

require_once __DIR__.'/../vendor/autoload.php';

use App\Entity\BugReport;
use App\Repository\BugReportRepository;
use App\Helpers\QueryBuilderFactory;
use App\Exception\BadRequestException;
use App\Logger\Logger;

if(isset($_POST, $_POST['update'])){
    
    $logger = new Logger;

    try{
    $queryBuilder = QueryBuilderFactory::make();
    $repository = new BugReportRepository($queryBuilder);

    //find the bug report with the id posted
    $theBugReport = $repository->find((int)$_POST['id']);
    $message = $_POST['message'];
    $email= $_POST['email'];
    $link = $_POST['link'];

    $theBugReport->setEmail($email);
    $theBugReport->setLink($link);
    $theBugReport->setMessage($message);

    $updatedReport = $repository->update($theBugReport);

    }catch(\Throwable $exception){
      $logger->critical($exception->getMessage(), ["exception" => $exception, "post" => $_POST]);
      throw new BadRequestException($exception->getMessage(), ["exception" => $exception], 400);

    }

    $logger->info("Bug report updated", ['id', $updatedReport->getId(), 'type' => $updatedReport->getReportType()]);
    //$bugReport = $repository->findAll();

}

?>
