<?php

# Get Petri Net
$pNet = include(__DIR__ . '/BuildNet.php');

# Print - no executable transactions
printExecutableTransactions($pNet);

# Add some tokens to places
$pNet->getElement('p1')->setTokens(10);
$pNet->getElement('p2')->setTokens(20);

# Print - transaction 1 and transaction 2
printExecutableTransactions($pNet);

# Help example function
function printExecutableTransactions($pNet) {
	$eTransactions = $pNet->getExecutableTransitions();
	if(count($eTransactions)) {
		foreach($eTransactions as $eTransition) {
			echo $eTransition->getLabel() . '<br />';
		}
	} else {
		echo 'No executable transactions <br />';
	}
}