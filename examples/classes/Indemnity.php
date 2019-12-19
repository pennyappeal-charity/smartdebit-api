<?php

namespace PennyAppeal\SmartDebitExample;

use Exception;

trait Indemnity
{
    /**
     * @throws Exception
     */
    protected function indemnityUsage()
    {
        throw new Exception("\nUsage: php <command> <id>\n");
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getImportIdArg() : int
    {
        if ($this->argc != 2) {
            $this->indemnityUsage();
        }
        return (int)$this->argv[1];
    }
}
