<?php

# Using autoload
include(__DIR__ . '/../autoload.php');

# Crate PNet object
$pNet = new \PetriNet\Net('basic1Net');

# Add places to net
$place1 = $pNet->addPlace('p1', 'Place 1');
$place2 = $pNet->addPlace('p2', 'Place 2');
$place3 = $pNet->addPlace('p3', 'Place 3');

# Add transitions to net
$transition1 = $pNet->addTransition('t1', 'Transition 1');
$transition2 = $pNet->addTransition('t2', 'Transition 2');
$transition3 = $pNet->addTransition('t3', 'Transition 3');

# Connect transitions with places
$arc1 = $pNet->addArc('a1', $place1, $transition1);
$arc1->setInscription(5);
$arc2 = $pNet->addArc('a2', $transition1, $place2);
$arc2->setInscription(2);
$arc3 = $pNet->addArc('a3', $place2, $transition2);
$arc3->setInscription(4);
$pNet->addArc('a4', $transition2, $place3);
$arc5 = $pNet->addArc('a5', $place3, $transition3);
$arc5->setInscription(3);
$pNet->addArc('a6', $transition3, $place2);

# Can setup graphics of objects
$place1->getNameGraphics()->setOffset(10, 20);
$transition1->getGraphics()->setPosition(7, 24);
$arc5->getGraphics()->addPosition(100, 100);
$arc5->getGraphics()->addPosition(20, 20);
$arc5->getInscriptionGraphics()->setOffset(25, 25);

return $pNet;