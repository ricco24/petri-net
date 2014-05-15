<?php

namespace PetriNet\Element;

class Place
{
	/** @var string				Unique identificator */
	private $id;
	
	/** @var string				Place label text */
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
	
	/** @var int				Actual tokens count */
	private $tokens = 0;
	
	/** @var int				Initial tokens count */
	private $initialMarking = 0;
	
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
		
		// Initialize graphics
		$this->graphics = new \PetriNet\Graphics\NodeGraphics();
		$this->nameGraphics = new \PetriNet\Graphics\AnnotationGraphics();
	}
	
	/**
	 * Get place id
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
	 * Get tokens count
	 * @return int
	 */
	public function getTokens() {
		return $this->tokens;
	}
	
	/**
	 * Get initial marking coung
	 * @return int
	 */
	public function getInitialMarking() {
		return $this->initialMarking;
	}
	
	/**
	 * Setup initial marking - initial tokens count
	 * @param int $initialMarking
	 * @param bool $setTokens
	 */
	public function setInitialMarking($initialMarking, $setTokens = false) {
		$this->initialMarking = (int) $initialMarking;
		if($setTokens) {
			$this->resetTokens();
		}
	}
	
	/**
	 * Increase tokens count
	 */
	public function addToken($count = 1) {
		$this->tokens += $count;
	}
	
	/**
	 * Decrease tokens count
	 */
	public function removeToken($count = 1) {
		$this->tokens -= $count;
	}
	
	/**
	 * Reset tokens count to initial marking
	 */
	public function resetTokens() {
		$this->tokens = $this->initialMarking;
	}
	
	/**
	 * Set tokens count to given count
	 * @param int $tokens
	 */
	public function setTokens($tokens) {
		$this->tokens = (int) $tokens;
	}
}
