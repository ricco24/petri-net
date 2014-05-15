<?php

namespace PetriNet;

class Net
{
	/** @var string				Id of PetriNet */
	private $id;
	
	/** @var array				Array of all places */
	private $places = array();
	
	/** @var array				Array of all transitions */
	private $transitions = array();
	
	/** @var array				Array of all arcs */
	private $arcs = array();
	
	/**
	 * 
	 * @param string $id
	 */
	public function __construct($id) {
		$this->id = $id;
	}
	
	/**
	 * Get id
	 * @return sring
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Add new place
	 * @param string $id
	 * @return boolean
	 */
	public function addPlace($id, $label = NULL) {
		// Check if place doesnt exists
		if($this->getElement($id)) {
			return false;
		}
		
		// Create new place
		return $this->places[$id] = new Element\Place($id, $label, $this);
	}
	
	/**
	 * Add new transition
	 * @param string $id
	 * @return boolean
	 */
	public function addTransition($id, $label = NULL) {
		// Check if transition doesnt exists
		if($this->getElement($id)) {
			return false;
		}
		
		// Create new transition
		return $this->transitions[$id] = new Element\Transition($id, $label, $this);
	}
	
	/**
	 * Add new arc
	 * @param string $id
	 * @param Place|Transition $source
	 * @param Place|Transition $target
	 * @return boolean|\PetriNet\Arc
	 */
	public function addArc($id, $source, $target) {
		// Check if arc doesnt exists
		if($this->getElement($id)) {
			return false;
		}
		
		// Create new arc and add arc to connected objects
		$arc = $this->arcs[$id] = new Element\Arc($id, $source, $target, $this);
		$source->addOutArc($arc);
		$target->addInArc($arc);	
		
		return $arc;
	}
	
	/**
	 * Find place by given id
	 * @param string $id
	 * @return Place
	 */
	public function getPlace($id) {
		if(isset($this->places[$id])) {
			return $this->places[$id];
		}
		
		return NULL;
	}
	
	/**
	 * Find transition by given id
	 * @param string $id
	 * @return Transition
	 */
	public function getTransition($id) {
		if(isset($this->transitions[$id])) {
			return $this->transitions[$id];
		}
		
		return NULL;
	}
	
	/**
	 * Get arc by given id
	 * @param string $id
	 * @return Arc
	 */
	public function getArc($id) {
		if(isset($this->arcs[$id])) {
			return $this->arcs[$id];
		}
		
		return NULL;
	}
	
	/**
	 * Get all places
	 * @return array
	 */
	public function getPlaces() {
		return $this->places;
	}
	
	/**
	 * Get all transitions
	 * @return array
	 */
	public function getTransitions() {
		return $this->transitions;
	}
	
	/**
	 * Get all arcs
	 * @return array
	 */
	public function getArcs() {
		return $this->arcs;
	}
	
	/**
	 * Get element by id
	 * @param string $id
	 * @return element|null
	 */
	public function getElement($id) {
		$place = $this->getPlace($id);
		if($place) {
			return $place;
		}
		
		$transition = $this->getTransition($id);
		if($transition) {
			return $transition;
		}
		
		$arc = $this->getArc($id);
		if($arc) {
			return $arc;
		}
		
		return NULL;
	}
	
	/**
	 * Get all executable transitions in net
	 * @return array
	 */
	public function getExecutableTransitions() {
		$result = array();
		foreach($this->getTransitions() as $transition) {
			if($transition->isExecutable()) {
				$result[] = $transition;
			}
		}
		
		return $result;
	}
}
