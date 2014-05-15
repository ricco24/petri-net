<?php

namespace PetriNet\Graphics;

class AnnotationGraphics extends AbstractGraphics
{
	/** @var array			Available elements */
	protected $elements = array(
		'offset' => array(
			'x' => 0,
			'y' => 0
		),
		'fill' => NULL,
		'line' => NULL,
		'font' => NULL
	);
	
	/**
	 * 
	 */
	public function __construct() {
		
	}
	
	/**
	 * Get offset element
	 * @return array
	 */
	public function getOffset() {
		return $this->elements['offset'];
	}
	
	/**
	 * Get x element offset
	 * @return int
	 */
	public function getOffsetX() {
		return $this->elements['offset']['x'];
	}
	
	/**
	 * Get y element offset
	 * @return int
	 */
	public function getOffsetY() {
		return $this->elements['offset']['y'];
	}
		
	/**
	 * Setup offset element
	 * @param number $x
	 * @param number $y
	 */
	public function setOffset($x, $y) {
		$this->elements['offset'] = array(
			'x' => $this->fixNumber($x),
			'y' => $this->fixNumber($y)
		);
	}
	
	/**
	 * Get fill element
	 * @return array
	 */
	public function getFill() {
		return $this->elements['fill'];
	}	
	
	/**
	 * Setup fill element
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
	 * Reset fill element
	 */
	public function resetFill() {
		$this->elements['fill'] = NULL;
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
	
	/**
	 * Get font element
	 * @return array
	 */
	public function getFont() {
		return $this->elements['font'];
	}
	
	/**
	 * Set font element
	 * @param string $family
	 * @param string $style
	 * @param number $weight
	 * @param number $size
	 * @param string $decoration
	 * @param string $align
	 * @param number $rotation
	 */
	public function setFont($family, $style, $weight, $size, $decoration, $align, $rotation) {
		$this->elements['font'] = array(
			'family' => $family,
			'style' => $style,
			'weight' => $this->fixNumber($weight),
			'size' => $this->fixNumber($size),
			'decoration' => $this->fixDecoration($decoration),
			'align' => $this->fixAlign($align),
			'rotation' => $this->fixNumber($rotation)
		);
	}
	
	/**
	 * Reset font element
	 */
	public function resetFont() {
		$this->elements['font'] = NULL;
	}
}
