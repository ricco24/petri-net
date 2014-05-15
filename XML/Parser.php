<?php

namespace PetriNet\Xml;

class Parser
{
	/** @var string				XML data */
	private $xml;
	
	/** @var \DOMDocument */
	private $dom;
	
	/** @var \DOMXPath */
	private $xPath;
	
	/**
	 * Construct parser
	 * @param string $xml
	 * @return bool 
	 */
	public function __construct($xml) {
		$this->xml = $xml;
		$this->dom = new \DOMDocument();		
		
		// Check if xml is parsed successfully
		if(!$this->getDOM()->loadXML($this->getXML())) {
			throw new \PetriNet\Exception\XMLParserException();
		}
		
		$this->xPath = new \DOMXPath($this->dom);
		
		// Try to find one required element - <net>
		if(!$this->getNetEl()) {
			throw new \PetriNet\Exception\XMLParserException();
		}
	}
	
	/**
	 * Parse loaded xml
	 * @return \PetriNet\Net
	 */
	public function parse() {
		// Create PN object
		$netEl = $this->getNetEl();		
		$petriNet = new \PetriNet\Net($netEl->getAttribute('id'));
		
		// Create places
		foreach($this->getXPath()->query('/pnml/net/place') as $placeEl) {
			$this->setupPlace($petriNet, $placeEl);
		}
		
		// Create transitions
		foreach($this->getXPath()->query('/pnml/net/transition') as $transitionEl) {
			$this->setupTransition($petriNet, $transitionEl);
		}
		
		// Create arcs
		foreach($this->getXPath()->query('/pnml/net/arc') as $arcEl) {
			$this->setupArc($petriNet, $arcEl);
		}

		return $petriNet;
	}
	
	/*************************** PRIVATE FUNCTIONS ****************************/
	
	/**
	 *
	 * @return string
	 */
	private function getXML() {
		return $this->xml;
	}
	
	/**
	 * 
	 * @return \DOMDocument
	 */
	private function getDOM() {
		return $this->dom;
	}
	
	/**
	 * 
	 * @return \DOMXPath
	 */
	private function getXPath() {
		return $this->xPath;
	}
	
	/**
	 * Get net element
	 * @return \DOMElement
	 */
	private function getNetEl() {
		return $this->getXPath()->query('/pnml/net')->item(0);
	}
	
	/**
	 * Get single element from xml string
	 * @param \DOMElement $element
	 * @param string $tagName
	 * @return \DOMElement
	 */
	private function getElement($element, $tagName) {
		return $this->getXPath()->query($tagName, $element)->item(0);
	}
	
	/**
	 * Get elements from xml string
	 * @param \DOMElement $element
	 * @param string $tagName
	 * @return type
	 */
	private function getElements($element, $tagName) {
		return $this->getXPath()->query($tagName, $element);
	}
	
	/**
	 * Get value from single element
	 * @param \DOMElement $element
	 * @param string $tagName
	 * @return string
	 */
	private function getElementValue($element, $tagName) {
		return $this->getXPath()->query($tagName, $element)->item(0)->nodeValue;
	}
	
	/**
	 * Generate random id. Unique for pn elements
	 * @param \PetriNet\Net $petriNet
	 * @return string
	 */
	private function getRandomId($petriNet) {
		$id = uniqid();
		while($petriNet->getElement($id)) {
			$id = uniqid();
		} 
		
		return $id;
	}
	
	/************************** MAIN OBJECT SETUPS ****************************/
	
	/**
	 * Create place and add place to net
	 * @param \PetriNet\Net $petriNet
	 * @param \DOMElement $placeEl
	 */
	private function setupPlace($petriNet, $placeEl) {
		$nameEl = $this->getElement($placeEl, 'name');
		$name = $this->getElementValue($nameEl, 'value');
		$id = $placeEl->getAttribute('id');

		// Add place to net
		$place = $petriNet->addPlace($id, $name);

		// Setup place data
		$initialMarkingEl = $this->getElement($placeEl, 'initialMarking');
		$tokenEl = $this->getElement($initialMarkingEl, 'token');
		$place->setInitialMarking($this->getElementValue($tokenEl, 'value'), true);

		// Setup graphics
		$this->setupNodeGraphics($place->getGraphics(), $this->getElement($placeEl, 'graphics'));
		$this->setupAnnotationGraphics($place->getNameGraphics(), $this->getElement($nameEl, 'graphics'));
	}
	
	/**
	 * Create transition and add transition to net
	 * @param \PetriNet\Net $petriNet
	 * @param \DOMElement $transitionEl
	 */
	private function setupTransition($petriNet, $transitionEl) {
		$nameEl = $this->getElement($transitionEl, 'name');
		$name = $this->getElementValue($nameEl, 'value');
		$id = $transitionEl->getAttribute('id');

		// Add transition to net
		$transition = $petriNet->addTransition($id, $name);

		// Setup graphics
		$this->setupNodeGraphics($transition->getGraphics(), $this->getElement($transitionEl, 'graphics'));
		$this->setupAnnotationGraphics($transition->getNameGraphics(), $this->getElement($nameEl, 'graphics'));
	}
	
