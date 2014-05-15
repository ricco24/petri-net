<?php

namespace PetriNet\Graphics;

class ArcGraphics extends AbstractGraphics
{
	/** @var array			Available elements */
	protected $elements = array(
		'position' => NULL,
		'line' => NULL
	);
	
	/**
	 * Get position element
	 * @return array
	 */
	public function getPosition() {
		return $this->elements['position'];
	}
	
	/**
	 * Add new position element
	 * @param number $x
	 * @param number $y
	 */
	public function addPosition($x, $y) {
		$this->elements['position'][] = array(
			'x' => $this->fixNumber($x),
			'y' => $this->fixNumber($y)
		);
	}
	
	/**
	 * Reset position element
	 */
	public function resetPosition() {
		$this->elements['position'] = NULL;
	}
	
	/**
	 * Get line element
	 * @return array
	 */
	public function getLine() {
		return $this->elements['line'];
	}
	
	/**
	 * Set line element
	 * @param string $shape
	 * @param string $color
	 * @param number $width
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
	 * Reset line element
	 */
	public function resetLine() {
		$this->elements['line'] = NULL;
	}
}
