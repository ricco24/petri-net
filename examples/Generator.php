<?php

# Get Petri Net
$pNet = include(__DIR__ . '/BuildNet.php');

// Generate pnml for given net
$generator = new \PetriNet\Xml\Generator($pNet);
$pnml = $generator->generate();

// Print result to output
echo '<pre>';
var_dump(htmlspecialchars($pnml));
echo '</pre>';

// Return used only for Parser example
return $pnml;