	/**
	 * Create arc and add arc to net
	 * @param \PetriNet\Net $petriNet
	 * @param \DOMElement $arcEl
	 */
	private function setupArc($petriNet, $arcEl) {
		$id = $arcEl->getAttribute('id');
		$source = $arcEl->getAttribute('source');
		$target = $arcEl->getAttribute('target');
		
		// Generate random id for arc
		if(!$id) {
			$id = $this->getRandomId($petriNet);
		}
		
		// Check required data
		if($id && $source && $target) {		
			$inscriptionEl = $this->getElement($arcEl, 'inscription');
			$inscription = $this->getElementValue($inscriptionEl, 'value');
			
			// Add arc to net and setup data
			$arc = $petriNet->addArc($id, $petriNet->getElement($source), $petriNet->getElement($target));
			$arc->setInscription($inscription);
			
			// Setup graphics
			$this->setupAnnotationGraphics($arc->getInscriptionGraphics(), $inscriptionEl);
			$this->setupArcGraphics($arc->getGraphics(), $arcEl);
		}
	}
	
	/**************************** GRAPHICS SETUPS *****************************/
	
	/**
	 * Setup node graphics
	 * @param \PetriNet\Graphics\NodeGraphics $nodeGraphics
	 * @param \DOMElement $graphicsEl
	 */
	private function setupNodeGraphics($nodeGraphics, $graphicsEl) {
		// Get elements from xml
		$positionEl = $this->getElement($graphicsEl, 'position');
		$dimensionEl = $this->getElement($graphicsEl, 'dimension');
		$fillEl = $this->getElement($graphicsEl, 'fill');
		$lineEl = $this->getElement($graphicsEl, 'line');

		// Setup data to graphics element
		if($positionEl) { $nodeGraphics->setPosition($positionEl->getAttribute('x'), $positionEl->getAttribute('y'));	}
		if($dimensionEl) { $nodeGraphics->setDimension($dimensionEl->getAttribute('x'), $dimensionEl->getAttribute('y')); }	
		if($fillEl) { $nodeGraphics->setFill($fillEl->getAttribute('color'), $fillEl->getAttribute('image'), $fillEl->getAttribute('gradient-color'), $fillEl->getAttribute('gradient-rotation')); }
		if($lineEl) { $nodeGraphics->setLine($lineEl->getAttribute('shape'), $lineEl->getAttribute('color'), $lineEl->getAttribute('width'), $lineEl->getAttribute('style')); }
	}
	
	/**
	 * Setup annotation graphics
	 * @param \PetriNet\Graphics\AnnotationGraphics $annotationGraphics
	 * @param \DOMElement $graphicsEl
	 */
	private function setupAnnotationGraphics($annotationGraphics, $graphicsEl) {
		// Get elements from xml
		$offsetEl = $this->getElement($graphicsEl, 'offset');		
		$fillEl = $this->getElement($graphicsEl, 'fill');
		$lineEl = $this->getElement($graphicsEl, 'line');
		$fontEl = $this->getElement($graphicsEl, 'font');
		
		// Setup data to graphics element
		if($offsetEl) { $annotationGraphics->setOffset($offsetEl->getAttribute('x'), $offsetEl->getAttribute('y')); }
		if($fillEl) { $annotationGraphics->setFill($fillEl->getAttribute('color'), $fillEl->getAttribute('image'), $fillEl->getAttribute('gradient-color'), $fillEl->getAttribute('gradient-rotation')); }
		if($lineEl) { $annotationGraphics->setLine($lineEl->getAttribute('shape'), $lineEl->getAttribute('color'), $lineEl->getAttribute('width'), $lineEl->getAttribute('style')); }
		if($fontEl) { $annotationGraphics->setFont($fontEl->getAttribute('family'), $fontEl->getAttribute('style'), $fontEl->getAttribute('weight'), $fontEl->getAttribute('size'), $fontEl->getAttribute('decoration'), $fontEl->getAttribute('align'), $fontEl->getAttribute('rotation')); }
	}
	
	/**
	 * Setup arc graphics
	 * @param \PetriNet\Graphics\ArcGraphics $arcGraphics
	 * @param \DOMElement $graphicsEl
	 */
	private function setupArcGraphics($arcGraphics, $graphicsEl) {
		// Get elements from xml
		$positionEls = $this->getElements($graphicsEl, 'position');
		$lineEl = $this->getElement($graphicsEl, 'line');
		
		// Setup data to graphics element
		if($lineEl) { $arcGraphics->setLine($lineEl->getAttribute('shape'), $lineEl->getAttribute('color'), $lineEl->getAttribute('width'), $lineEl->getAttribute('style')); }
		
		// Arc graphics can have multiple x,y values
		foreach($positionEls as $positionEl) {
			$arcGraphics->addPosition($positionEl->getAttribute('x'), $positionEl->getAttribute('y'));
		}
	}
}
