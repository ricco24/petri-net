<?php

$vendorDir = __DIR__;

// Classmap definition
$classMap = array(
	'PetriNet\\Element\\Arc' => $vendorDir . '/src/Element/Arc.php',
	'PetriNet\\Element\\Place' => $vendorDir . '/src/Element/Place.php',
	'PetriNet\\Element\\Transition' => $vendorDir . '/src/Element/Transition.php',
	'PetriNet\\Exception\\ArcException' => $vendorDir . '/src/Exception/ArcException.php',
	'PetriNet\\Exception\\PetriNetException' => $vendorDir . '/src/Exception/PetriNetException.php',
	'PetriNet\\Exception\\TransitionException' => $vendorDir . '/src/Exception/TransitionException.php',
	'PetriNet\\Exception\\XMLParserException' => $vendorDir . '/src/Exception/XMLParserException.php',
	'PetriNet\\Graphics\\AbstractGraphics' => $vendorDir . '/src/Graphics/AbstractGraphics.php',
	'PetriNet\\Graphics\\AnnotationGraphics' => $vendorDir . '/src/Graphics/AnnotationGraphics.php',
	'PetriNet\\Graphics\\ArcGraphics' => $vendorDir . '/src/Graphics/ArcGraphics.php',
	'PetriNet\\Graphics\\NodeGraphics' => $vendorDir . '/src/Graphics/NodeGraphics.php',
	'PetriNet\\Xml\\Generator' => $vendorDir . '/src/XML/Generator.php',
	'PetriNet\\Xml\\Parser' => $vendorDir . '/src/XML/Parser.php',
	'PetriNet\\Net' => $vendorDir . '/src/Net.php'
);

// Register autoload
spl_autoload_register(function($class) use ($classMap) {
	// work around for PHP 5.3.0 - 5.3.2 https://bugs.php.net/50731
	if ('\\' == $class[0]) {
		$class = substr($class, 1);
	}

	if(isset($classMap[$class])) {
		include($classMap[$class]);
	}
});

