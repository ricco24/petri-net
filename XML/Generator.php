<?php

namespace PetriNet\Xml;

class Generator
{
	/** @var PetriNet\Net		Net to generate xml from */
	private $net;
	
	/** @var \DOMDocument */
	private $dom;
	
	/**
	 * 
	 * @param \PetriNet\Net $net
	 */
	public function __construct(\PetriNet\Net $net) {
		$this->net = $net;
	}
	
	/**
	 * Create element shortcut function
	 * @param string $name
	 * @param string $value
	 * @param \DOMElement $connectTo
	 * @return \DOMElement
	 */
	private function createElement($name, $value = NULL, $connectTo = NULL) {
		$el = $this->getDOM()->createElement($name, $value);		
		$connectTo ? $connectTo->appendChild($el) : NULL;	
		return $el;
	}
	
	/**
	 * Add attribute shortcut function
	 * @param string $name
	 * @param string $value
	 * @param \DOMElement $connectTo
	 * @return \DOMAttr
	 */
	private function addAttr($name, $value, $connectTo) {
		$attr = $this->getDOM()->createAttribute($name);
		$attr->value = $value;		
		$connectTo->appendChild($attr);
		
		return $attr;
	}
	
	/**
	 * Get given net
	 * @return \PetriNet\Net
	 */
	private function getNet() {
		return $this->net;
	}
	
	/**
	 * Get or create dom object
	 * @return type
	 */
	private function getDOM($reset = false) {
		if(!$this->dom || $reset) {
			$this->dom = new \DOMDocument('1.0', 'UTF-8');
		}
		
		return $this->dom;
	}
	
	/**
	 * Create pnml element
	 * @return \DOMElement
	 */
	private function createPnml() {
		$pnml = $this->createElement('pnml');
		$this->getDOM()->appendChild($pnml);
		
		return $pnml;
	}
	
	/**
	 * Create net element
	 * @param \DOMElement $connectTo
	 * @return \DOMElement
	 */
	private function createNet($connectTo) {
		$net = $this->createElement('net', null, $connectTo);		
	
		// Setup attrs
		$this->addAttr('type', 'VIPschema.xsd', $net);
		$this->addAttr('id', $this->getNet()->getId(), $net);
		
		return $net;
	}
	
	/**
	 * Create graphics elements
	 * @param \PetriNet\Graphics $graphicsObj
	 * @param \DOMElement $connectTo
	 * @return \DOMElement
	 */
	private function createGraphics($graphicsObj, $connectTo) {
		$graphicsEl = $this->createElement('graphics', null, $connectTo);
		
		// Append active elements
		foreach($graphicsObj->getElements() as $elName => $elData) {
			// If element data is set to null, skip
			if(!$elData) {
				continue;
			}
			
			// If data is arc graphics position
			if($graphicsObj instanceof \PetriNet\Graphics\ArcGraphics && $elName == 'position') {
				foreach($elData as $nestedData) {
					$graphicsSubEl = $this->createElement($elName, null, $graphicsEl);

					// Create element attrs
					foreach($nestedData as $attrName => $attrValue) {
						if($attrValue !== NULL) {			
							$this->addAttr($attrName, $attrValue, $graphicsSubEl);
						}
					}
				}
			} else { // If data is simple array		
				$graphicsSubEl = $this->createElement($elName, null, $graphicsEl);

				// Create element attrs
				foreach($elData as $attrName => $attrValue) {
					if($attrValue !== NULL) {			
						$this->addAttr($attrName, $attrValue, $graphicsSubEl);
					}
				}
			}
		}
		
		return $graphicsEl;
	}
	
	/**
	 * Create place
	 * @param \PetriNet\Element\Place $placeObj
	 * @param \DOMElement $connectTo
	 */
	private function createPlace($placeObj, $connectTo) {
		$placeEl = $this->createElement('place', null, $connectTo);
		$this->addAttr('id', $placeObj->getId(), $placeEl);
		
		// Graphic element
		$this->createGraphics($placeObj->getGraphics(), $placeEl);
		
		// Name element
		$nameEl = $this->createElement('name', null, $placeEl);
		$this->createElement('value', $placeObj->getLabel(), $nameEl);
		
		// Initial marking element
		$iMarkingEl = $this->createElement('initialMarking', null, $placeEl);
		$tokenEl = $this->createElement('token', null, $iMarkingEl);
		$this->createElement('value', $placeObj->getTokens(), $tokenEl);
		
		// Name graphics element
		$this->createGraphics($placeObj->getNameGraphics(), $nameEl);
	}
	
	/**
	 * Create transition
	 * @param \PetriNet\Element\Transition $transitionObj
	 * @param \DOMElement $connectTo
	 */
	private function createTransition($transitionObj, $connectTo) {
		$transitionEl = $this->createElement('transition', null, $connectTo);
		$this->addAttr('id', $transitionObj->getId(), $transitionEl);
		
		// Graphic element
		$this->createGraphics($transitionObj->getGraphics(), $transitionEl);
		
		// Name element
		$nameEl = $this->createElement('name', null, $transitionEl);
		$this->createElement('value', $transitionObj->getLabel(), $nameEl);
		
		// Name graphic element
		$this->createGraphics($transitionObj->getNameGraphics(), $nameEl);
	} 
	
	/**
	 * Create arc 
	 * @param \PetriNet\Element\Arc $arcObj
	 * @param \DOMElement $connectTo
	 */
	private function createArc($arcObj, $connectTo) {
		$arcEl = $this->createElement('arc', null, $connectTo);
		
		// Graphic element
		$this->createGraphics($arcObj->getGraphics(), $arcEl);
		
		// Setup attrs
		$this->addAttr('id', $arcObj->getId(), $arcEl);
		$this->addAttr('source', $arcObj->getSource()->getId(), $arcEl);
		$this->addAttr('target', $arcObj->getTarget()->getId(), $arcEl);
		
		// Setup inscription
		$inscriptionEl = $this->createElement('inscription', null, $arcEl);
		$this->createElement('value', $arcObj->getInscription(), $inscriptionEl);
		$this->createGraphics($arcObj->getInscriptionGraphics(), $inscriptionEl);
	}
	
	/**
	 * Generate XML
	 */
	public function generate() {
		$dom = $this->getDOM(true);

		// Construct default elements
		$pnml = $this->createPnml();
		$net = $this->createNet($pnml);
		
		// Create places
		foreach($this->net->getPlaces() as $place) {
			$this->createPlace($place, $net);
		}
		
		// Create transitions 
		foreach($this->net->getTransitions() as $transition) {
			$this->createTransition($transition, $net);
		}
		
		// Create arcs
		foreach($this->net->getArcs() as $arc) {
			$this->createArc($arc, $net);
		}
		
		return $dom->saveXML();
	}
}
