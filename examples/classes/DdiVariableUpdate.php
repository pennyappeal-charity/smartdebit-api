<?php

namespace PennyAppeal\SmartDebitExample;

use Exception;

trait DdiVariableUpdate
{
    protected function ddiVariableUpdateUsage()
    {
        throw new Exception("\nUsage: php ddiVariableUpdate.php [--ref <reference number>] "
            . "[--amount <pence>]\n");
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getDdiVariableUpdateArgs()
    {
        $args = [
            'ref' => null,
            'amount' => null,
        ];

        $argIndex = 1;
        while ($argIndex < $this->argc) {
            $option = $this->argv[$argIndex];
            $argIndex++;
            switch ($option) {
                case '--ref':
                    $args['ref'] = $this->argv[$argIndex];
                    $argIndex++;
                    break;
                case '--amount':
                    $args['amount'] = $this->argv[$argIndex];
                    $argIndex++;
                    break;
                default:
                    $this->ddiVariableUpdateUsage();
            }
        }
        if (is_null($args['ref']) || is_null($args['amount'])) {
            $this->ddiVariableUpdateUsage();
        }
        return $args;
    }
}