<?php  declare(strict_types = 1);

require_once __DIR__.'/../vendor/autoload.php';

use App\Database\QueryBuilder;
use App\Repository\BugReportRepository;
use App\Helpers\QueryBuilderFactory;

$queryBuilder = \App\Helpers\QueryBuilderFactory::make();
$repository = new BugReportRepository($queryBuilder);

$bugReports = $repository->findAll();

?>