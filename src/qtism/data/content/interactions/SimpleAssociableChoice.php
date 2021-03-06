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
 * Copyright (c) 2013-2015 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 * @license GPLv2
 */

namespace qtism\data\content\interactions;

use qtism\common\collections\IdentifierCollection;
use qtism\data\content\FlowStaticCollection;
use \InvalidArgumentException;

/**
 * The simpleAssociableChoice QTI class.
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 *
 */
class SimpleAssociableChoice extends Choice implements AssociableChoice
{
    /**
	 * The elements composing the SimpleAssociableChoice.
	 *
	 * @var \qtism\data\content\FlowStaticCollection
	 * @qtism-bean-property
	 */
    private $content;

    /**
	 * From IMS QTI:
	 *
	 * The maximum number of choices this choice may be associated with.
	 * If matchMax is 0 then there is no restriction.
	 *
	 * @var integer
	 * @qtism-bean-property
	 */
    private $matchMax;

    /**
	 * From IMS QTI:
	 *
	 * The minimum number of choices this choice must be associated with
	 * to form a valid response. If matchMin is 0 then the candidate is not
	 * required to associate this choice with any others at all. matchMin
	 * must be less than or equal to the limit imposed by matchMax.The
	 * minimum number of choices this choice must be associated with to
	 * form a valid response. If matchMin is 0 then the candidate is not
	 * required to associate this choice with any others at all. matchMin
	 * must be less than or equal to the limit imposed by matchMax.
	 *
	 * @var integer
	 * @qtism-bean-property
	 */
    private $matchMin = 0;
    
    /**
     * From IMS QTI:
     * 
     * A set of choices that this choice may be associated with, all others are 
     * excluded. If no matchGroup is given, or if it is empty, then all other 
     * choices may be associated with this one subject to their own matching 
     * constraints.
     * 
     * @var \qtism\common\collections\IdentifierCollection
     * @qtism-bean-property
     */
    private $matchGroup;

    /**
	 * Create a new SimpleAssociableChoice object.
	 *
	 * @param string $identifier The identifier of the choice.
	 * @param integer $matchMax A positive (>= 0) integer.
	 * @param string $id The id of the bodyElement.
	 * @param string $class The class of the bodyElement.
	 * @param string $lang The lang of the bodyElement.
	 * @param string $label The label of the bodyElement.
	 * @throws \InvalidArgumentException
	 */
    public function __construct($identifier, $matchMax, $id = '', $class = '', $lang = '', $label = '')
    {
        parent::__construct($identifier, $id, $class, $lang, $label);
        $this->setMatchMax($matchMax);
        $this->setMatchMin(0);
        $this->setContent(new FlowStaticCollection());
        $this->setMatchGroup(new IdentifierCollection());
    }

    /**
	 * Set the matchMax attribute.
	 *
	 * @param integer $matchMax A positive (>= 0) integer.
	 * @throws \InvalidArgumentException If $matchMax is not a positive integer.
	 */
    public function setMatchMax($matchMax)
    {
        if (is_int($matchMax) === true && $matchMax >= 0) {
            $this->matchMax = $matchMax;
        } else {
            $msg = "The 'matchMax' argument must be a positive (>= 0) integer, '" . gettype($matchMax) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
	 * Get the matchMax attribute.
	 *
	 * @return integer A positive (>= 0) integer.
	 */
    public function getMatchMax()
    {
        return $this->matchMax;
    }

    /**
	 * Get the matchMin attribute.
	 *
	 * @param integer $matchMin A positive (>= 0) integer.
	 * @throws \InvalidArgumentException If $matchMin is not a positive integer.
	 */
    public function setMatchMin($matchMin)
    {
        if (is_int($matchMin) === true && $matchMin >= 0) {
            $this->matchMin = $matchMin;
        } else {
            $msg = "The 'matchMin' argument must be a positive (>= 0) integer, '" . gettype($matchMin);
            throw new InvalidArgumentException($msg);
        }
    }

    /**
	 * @see \qtism\data\QtiComponent::getComponents()
	 */
    public function getComponents()
    {
        return $this->getContent();
    }

    /**
	 * Set the elements composing the simpleAssociableChoice.
	 *
	 * @param \qtism\data\content\FlowStaticCollection $content A collection of FlowStatic objects.
	 */
    public function setContent(FlowStaticCollection $content)
    {
        $this->content = $content;
    }

    /**
	 * Get the elements composing the simpleAssociableChoice.
	 *
	 * @return \qtism\data\content\FlowStaticCollection A collection of FlowStatic objects.
	 */
    public function getContent()
    {
        return $this->content;
    }

    /**
	 * Set the matchMin attribute.
	 *
	 * @return integer A positive (>= 0) integer.
	 */
    public function getMatchMin()
    {
        return $this->matchMin;
    }
    
    /**
     * @see \qtism\data\content\interactions\AssociableChoice::setMatchGroup()
     */
    public function setMatchGroup(IdentifierCollection $matchGroup)
    {
        $this->matchGroup = $matchGroup;
    }
    
    /**
     * @see \qtism\data\content\interactions\AssociableChoice::getMatchGroup()
     */
    public function getMatchGroup()
    {
        return $this->matchGroup;
    }

    /**
	 * @see \qtism\data\QtiComponent::getQtiClassName()
	 */
    public function getQtiClassName()
    {
        return 'simpleAssociableChoice';
    }
}
