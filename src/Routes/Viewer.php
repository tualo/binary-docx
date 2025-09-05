<?php

namespace Tualo\Office\BinaryDocx\Routes;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\DS;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Ramsey\Uuid\Uuid as U;

class Viewer implements IRoute
{


    public static function register()
    {

        BasicRoute::add('/binary-docx/viewer/(?P<id>.+)', function ($matches) {
            $db = App::get('session')->getDB();
            App::contenttype('application/json');




            if ($db == NULL) {
                App::result('msg', 'Nicht erlaubt');
            } else {
                $files = $db->singleRow("select * from fb_wvd.doc_binary where document_link = {id}", [
                    'id' => $matches['id']
                ]);

                /*

                header('Content-Disposition: attachment; filename="' . $matches['id'] . '.docx"');


                header('Content-Type: application/msword');
                http_response_code(200);
                */
                //App::contenttype('application/vnd.openxmlformats-officedocument.wordprocessingml.document');
                //echo base64_decode($files[0]['base64_backup']);
                if (!is_null($files['doc_data'])) {
                    $data = $files['doc_data'];
                } else {
                    $data = base64_decode($files['base64_backup']);
                }

                $ext = 'docx';
                $readerType = 'Word2007';
                if ($files['detected_by_strings'] == 'unknown') {
                    $readerType = 'MsDoc';
                    $ext = 'doc';
                }
                if ($files['detected_by_strings'] == 'Microsoft Office Word 97-2003-Dokument (doc)') {
                    $readerType = 'MsDoc';
                    $ext = 'doc';
                }
                if ($files['detected_by_strings'] == 'Microsoft Word 97-2004-Dokument (doc)') {
                    $readerType = 'MsDoc';
                    $ext = 'doc';
                }
                if ($files['detected_by_strings'] == 'Microsoft Word-Dokument (doc)') {
                    $readerType = 'MsDoc';
                    $ext = 'doc';
                }

                // echo $files['detected_by_strings'];


                $tempFile = App::get('tempPath') . '/' . (U::uuid4())->toString() . '.' . $ext;
                $tempFile2 = App::get('tempPath') . '/' . (U::uuid4())->toString() . '.' . 'html';

                file_put_contents($tempFile, $data);

                $phpWord = \PhpOffice\PhpWord\IOFactory::load($tempFile);

                /*
                $phpWord = IOFactory::createReader($readerType);
                $phpWord->load($tempFile);
*/

                $htmlWriter = new \PhpOffice\PhpWord\Writer\HTML($phpWord);
                $htmlWriter->save($tempFile2);

                echo file_get_contents($tempFile2);


                /*

                
                $phpWord = IOFactory::createReader($readerType);
                $phpWord->load($tempFile);

                $objWriter = IOFactory::createWriter($phpWord, 'HTML');
                $objWriter->save('php://output');
                */
                unlink($tempFile);
                unlink($tempFile2);
                exit();
            }
        }, ['get', 'post'], true);
    }
}
