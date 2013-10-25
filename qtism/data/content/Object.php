<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 * Copyright (c) 2013 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Jérôme Bogaerts, <jerome@taotesting.com>
 * @license GPLv2
 * @package
 */

namespace qtism\data\content;

use qtism\common\utils\Format;
use \InvalidArgumentException;

/**
 * From IMS QTI:
 * 
 * The object QTI class.
 * 
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 *
 */
class Object extends BodyElement implements FlowStatic, InlineStatic {
	
	/**
	 * The content elements of the object.
	 * 
	 * @var ObjectFlowCollection
	 */
	private $components;
	
	/**
	 * From IMS QTI:
	 * 
	 * The data attribute provides a URI for locating the data 
	 * associated with the object.
	 * 
	 * @var string
	 * @qtism-bean-property
	 */
	private $data;
	
	/**
	 * The mime-type.
	 * 
	 * @var string
	 * @qtism-bean-property
	 */
	private $type;
	
	/**
	 * The width. -1 means no height was provided.
	 * 
	 * @var integer
	 * @qtism-bean-property
	 */
	private $width = -1;
	
	/**
	 * The height. -1 means no height was provided.
	 * 
	 * @var integer
	 * @qtism-bean-property
	 */
	private $height = -1;
	
	/**
	 * Create a new Object object.
	 * 
	 * @param string $data The URI for locating the data of the object.
	 * @param string $type The mime-type of the object.
	 * @param string $id The id of the bodyElement.
	 * @param string $class The class of the bodyElement.
	 * @param string $lang The language of the bodyElement.
	 * @param string $label The label of the bodyElement.
	 */
	public function __construct($data, $type, $id = '', $class = '', $lang = '', $label = '') {
		parent::__construct($id, $class, $lang, $label);
		$this->setData($data);
		$this->setType($type);
		$this->setWidth(-1);
		$this->setHeight(-1);
	}
	
	/**
	 * Set the URI for locating the data of the object.
	 * 
	 * @param string $data The URI for locating the data of the object.
	 * @throws InvalidArgumentException If $data is not a URI.
	 */
	public function setData($data) {
		if (Format::isUri($data) === true) {
			$this->data = $data;
		}
		else {
			$msg = "The 'data' argument must be a non-empty string, '" . gettype($data) . "' given.";
			throw new InvalidArgumentException($msg);
		}
	}
	
	/**
	 * Get the URI for locating the data of the object.
	 * 
	 * @return string A URI.
	 */
	public function getData() {
		return $this->data;
	}
	
	/**
	 * Set the mime-type of the object.
	 * 
	 * @param string $type A mime-type.
	 * @throws InvalidArgumentException If $type is not a valid mime-type.
	 */
	public function setType($type) {
		if (is_string($type) === true && empty($type) === false) {
			$this->type = $type;
		}
		else {
			$msg = "The 'type' argument must be a non-empty string, '" . gettype($type) . "' given.";
			throw new InvalidArgumentException($msg);
		}
	}
	
	/**
	 * Get the mime-type of the object.
	 * 
	 * @return string A mime-type.
	 */
	public function getType() {
		return $this->type;
	}
	
	/**
	 * Set the width of the object. A negative value describes that no width
	 * is provided.
	 * 
	 * @param integer $width A width.
	 * @throws InvalidArgumentException
	 */
	public function setWidth($width) {
		if (is_int($width) === true) {
			$this->width = $width;
		}
		else {
			$msg = "The 'width' argument must be an integer, '" . gettype($width) . "' given.";
			throw new InvalidArgumentException($msg);
		}
	}
	
	/**
	 * Get the width of the object. A negative value describes that no width
	 * is provided.
	 * 
	 * @return integer A width.
	 */
	public function getWidth() {
		return $this->width;
	}
	
	/**
	 * Whether a width is defined for the object.
	 * 
	 * @return boolean.
	 */
	public function hasWidth() {
		return $this->width >= 0;
	}
	
	/**
	 * Set the height of the object. A negative value describes that no height
	 * is provided.
	 * 
	 * @param integer $height A height.
	 * @throws InvalidArgumentException If $height is not an integer value.
	 */
	public function setHeight($height) {
		if (is_int($width) === true) {
			$this->height = $height;
		}
		else {
			$msg = "The 'height' argument must be an integer, '" . gettype($height) . "' given.";
			throw new InvalidArgumentException($msg);
		}
	}
	
	/**
	 * Get the width of the object. A negative value describes that no height is 
	 * provided.
	 * 
	 * @return integer A height.
	 */
	public function getHeight() {
		return $this->height;
	}
	
	/**
	 * Whether the object has a height.
	 * 
	 */
	public function hasHeight() {
		return $this->height >= 0;
	}
	
	/**
	 * Get the components composing the Object.
	 * 
	 * @return ObjectFlowCollection A collection of ObjectFlow objects.
	 */
	public function getComponents() {
		return $this->components;
	}
	
	/**
	 * Set the components composing the Object.
	 * 
	 * @param ObjectFlowCollection $components
	 */
	public function setComponents(ObjectFlowCollection $components) {
		$this->components = $components;
	}
	
	public function getQtiClassName() {
		return 'object';
	}
}