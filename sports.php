<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);



// from http://www.finditillinois.org/wordpress/?page_id=23
// and https://github.com/ahbullen/CONTENTdmAPIs/blob/master/dmBrowse.php$collection = "p16014coll11";
// $xmlData = file_get_contents('https://server16014.contentdm.oclc.org/dmwebservices/index.php?q=dmQuery/all/title^woman^all^and/title!subjec!descri/title/50/1/0/0/0/0/xml');
$xmlData = file_get_contents('https://server16014.contentdm.oclc.org/dmwebservices/index.php?q=dmQuery/p16014coll11/title^%20^all^and/title!subjec!descri/title/40/1/0/0/0/0/xml');


// Create the document object

$xml = simplexml_load_string($xmlData);

$pager = array();

// How many hits did the search yield

foreach ($xml->xpath('//pager') as $hits) {
        $pager[] = array(
                'start' => (string) $hits->start,
                'total' => (string) $hits->total
        );
}

$result = array();

// Get the nodes and loop them

foreach ($xml->xpath('//record') as $record) {
        $result[] = array(
                'collection' => (string) $record->collection,
                'title' => (string) $record->title,
                'subject' => (string) $record->subjec,
                'descri' => (string) $record->descri,
                'thumb' => (string) $record->pointer,
                'creator' => (string) $record->creato
        );
}

$numberOfHits = $pager[0]["total"];
$resultCount = count($result) - 1;

?>
<html>

<style media="screen" type="text/css">

img.fooclass {
  height: 200px;
   width: 200px;
}

</style>

        <head>
                <title>SRC - interview series </title>
        </head>
        <body>
                <div id="header">
                        <h1 style="text-align: center;">SRC - Sports Icons interview series </h1>
                        <h2 style="text-align: center;">with <?php echo $numberOfHits ?> interviews of cleveland legends</h2>
                </div>
                <div id="list">
                        <ol>
                        <?php
for ($i=0;$i<=$resultCount;$i++) {
        $title = $result[$i]["title"];
        $subject = $result[$i]["subject"];
        $description = $result[$i]["descri"];
        $thumb = $result[$i]["thumb"];
        $the_creator = $result[$i]["creator"];
        $collection = $result[$i]["collection"];
        $collection = str_ireplace("/", "", "$collection");
        $urlStr = "http://cplorg.contentdm.oclc.org/cdm/singleitem/collection/p16014coll11/id/" . $thumb . "/rec/" . $i ;
        $imgStr = "http://cplorg.contentdm.oclc.org/utils/getthumbnail/collection/$collection/id/" . $thumb;
        echo "<li><a href=\"$urlStr\"><img class=\"fooclass\" src=\"$imgStr\"></a> <strong>$title</strong><br /><em>$description</em> $the_creator <br /><p /></li>\n";
}
?>
                        </ol>
                </div>
                        </body>
</html>

