<?php

# Get PNML of our net
$pnml = include(__DIR__ . '/Generator.php');

// Parse given PNML
$parser = new \PetriNet\Xml\Parser($pnml);
$pNet = $parser->parse();

// Print result to output
echo '<pre>';
var_dump($pNet);
echo '</pre>';