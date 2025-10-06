<?php

namespace Tualo\Office\BinaryDocx;

use Sabre\DAV;
use Sabre\DAV\Collection;
use Tualo\Office\Basic\TualoApplication as App;

class VFSDirectory extends Collection
{

  private $myPath;
  private $db;

  /*
  $files = $db->direct("select * from fb_wvd.doc_binary where document_link = {id}", [
                    'id' => $matches['id']
                ]);
  */

  function __construct($myPath)
  {
    App::logger('VFSDirectory')->info("*********************");

    $this->myPath = $myPath;
    $this->db = App::get('session')->getDB();

    App::logger('VFSDirectory')->info("DB: " . (is_null($this->db) ? 'null' : 'not null'));
    App::logger('VFSDirectory')->info("__construct " . $myPath);
  }

  function getChildren()
  {

    $children = [];

    $sql = '
    select 
        document_link,
        last_upd_user,
        last_upd_date,
        create_user,
        create_date,
        archive,
        header_type,
        has_base64_backup,
        detected_by_strings
    from 
      fb_wvd.doc_binary 
    order by create_date desc limit 100 ';

    App::logger('VFSDirectory')->info("getChildren SQL: " . $sql);
    $files = $this->db->direct($sql, []);
    foreach ($files as $f) {
      App::logger('VFSDirectory')->info("getChildren found file: " . $f['document_link'] . '.docx');
      $children[] = new VFSFile($f['document_link'] . '.docx', $this->db);
    }
    /*
    // Loop through the directory, and create objects for each node
    foreach (scandir($this->myPath) as $node) {

      // Ignoring files staring with .
      if ($node[0] === '.') continue;
      $children[] = $this->getChild($node);
    }
    */

    return $children;
  }

  function getChild($name)
  {
    App::logger('VFSDirectory')->info("getChild " . $name);

    $path = $this->myPath . '/' . $name;

    /*
    // We have to throw a NotFound exception if the file didn't exist
    if (!file_exists($path)) {
      throw new DAV\Exception\NotFound('The file with name: ' . $name . ' could not be found');
    }
*/
    // Some added security
    if ($name[0] == '.')  throw new DAV\Exception\NotFound('Access denied');
    /*
    if (is_dir($path)) {

      return new VFSDirectory($path);
    } else {
*/
    return new VFSFile($path);
    //  }
  }

  function childExists($name)
  {
    App::logger('VFSDirectory')->info("childExists " . $name);

    return true; //file_exists($this->myPath . '/' . $name);
  }

  function getName()
  {
    App::logger('VFSDirectory')->info("getName ");

    return basename($this->myPath);
  }
}
