<?php

namespace PetriNet\Graphics;

class NodeGraphics extends AbstractGraphics
{
	/** @var array			Available elements */
	protected $elements = array(
		'position' => array(
			'x' => 0,
			'y' => 0
		),
		'dimension' => NULL,		
		'fill' => NULL,
		'line' => NULL
	);
	
	/**
	 * 
	 */
	public function __construct() {

	}
	
	/**
	 * Get position element
	 * @return array
	 */
	public function getPosition() {
		return $this->elements['position'];
	}
	
	/**
	 * Get x element position
	 * @return int
	 */
	public function getPositionX() {
		return $this->elements['position']['x'];
	}
	
	/**
	 * Get y element position
	 * @return int
	 */
	public function getPositionY() {
		return $this->elements['position']['y'];
	}

	/**
	 * Setup position
	 * @param numeric $x
	 * @param numeric $y
	 */
	public function setPosition($x, $y) {		
		$this->elements['position'] = array(
			'x' => $this->fixNumber($x),
			'y' => $this->fixNumber($y)
		);
	}
	
	/**
	 * Get dimension
	 * @return array
	 */
	public function getDimension() {
		return $this->elements['dimension'];
	}
	
	/**
	 * Setup dimension
	 * @param numeric $x
	 * @param numeric $y
	 */
	public function setDimension($x, $y) {	
		$this->elements['dimension'] = array(
			'x' => $this->fixNumber($x),
			'y' => $this->fixNumber($y)
		);
	}
	
	/**
	 * Reset dimenison element to null
	 */
	public function resetDimension() {
		$this->elements['dimension'] = NULL;
	}
	
	/**
	 * Get fill
	 * @return array
	 */
	public function getFill() {
		return $this->elements['fill'];
	}
	
	/**
	 * Setup fill
	 * @param string $color
	 * @param string $image
	 * @param string $gradientColor
	 * @param string $gradientRotation
	 */
	public function setFill($color, $image, $gradientColor, $gradientRotation) {
		$this->elements['fill'] = array(
			'color' => $color,
			'image' => $image,
			'gradient-color' => $gradientColor,
			'gradient-rotation' => $this->fixGradientRotation($gradientRotation)
		);
	}
	
	/**
	 * Reset fill element to null
	 */
	public function resetFill() {
		$this->elements['fill'] = NULL;
	}
	
	/**
	 * Get line
	 * @return array
	 */
	public function getLine() {
		return $this->elements['line'];
	}
	
	/**
	 * Setup line
	 * @param string $shape
	 * @param string $color
	 * @param numeric $width
	 * @param string $style
	 */
	public function setLine($shape, $color, $width, $style) {
		$this->elements['line'] = array(
			'shape' => $this->fixShape($shape),
			'color' => $color,
			'width' => $this->fixNumber($width),
			'style' => $this->fixStyle($style)
		);
	}

	/**
	 * Reset line element to null
	 */
	public function resetLine() {
		$this->elements['line'] = NULL;
	}
}
