<?php

if (sizeof($_GET) == 0) {
  //si se está funcionando desde linea de comandos
  //http://php.net/manual/en/features.commandline.php#108883
  parse_str(implode('&', array_slice($argv, 1)), $_GET);

  //por si no manda nada, incluso en linea de comandos
  if (sizeof($_GET) == 0)
    die("gimme some arguments!!");
}

$html_file = $_GET['html_file'];
$result_file = isset($_GET['result_file']) ?: __DIR__ . "/Pocket.csv";

echo PHP_EOL . "html file: " . $html_file . PHP_EOL;


//$html = new SimpleXMLElement($dte);

$doc = new DOMDocument();
$doc->loadHTMLFile($html_file);

$as = $doc->getElementsByTagName('a');


if (is_null($as)) exit();

$result = [];

$result[] = "Name;Tags;URL;";

foreach ($as as $i => $a) {

  $url = $a->getAttribute("href");
  $tags = $a->getAttribute("tags");
  $name = $a->nodeValue;

  $row =  '"' . $name . '";"' . $tags . '";"' . $url . '";';

  $result[] = $row;
}

echo PHP_EOL . "entries analized: " . ($i - 1) . PHP_EOL;

$e = file_put_contents($result_file, implode(PHP_EOL, $result));

if ( $e === false )
  echo "can't save the file";