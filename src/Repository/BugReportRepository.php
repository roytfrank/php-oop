<?php

namespace App\Repository;
use App\Entity\BugReport;

class BugReportRepository extends Repository{

    protected static $table = "reports";
    protected static $className = BugReport::class;

}


?>