<?php

namespace PennyAppeal\SmartDebitExample;

use DateTime;
use Exception;

trait GetSuccessfulCollectionReport
{
    /**
     * @throws \Exception
     */
    protected function getSuccessfulCollectionReportUsage()
    {
        throw new Exception("Usage: \"php getSuccessfulCollectionReport.php <date>\"\n");
    }

    /**
     * @return DateTime
     * @throws Exception
     */
    public function getDateArg() : DateTime
    {
        if ($this->argc == 2 && ($date = date_create($this->argv[1]))) {
            return $date;
        }
        $this->getSuccessfulCollectionReportUsage();
    }
}