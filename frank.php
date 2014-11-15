<?php 

/**
 * Frank
 *
 * Exposes a useful object of a directory structure to build websites. 
 * Frank takes cues from flat-file content management systems like Kirby
 * and Stacey but purposefully does excruciatingly less. If Frank does
 * not do enough for you, don't use it! For usage, see @link
 *
 * @author    @amongiants
 * @version   0.0.1
 * @link      https://github.com/amongiants/Frank
 */

class Frank {

  private $site;

  /**
   * Returns the site object
   */
  public function site(){
    return $this->site;
  }

  /**
   * Constructs site object from content directory
   */
  public function __construct($contentDirectory = './content'){
    $temp = $this->nodeArray(new DirectoryIterator($contentDirectory));
    $this->recur_ksort($temp); // DirectoryIterator order things unexpectedly, recursive ksort to kick off proper
    $this->site = $this->objectify($this->structureIterator($temp));
  }

  /**
   * Builds basic node array of active directories and visible files
   */
  private function nodeArray(DirectoryIterator $dir){
    $data = array();
    foreach($dir as $node){
      if($node->isDir() && !$node->isDot() && $this->isActiveDirectory($node->getPathname()))
        $data[$node->getFilename()] = $this->nodeArray(new DirectoryIterator($node->getPathname()));
      elseif($node->isFile() && $this->isNotHiddenFile($node->getFilename()))
        $data[] = $node->getPathname();
    }
    return $data;
  }

  /**
   * Iterates node array and adds some Frankness:
   * For each directory, images placed into [images],
   * files placed into [files], and info.json decoded
   * and placed into [info]
   */
  private function structureIterator($array){
    $data = array();
    foreach($array as $key => $value){
      if(is_array($value))
        $data[$this->slug($key)] = $this->structureIterator($value);
      else {
        if($this->isImage($value))
          $data['images'][] = $value;
        elseif($this->isJson($value))
          $data['info'] = $this->convertJson($value);
        else
          $data['files'][] = $value;
      }
    }
    $this->sortFiles($data);
    return $data;
  }

  /**
   * Sort images and files alphanumerically
   */
  private function sortFiles(&$array){
    if(isset($array['files']))  sort($array['files']);
    if(isset($array['images'])) sort($array['images']);
  }

  private function recur_ksort(&$array){
    foreach($array as &$value)
      if(is_array($value)) $this->recur_ksort($value);
    ksort($array);
  }

  private function slug($path){
    return ltrim(basename($path), "0..9-");
  }

  private function isActiveDirectory($path){
    return preg_match('/^\d/', basename($path)) === 1;
  }

  private function isNotHiddenFile($filename){
    return strpos($filename, '.');
  }

  private function isImage($path){
    return getimagesize($path) || pathinfo($path)['extension'] == 'svg';
  }

  private function isJson($path){
    return pathinfo($path)['extension'] == 'json';
  }

  private function convertJson($path){
    return json_decode(file_get_contents($path), TRUE);
  }

  private function objectify($array){
    return json_decode(json_encode($array), FALSE);
  }

}