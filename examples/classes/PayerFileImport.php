<?php

namespace PennyAppeal\SmartDebitExample;

use Exception;

trait PayerFileImport
{
    protected function payerFileAsyncImportUsage()
    {
        throw new Exception("\nUsage: php payerFileAsyncImport -f <filename> -a <action>\n");
    }

    public function getPayerFileAsyncImportArgs()
    {
        $args = [];
        $argIndex = 1;
        while ($argIndex < $this->argc) {
            switch ($this->argv[$argIndex]) {
                case '-f':
                    $argIndex++;
                    $args['filename'] = $this->argv[$argIndex];
                    $argIndex++;
                    break;
                case '-a':
                    $argIndex++;
                    $args['action'] = $this->argv[$argIndex];
                    $argIndex++;
                    break;
                default:
                    $this->payerFileAsyncImportUsage();
            }
        }
        if (!(isset($args['filename']) && isset($args['action']))) {
            $this->payerFileAsyncImportUsage();
        }
        return $args;
    }

    protected function payerFileAsyncImportStatusUsage()
    {
        throw new Exception("\nUsage: php payerFileAsyncImportStatus --id <import id>\n");
    }

    public function getPayerFileAsyncImportStatusArgs()
    {
        $args = [];
        $argIndex = 1;
        while ($argIndex < $this->argc) {
            switch ($this->argv[$argIndex]) {
                case '--id':
                    $argIndex++;
                    $args['id'] = $this->argv[$argIndex];
                    $argIndex++;
                    break;
                default:
                    $this->payerFileAsyncImportStatusUsage();
            }
        }
        if (!isset($args['id'])) {
            $this->payerFileAsyncImportStatusUsage();
        }
        return $args;
    }
}
