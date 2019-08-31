<?php
require $_SERVER['DOCUMENT_ROOT'].'/components/autoloader.php';

parse_str(implode('&', array_slice($argv, 1)), $_GET);

$Helper = new Helper();
$Parser = new Parser();

if (isset($_GET['--parse'])) {
    $Parser = new Parser();
    $ret = $Parser -> parse($_GET['url']);

    $Helper = new Helper();
    $urlWProtocol = $Helper -> addProtocol($_GET['url']);
    $domain = $Helper->getDomain($urlWProtocol);

    $getRootLinks[0] = $Parser -> getLinksOfPage($urlWProtocol);


    $all    = $Parser -> getAllLinks($getRootLinks[0]);
    $uniqe  = $Parser -> getUniqueLinks($all);
    $images = $Parser -> getAllImagesFromURLs($uniqe);

    $Helper->writeCSV($images, $domain);

} elseif (isset($_GET['--report'])) {
    $report =  $Helper->getDomainStats($_GET['domain']);
    echo "Report for domain ".$_GET['domain'].":\nurl's: $report[0]\nimages: $report[1]";
} elseif (isset($_GET['--help'])) {
    echo "
Usage: parser.php [action] [args]

  actions:
  --parse <url>       Run parser. Create domain.csv file
  --report <domain>   Report of parser work on current domain
  --help              Open help page
";
}

?>