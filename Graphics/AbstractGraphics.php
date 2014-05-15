<?php

namespace PetriNet\Graphics;

abstract class AbstractGraphics
{	
	protected $elements;
	
	public function getElements() {
		return $this->elements;
	}
	
	protected function fixNumber($number) {
		return (int) $number;
	}
	
	protected function fixShape($shape) {
		if(!in_array($shape, array('line', 'curve'))) {
			$shape = 'line';
		}
		
		return $shape;
	}
	
	protected function fixStyle($style) {
		if(!in_array($style, array('solid', 'dash', 'dot'))) {
			$style = 'solid';
		}
		
		return $style;
	}
	
	protected function fixGradientRotation($gradientRotation) {
		if(!in_array($gradientRotation, array('vertical', 'horizontal', 'diagonal'))) {
			$gradientRotation = 'vertical';
		}
		
		return $gradientRotation;
	}
	
	protected function fixDecoration($decoration) {
		if(!in_array($decoration, array('underline', 'overline', 'line-through'))) {
			$decoration = 'underline';
		}
		
		return $decoration;
	}
	
	protected function fixAlign($align) {
		if(!in_array($align, array('left', 'center', 'right'))) {
			$align = 'left';
		}
		
		return $align;
	}
}
