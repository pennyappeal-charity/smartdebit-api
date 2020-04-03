<?php

namespace PennyAppeal\SmartDebitExample;

use Exception;

trait BacsList
{
    protected function bacsListUsage()
    {
        throw new Exception("\nUsage: php indemnityList.php [--max_results <max>] [--start_index <index>] "
            . "[--id_from <id>] [--from_date <from> --till_date <till>]\n");
    }

    public function getBacsListArgs()
    {
        $args = [
            'maxResults' => null,
            'startIndex' => null,
            'idFrom' => null,
            'fromDate' => null,
            'tillDate' => null,
        ];

        $argIndex = 1;
        while ($argIndex < $this->argc) {
            $option = $this->argv[$argIndex];
            $argIndex++;
            switch ($option) {
                case '--max_results':
                    $args['maxResults'] = $this->argv[$argIndex];
                    $argIndex++;
                    break;
                case '--start_index':
                    $args['startIndex'] = $this->argv[$argIndex];
                    $argIndex++;
                    break;
                case '--id_from':
                    $args['idFrom'] = $this->argv[$argIndex];
                    $argIndex++;
                    break;
                case '--from_date':
                    $args['fromDate'] = new DateTime($this->argv[$argIndex]);
                    $argIndex++;
                    break;
                case '--till_date':
                    $args['tillDate'] = new DateTime($this->argv[$argIndex]);
                    $argIndex++;
                    break;
                default:
                    $this->bacsListUsage();
            }
        }
        return $args;
    }
}
