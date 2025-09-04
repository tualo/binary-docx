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

        BasicRoute::add('/binary-docx/register/(?P<id>.+)', function ($matches) {
            $session = App::get('session');

            $token = $session->registerOAuth(
                // $params = ['cmp' => 'cmp_ds'],
                $force = true,
                $anyclient = false,
                $path = '/binary-docx/open/' . $matches['id'] . '.docx',
                $name = isset($_REQUEST['name']) ? $_REQUEST['name'] : '',
                $device = isset($_REQUEST['device']) ? $_REQUEST['device'] : '',
            );
            $session->oauthSingleUse($token);
            App::contenttype('application/json');
            App::result('success', true);
            App::result('url', App::configuration('binary_docx', 'base_url', '.') . '/~/' . $token . '/binary-docx/open/' . $matches['id'] . '.docx');
        }, ['get', 'post'], true);


        BasicRoute::add('/binary-docx/open/(?P<id>.+).docx', function ($matches) {
            $db = App::get('session')->getDB();
            App::contenttype('application/json');




            if ($db == NULL) {
                App::result('msg', 'Nicht erlaubt');
            } else {
                $files = $db->direct("select * from fb_wvd.doc_binary where document_link = {id}", [
                    'id' => $matches['id']
                ]);


                header('Content-Disposition: attachment; filename="' . $matches['id'] . '.docx"');


                header('Content-Type: application/msword');
                http_response_code(200);
                //App::contenttype('application/vnd.openxmlformats-officedocument.wordprocessingml.document');
                echo base64_decode($files[0]['base64_backup']);
                exit();
            }
        }, ['get', 'post'], true);
    }
}
