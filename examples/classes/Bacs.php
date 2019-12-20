<?php

namespace PennyAppeal\SmartDebitExample;

use Exception;

trait Bacs
{
    /**
     * @throws Exception
     */
    protected function bacsUsage()
    {
        throw new Exception("\nUsage: php <command> <id>\n");
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getBacsIdArg() : int
    {
        if ($this->argc != 2) {
            $this->bacsUsage();
        }
        return (int)$this->argv[1];
    }

    public function dumpFileElement(string $xmlData)
    {
        $xml = simplexml_load_string($xmlData);
        if (isset($xml->file)) {
            echo(
                "Base 64 decoded <file> element:\n"
                . base64_decode($xml->file)
                . "\n"
            );
        } else {
            echo("No <file> element found!\n");
        }
    }
}
