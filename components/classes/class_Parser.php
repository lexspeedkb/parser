<?php
class Parser {

  private $resolved  = array();
  private $allLinks  = array();
  private $allImages = array();

    /**
      * @param url - url of parsed page with/without protocol
      *
      *
      */    
    public function parse($url) {
        $Helper = new Helper();
        $urlWProtocol = $Helper -> addProtocol($url);
        $parsedPage =  file_get_contents($urlWProtocol);

        return $parsedPage;
    }

    public function getLinksOfPage($url) {
      $Helper = new Helper();

      $domain = $Helper -> getDomain($url);
      
      $links = array();
      
      $html = file_get_contents($url);

      $dom = new DOMDocument();
      @$dom->loadHTML($html);

      $xPath = new DOMXPath($dom);

      $resolved = array();

      $elements = $xPath->query("//a/@href");
      foreach ($elements as $e) {
          $isCurrentDomain = strpos($e->nodeValue, $domain);

          if ($isCurrentDomain !== false) {
            $key = array_search($e->nodeValue, $this->resolved);
            if ($key === false) {
              array_push($links, $e->nodeValue);
              array_push($this->resolved, $e->nodeValue);
              array_push($this->allLinks, $e->nodeValue);
            }
            
          }
      }

      $array_unique = array_unique($links);

      return $array_unique;
    }

    public function getAllLinks($array) {
      $i = 1;
      foreach ($array as $key => $value) {
          $getRootLinks[$i] = $this -> getLinksOfPage($value);
          $i++;
      }

      return $this->allLinks;
    }

    public function getUniqueLinks($array) {
      $unique = array_unique($array);

      return $unique;
    }

    public function getAllImagesFromURLs($urls) {
      $linksAndImages   = array();
      $localImagesArray = array();

      $i = 0;
      $countOfImages = 0;
      foreach ($urls as $key => $url) {
        array_push($localImagesArray, $this -> getImagesOfPage($url));
        $linksAndImages[$i][0] = $url;
        $linksAndImages[$i][1] = $this -> getImagesOfPage($url);

        foreach ($this -> getImagesOfPage($url) as $key => $value) {
          $linksAndImages[$i][1] .= $value."\n";
          $countOfImages++;
        }
        $linksAndImages[$i][1] = str_replace('Array', '', $linksAndImages[$i][1]);

        $i++;
      }

      $stats[0] = count($urls);
      $stats[1] = $countOfImages;

      array_unshift($linksAndImages, $stats);

      return $linksAndImages;
    }

    public function getImagesOfPage($url) {
      $localImagesArray = array();

      $Helper = new Helper();

      $doc = $this->parse($url);

      preg_match_all('/<img[^>]+>/i',$doc, $results);

      $images = array();
      foreach( $results[0] as $img_tag)
      {
          preg_match_all('/(src)=("[^"]*")/i',$img_tag, $images[$img_tag]);
          $i = 0;
          foreach ($images as $image) {
            $image[2][0] = str_replace('"', '', $image[2][0]);
            array_push($this->allImages, $Helper->addProtocolToPhoto($image[2][0]));
            array_push($localImagesArray, $Helper->addProtocolToPhoto($image[2][0]));
          }
      }

      return $localImagesArray;

    }

}
?>