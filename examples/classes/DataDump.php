<?php

namespace PennyAppeal\SmartDebitExample;

use Exception;

trait DataDump
{
    protected $dataDumpFormats = ['XML', 'CSV'];

    /**
     * @throws Exception
     */
    protected function dataDumpUsage()
    {
        $allowedFormats = implode('|', $this->dataDumpFormats);
        throw new Exception("\nUsage: php dataDump.php [-r <payer_reference>] [-f {$allowedFormats}]\n");
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getDataDumpArgs()
    {
        $args = [
            'payerReference' => null,
            'format' => $this->dataDumpFormats[0],
        ];

        $argIndex = 1;
        while ($argIndex < $this->argc) {
            switch ($this->argv[$argIndex]) {
                case '-r':
                    $argIndex++;
                    $args['payerReference'] = $this->argv[$argIndex];
                    $argIndex++;
                    break;
                case '-f':
                    $argIndex++;
                    $format = $this->argv[$argIndex];
                    if (in_array($format, $this->dataDumpFormats)) {
                        $args['format'] = $format;
                    } else {
                        $this->dataDumpUsage();
                    }
                    $argIndex++;
                    break;
                default:
                    $this->dataDumpUsage();
            }
        }
        return $args;
    }
}
