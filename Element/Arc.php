<?php

namespace PetriNet\Element;

class Arc
{
	/** @var string				Unique identificator */
	private $id;
	
	/** @var \PetriNet\Net		PetriNet instance */
	private $net;
	
	/** @var \PetriNet\Graphics\AnnotationGraphics */
	private $inscriptionGraphics;
	
	/** @var \PetriNet\Graphics\ArcGraphics */
	private $graphics;
	
	/** @var Transition|Place	Place or transition instance - source object */
	private $source;
	
	/** @var Transition|Place	Place or transition instance - target object */
	private $target;
	
	/** @var int				Weight of arc */
	private $inscription = 1;
	
	/**
	 * 
	 * @param string $id
	 * @param Transition|Place $source
	 * @param Transition|Place $target
	 * @param \PetriNet\Net $net
	 * @throws \Exception
	 */
	public function __construct($id, $source, $target, $net) {
		$this->id = $id;
		$this->net = $net;
		
		// Check given objects
		$places = $transitions = 0;
		$source instanceof Place ? $places++ : NULL;
		$source instanceof Transition ? $transitions++ : NULL;
		$target instanceof  Place ? $places++ : NULL;
		$target instanceof Transition ? $transitions++ : NULL;
		
		if(!$places || !$transitions) {
			throw new \PetriNet\Exception\ArcException('Start and end object cannot be same type.');
		}

		$this->source = $source;
		$this->target = $target;
		
		// Setup graphics
		$this->graphics = new \PetriNet\Graphics\ArcGraphics();
		$this->inscriptionGraphics = new \PetriNet\Graphics\AnnotationGraphics();
	}
	
	/**
	 * Get Arc id
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Get source object
	 * @return Transition|Place
	 */
	public function getSource() {
		return $this->source;
	}
	
	/**
	 * Set source object
	 * @param Transition|Place $obj
	 */
	public function setSource($obj) {
		if($obj instanceof Place && $this->getTarget() instanceof Transition) {
			$this->source = $obj;
		} elseif($obj instanceof Transition && $this->getTarget() instanceof Place) {
			$this->source = $obj;
		} else {		
			throw new \PetriNet\Exception\ArcException('Start and end object cannot be same type.');
		}		
	}
	
	/**
	 * Get target object
	 * @return Transition|Place
	 */
	public function getTarget() {
		return $this->target;
	}
	
	/**
	 * Set target object
	 * @param Transition|Place $obj
	 */
	public function setTarget($obj) {
		if($obj instanceof Place && $this->getSource() instanceof Transition) {
			$this->source = $obj;
		} elseif($obj instanceof Transition && $this->getSource() instanceof Place) {
			$this->source = $obj;
		} else {		
			throw new \PetriNet\Exception\ArcException('Start and end object cannot be same type.');
		}		
	}
	
	/**
	 * Get graphics instance
	 * @return \PetriNet\Graphics\NodeGraphics
	 */
	public function getGraphics() {
		return $this->graphics;
	}
	
	/**
	 * Get inscription graphics instance
	 * @return \PetriNet\Graphics\AnnotationGraphics
	 */
	public function getInscriptionGraphics() {
		return $this->inscriptionGraphics;
	}
	
	/**
	 * Set inscription (weight)
	 * @param int $inscription
	 */
	public function setInscription($inscription) {
		$this->inscription = (int) $inscription;
	}
	
	/**
	 * Get inscription (weight)
	 * @return int
	 */
	public function getInscription() {
		return $this->inscription;
	}
}
