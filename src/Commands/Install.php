<?php

/*
namespace Tualo\Office\BinaryDocx\Commands;

use Garden\Cli\Cli;
use Garden\Cli\Args;
use phpseclib3\Math\BigInteger\Engines\PHP;
use Tualo\Office\Basic\ICommandline;
use Tualo\Office\ExtJSCompiler\Helper;
use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\PostCheck;

class InstallMainSQLCommandline implements ICommandline
{

    public static function getCommandName(): string
    {
        return 'install-sql-fibuconv';
    }

    public static function setup(Cli $cli)
    {
        $cli->command(self::getCommandName())
            ->description('installs needed sql for fibuconv module')
            ->opt('client', 'only use this client', true, 'string');
    }


    public static function setupClients(string $msg, string $clientName, string $file, callable $callback)
    {
        $_SERVER['REQUEST_URI'] = '';
        $_SERVER['REQUEST_METHOD'] = 'none';
        App::run();

        $session = App::get('session');
        $sessiondb = $session->db;
        $dbs = $sessiondb->direct('select username db_user, password db_pass, id db_name, host db_host, port db_port from macc_clients ');
        foreach ($dbs as $db) {
            if (($clientName != '') && ($clientName != $db['db_name'])) {
                continue;
            } else {
                App::set('clientDB', $session->newDBByRow($db));
                PostCheck::formatPrint(['blue'], $msg . '(' . $db['db_name'] . '):  ');
                $callback($file);
                PostCheck::formatPrintLn(['green'], "\t" . ' done');
            }
        }
    }

    public static function run(Args $args)
    {

        $files = [
            'install/fibufiles_typen'    => 'setup fibufiles_typen',
            'install/fibufiles_typen.ds'    => 'setup fibufiles_typen.ds',

            'install/fibufiles'    => 'setup fibufiles',
            'install/fibufiles.ds'    => 'setup fibufiles.ds',


            'install/proc.xsplit'    => 'setup xsplit',
            'install/proc.split_str_into_table'    => 'setup split_str_into_table',
            'install/proc.csv_to_table'    => 'setup csv_to_table',
            'install/proc.detect_fibufile_types'    => 'setup detect_fibufile_types',


            'install/marketplaces'    => 'setup marketplaces',
            'install/marketplaces.ds'    => 'setup marketplaces.ds',


            // immer zum schluss
            'install/ds_fill'    => 'refreshing ds data',

        ];


        foreach ($files as $file => $msg) {
            $installSQL = function (string $file) {

                $sinlgeStatements = App::get('clientDB')->explode_by_delimiter($sql);
                foreach ($sinlgeStatements as $commandIndex => $statement) {
                    try {
                        App::get('clientDB')->execute($statement);
                        App::get('clientDB')->moreResults();
                    } catch (\Exception $e) {
                        echo PHP_EOL;
                        PostCheck::formatPrintLn(['red'], $e->getMessage() . ': commandIndex => ' . $commandIndex);
                    }
                }
            };
            $clientName = $args->getOpt('client');
            if (is_null($clientName)) $clientName = '';
            self::setupClients($msg, $clientName, $file, $installSQL);
        }
    }
}
*/