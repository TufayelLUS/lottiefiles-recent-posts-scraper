<?php
function getContent($url)
  {
    $ch = curl_init();
    $options = array(
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0',
        CURLOPT_ENCODING       => "utf-8",
        CURLOPT_AUTOREFERER    => true,
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_MAXREDIRS      => 10,
    );
    curl_setopt_array( $ch, $options );
    if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
      curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    }
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return strval($data);
  }
  $resp = getContent("https://lottiefiles.com/recent?page=1");
  $data = explode('" v-on:click.prevent', $resp);
  $dataset = array();
  for ($i=0; $i < count($data)-1; $i++) { 
  	$tmp = explode("id='elem_", $data[$i])[1];
  	$tmp = explode("href=\"", $tmp)[1];
  	$descURL = explode("\"", $tmp)[0];
  	$descSRC = getContent($descURL);
  	$description = explode("description&quot;:&quot;", $descSRC)[1];
  	$description = explode("&quot;", $description)[0];
  	$tmp = explode("title=\"", $data[$i]);
  	$title = $tmp[count($tmp)-1];
  	$jsonURL = explode("src=\"", $data[$i+1]);
  	$jsonURL = explode("\"", $jsonURL[1])[0];
  	$jsonURL = str_replace("\\", "", $jsonURL);
  	array_push($dataset, ["Title" => $title, "URL" => $jsonURL, "Description" => $description]);
  }
  echo "<pre>";
  //var_dump($dataset);
  echo(json_encode($dataset, JSON_PRETTY_PRINT));
  echo "</pre>";
  ?>
