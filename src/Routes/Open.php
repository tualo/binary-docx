<?php

namespace Tualo\Office\BinaryDocx\Routes;

use Tualo\Office\Basic\TualoApplication as App;
use Tualo\Office\Basic\Route as BasicRoute;
use Tualo\Office\Basic\IRoute;
use Tualo\Office\DS;

class Open implements IRoute
{

    public static function register()
    {



        BasicRoute::add('/binary-docx/open/(?P<id>.+)', function ($matches) {
            $db = App::get('session')->getDB();
            App::contenttype('application/json');




            if ($db == NULL) {
                App::result('msg', 'Nicht erlaubt');
            } else {
                $files = $db->direct("select * from fb_wvd.doc_binary where document_link = {id}", [
                    'id' => $matches['id']
                ]);


                header('Content-Disposition: attachment; filename="' . $matches['id'] . '.docx"');


                App::contenttype('application/vnd.openxmlformats-officedocument.wordprocessingml.document');
                echo base64_decode($files[0]['base64_backup']);
            }
        }, ['get', 'post'], true);
    }
}
