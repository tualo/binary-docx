<?php

namespace Tualo\Office\BinaryDocx\Checks;

use Tualo\Office\Basic\Middleware\Session;
use Tualo\Office\Basic\PostCheck;
use Tualo\Office\Basic\TualoApplication as App;


class Tables  extends PostCheck
{

    public static function test(array $config)
    {
        $clientdb = App::get('clientDB');
        if (is_null($clientdb)) return;
        $tables = [
            /*
            'fibufiles_typen'=>[],
            'fibufiles'=>[],
            */];
        self::tableCheck(
            'binary_docx',
            $tables,
            "please run the following command: `./tm install-sql-binary-docx --client " . $clientdb->dbname . "`",
            "please run the following command: `./tm install-sql-binary-docx --client " . $clientdb->dbname . "`"

        );
    }
}
