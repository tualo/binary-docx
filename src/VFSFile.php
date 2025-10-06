<?php

namespace Tualo\Office\BinaryDocx;

use Sabre\DAV;
use Tualo\Office\Basic\TualoApplication as App;


class VFSStreamWrapper
{
    private $position;
    private $varname;
    private $db;
    private $data;

    public static function getIdFromPath($path)
    {
        $url = parse_url($path);
        return preg_replace('/[^0-9]/', '', $url["path"]);
    }

    function stream_open($path, $mode, $options, &$opened_path)
    {

        $this->varname = self::getIdFromPath($path);
        App::logger('VFSStreamWrapper')->info("stream_open " . $this->varname . " from " . $path . " mode: " . $mode . " options: " . $options);
        $this->position = 0;
        $this->db = \Tualo\Office\Basic\TualoApplication::get('session')->getDB();
        $r = $this->db->direct("select doc_data from fb_wvd.doc_binary where document_link = {id}", [
            'id' => $this->varname
        ]);
        if (count($r) == 1) {
            $this->data = ($r[0]['doc_data']);
        } else {
            $this->data = '';
        }
        return true;
    }

    function stream_read($count)
    {
        $ret = substr($this->data, $this->position, $count);
        $this->position += strlen($ret);
        return $ret;
    }

    function stream_eof()
    {
        return $this->position >= strlen($this->data);
    }

    function stream_tell()
    {
        return $this->position;
    }

    function stream_seek($offset, $whence)
    {
        if ($whence == SEEK_SET) {
            if ($offset < strlen($this->data) && $offset >= 0) {
                $this->position = $offset;
                return true;
            } else {
                return false;
            }
        } elseif ($whence == SEEK_CUR) {
            if ($offset >= 0) {
                $this->position += $offset;
                return true;
            } else {
                return false;
            }
        } elseif ($whence == SEEK_END) {
            if (strlen($this->data) + $offset >= 0) {
                $this->position = strlen($this->data) + $offset;
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
}

class VFSFile extends DAV\File
{

    private $myPath;
    private $db;

    function __construct($myPath, $db = null)
    {

        App::logger('VFSFile')->info("__construct " . $myPath);

        $this->myPath = $myPath;
        $this->db = $db;
        if ($this->db === null) {
            $this->db = \Tualo\Office\Basic\TualoApplication::get('session')->getDB();
        }
    }

    function getName()
    {
        App::logger('VFSFile')->info("getName " . $this->myPath);

        return basename($this->myPath);
    }

    function get()
    {
        App::logger('VFSFile')->info("get " . $this->myPath);

        stream_wrapper_register("var", "\Tualo\Office\BinaryDocx\VFSStreamWrapper") or die("Failed to register protocol");
        return fopen("var://" . $this->myPath, "r+");
    }

    function getSize()
    {


        $size = $this->db->singleValue("select length(doc_data) x from fb_wvd.doc_binary where document_link = {id}", [
            'id' => VFSStreamWrapper::getIdFromPath($this->myPath)
        ], 'x');

        return $size;
    }

    function getETag()
    {
        $hash = $this->db->singleValue("select md5(doc_data) x from fb_wvd.doc_binary where document_link = {id}", [
            'id' => VFSStreamWrapper::getIdFromPath($this->myPath)
        ], 'x');
        return '"' . $hash . '"';
    }
}
