<?php

namespace PetriNet\Element;

class Transition
{
	/** @var string				Unique identificator */
	private $id;
	
	/** @var string				Transition label text */
	private $label;
	
	/** @var \PetriNet\Net		PetriNet instance */
	private $net;
	
	/** @var array				Inbound arcs */
	private $inArcs = array();
	
	/** @var array				Outbound arcs */
	private $outArcs = array();
	
	/** @var \PetriNet\Graphics\AnnotationGraphics */
	private $nameGraphics;
	
	/** @var \PetriNet\Graphics\NodeGraphics */
	private $graphics;
	
	/**
	 * 
	 * @param string $id
	 * @param string $label
	 * @param \PetriNet\Net $net
	 */
	public function __construct($id, $label, \PetriNet\Net $net) {
		$this->id = $id;
		$this->label = $label;
		$this->net = $net;
		
		// Setup graphics
		$this->graphics = new \PetriNet\Graphics\NodeGraphics();
		$this->nameGraphics = new \PetriNet\Graphics\AnnotationGraphics();
	}
	
	/**
	 * Get transition id
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Get label text
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}
	
	/**
	 * Set label text
	 * @param string $label
	 */
	public function setLabel($label) {
		$this->label = $label;
	}
	
	/**
	 * Add new inbound arc
	 * @param Arc $arc
	 */
	public function addInArc(Arc $arc) {
		$this->inArcs[$arc->getId()] = $arc;
	}
	
	/**
	 * Add new outbound arc
	 * @param Arc $arc
	 */
	public function addOutArc(Arc $arc) {
		$this->outArcs[$arc->getId()] = $arc;
	}
	
	/**
	 * Get all inbound arcs
	 * @return array
	 */
	public function getInArcs() {
		return $this->inArcs;
	}
	
	/**
	 * Get all outbound arcs
	 * @return array
	 */
	public function getOutArcs() {
		return $this->outArcs;
	}
	
	/**
	 * Get graphics instance
	 * @return \PetriNet\Graphics\NodeGraphics
	 */
	public function getGraphics() {
		return $this->graphics;
	}
	
	/**
	 * Get name graphics instance
	 * @return \PetriNet\Graphics\AnnotationGraphics
	 */
	public function getNameGraphics() {
		return $this->nameGraphics;
	}
	
	/**
	 * Get parsed title
	 * @return string
	 */
	public function getParsedTitle() {
		return $this->parsedTitle;
	}
	
	/**
	 * Get parsed conditions
	 * @return array
	 */
	public function getParsedConditions() {
		return $this->parsedConditions;
	}
	
	/**************************** BEHAVIOR FUNCTIONS **************************/
	
	/**
	 * Check if transition is executable
	 * @return boolean
	 */
	public function isExecutable() {
		foreach($this->getInArcs() as $arc) {
			if($arc->getSource()->getTokens() < $arc->getInscription()) {
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Execute transition
	 */
	public function execute() {
		if(!$this->isExecutable()) {
			throw new \PetriNet\Exception\TransitionException('This Transition is not executable');
		}
		
		// Remove tokens forom sources
		foreach($this->getInArcs() as $arc) {
			$arc->getSource()->removeToken($arc->getInscription());
		}
		
		// Add tokens to targets
		foreach($this->getOutArcs() as $arc) {
			$arc->getTarget()->addToken($arc->getInscription());
		}
	}
}
