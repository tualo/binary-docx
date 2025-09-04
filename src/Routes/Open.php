<?php

namespace Tualo\Office\FibuConv\Routes;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\DS;

class Convert implements IRoute
{

    public static function register()
    {



        BasicRoute::add('/fibuconv/preconvert', function () {
            App::contenttype('application/json');
            $db = App::get('session')->getDB();
            if ($db == NULL) {
                App::result('msg', 'Nicht erlaubt');
            } else {
                $files = $db->direct("select * from view_readtable_fibufiles where typ='unkown'  and __file_type in ('text/plain', 'text/csv')  ");

                foreach ($files as $file) {

                    $sql = "select ds_files_data.*, from_base64(SUBSTRING_INDEX(data, ',', -1)) x, CHARSET(from_base64(SUBSTRING_INDEX(data, ',', -1))) c from ds_files_data where file_id = {__file_id}";

                    $rec = $db->singleValue($sql, $file, 'c');
                    if ($rec === 'binary') {

                        $data = \Tualo\Office\DS\DSFiles::instance('fibufiles')->getBase64('id', $file['id']);
                        list($mime, $d) = explode('base64,', $data);
                        $d = base64_decode($d);
                        if (@iconv('UTF-8', 'ISO-8859-1', $d) == false) {
                            $dc  = iconv('ISO-8859-1', 'UTF-8', $d);
                            $db->direct('update ds_files_data set data={data} where file_id = {file_id}', [
                                'file_id' => $file['__file_id'],
                                'data' => $mime . 'base64,' . base64_encode($dc)
                            ]);
                        }
                    }

                    /*
                    $data = \Tualo\Office\DS\DSFiles::instance('fibufiles')->getBase64('id',$file['id']);
                    list($mime,$d)=explode('base64,', $data);
                    $d = base64_decode($d);
                    */
                }


                App::result('success', true);
            }
        }, ['get', 'post'], true);

        BasicRoute::add('/fibuconv/convert', function () {

            $db = App::get('session')->getDB();
            if ($db == NULL) {
                App::result('msg', 'Nicht erlaubt');
            } else {

                /*
                $files = $db->direct('select * from fibufiles where typ="ebay-tra-c" and id ="79a7e33e-8d3a-11ef-81d4-0242c0a8b302"  ');

                foreach($files as $file){
                    $data = \Tualo\Office\DS\DSFiles::instance('fibufiles')->getBase64('id',$file['id']);
                    list($mime,$d)=explode('base64,', $data);
                    $d = base64_decode($d);
                    echo $d;
                    exit();
                }
*/


                $files = $db->direct('select * from fibufiles where typ="ebay-tra"  ');

                foreach ($files as $file) {
                    $data = \Tualo\Office\DS\DSFiles::instance('fibufiles')->getBase64('id', $file['id']);
                    list($mime, $d) = explode('base64,', $data);
                    $d = base64_decode($d);


                    //$d = preg_replace('/[^\x20-\x7E]\n/', '', $d);

                    $d = iconv('UTF-8', 'UTF-8//IGNORE', $d);
                    $pattern = "/[^a-zA-z0-9ÄÖÜäüöß@?#%!-_&~ \n\"\';]/";
                    $d = preg_replace($pattern, '', $d);
                    $d = mb_convert_encoding($d, 'UTF-8', 'UTF-8');
                    /*
                    echo ($d);
                    exit();
                    */
                    $db->direct('update ds_files_data set data={data} where file_id = {file_id}', [
                        'file_id' => $file['file_id'],
                        'data' => $mime . 'base64,' . base64_encode(($d))
                    ]);
                    $db->direct("update fibufiles set typ = 'ebay-tra-c' where id = {id}", ['id' => $file['id']]);
                }



                $files = $db->direct('select * from fibufiles where typ in ("paypal-1","paypal-2","paypal-3","paypal-4","paypal-5")  ');

                foreach ($files as $file) {
                    $data = \Tualo\Office\DS\DSFiles::instance('fibufiles')->getBase64('id', $file['id']);
                    list($mime, $d) = explode('base64,', $data);
                    $d = base64_decode($d);
                    if (@iconv('UTF-8', 'ISO-8859-1', $d) == false) {
                        $d  = iconv('ISO-8859-1', 'UTF-8', $d);


                        /*$db->direct('update ds_files_data set data={data} where file_id = {file_id}',[
                            'file_id'=>$file['file_id'],
                            'data'=>$mime.'base64,'.base64_encode($dc)
                        ]);
                        */
                    } else {
                    }
                    $d = str_replace("\t", ' ', $d);
                    $d = str_replace('"",""', chr(9), $d);
                    $d = str_replace(',""', ' ', $d);
                    $d = str_replace('"""', '', $d);
                    $d = preg_replace('/^"/m', '', $d);
                    $d = explode("\n", $d);
                    foreach ($d as $k => $v) {
                        $d[$k] = str_replace("\r", "", str_replace("\n", "", $d[$k])) . "\n";
                    }
                    $d = implode("", $d);
                    //$d= preg_replace('/$/m',"\t",$d);
                    //$d= preg_replace('/\\n\\n/m',"\n",$d);
                    //print_r($d);
                    // echo $d; exit();


                    $db->direct('update ds_files_data set data={data} where file_id = {file_id}', [
                        'file_id' => $file['file_id'],
                        'data' => $mime . 'base64,' . base64_encode($d)
                    ]);

                    $db->direct("update fibufiles set typ = concat(typ,'-c') where id = {id}", ['id' => $file['id']]);
                }




                $files = $db->direct('select * from fibufiles where typ in ("ama-p-1","ama-p-2")  ');

                foreach ($files as $file) {
                    $data = \Tualo\Office\DS\DSFiles::instance('fibufiles')->getBase64('id', $file['id']);
                    list($mime, $d) = explode('base64,', $data);
                    $d = base64_decode($d);
                    if (@iconv('UTF-8', 'ISO-8859-1', $d) == false) {
                        $d  = iconv('ISO-8859-1', 'UTF-8', $d);


                        /*$db->direct('update ds_files_data set data={data} where file_id = {file_id}',[
                            'file_id'=>$file['file_id'],
                            'data'=>$mime.'base64,'.base64_encode($dc)
                        ]);
                        */
                    } else {
                    }
                    $d = str_replace('\t', ' ', $d);

                    $d = str_replace('ï»¿', '', $d);
                    $d = str_replace('","', chr(9), $d);
                    $d = str_replace(',"', ' ', $d);
                    $d = str_replace('"', '', $d);
                    $d = preg_replace('/^"/m', '', $d);
                    $d = str_replace('NetTransactionAmount,', 'NetTransactionAmount', $d);


                    /*
                    echo $d;
                    exit();
                    */
                    try {
                        $db->direct('update ds_files_data set data={data} where file_id = {file_id}', [
                            'file_id' => $file['file_id'],
                            'data' => $mime . 'base64,' . base64_encode($d)
                        ]);

                        $db->direct("update fibufiles set typ = concat(typ,'-c') where id = {id}", ['id' => $file['id']]);
                    } catch (\Exception $e) {
                        echo $e->getMessage();
                    }
                }

                App::result('success', true);
            }

            App::contenttype('application/json');
        }, ['get', 'post'], true);
    }
}
