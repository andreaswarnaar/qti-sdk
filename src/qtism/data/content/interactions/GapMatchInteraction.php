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
 * Copyright (c) 2013-2016 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 * @license GPLv2
 */

namespace qtism\data\content\interactions;

use qtism\data\QtiComponentCollection;
use qtism\data\state\ResponseValidityConstraint;
use qtism\data\state\AssociationValidityConstraint;
use qtism\data\content\BlockStaticCollection;
use \InvalidArgumentException;

/**
 * From IMS QTI:
 *
 * A gap match interaction is a blockInteraction that contains a number gaps that
 * the candidate can fill from an associated set of choices. The candidate must
 * be able to review the content with the gaps filled in context, as indicated
 * by their choices.
 *
 * The gapMatchInteraction must be bound to a response variable with base-type
 * directedPair and either single or multiple cardinality, depending on the number
 * of gaps. The choices represent the source of the pairing and gaps the targets.
 * Each gap can have at most one choice associated with it. The maximum occurrence
 * of the choices is controlled by the matchMax attribute of gapChoice.
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 *
 */
class GapMatchInteraction extends BlockInteraction
{
    /**
     * From IMS QTI:
     *
     * If the shuffle attribute is true then the delivery engine must randomize
     * the order in which the choices (not the gaps) are initially presented,
     * subject to the value of the fixed attribute of each choice.
     *
     * @var boolean
     * @qtism-bean-property
     */
    private $shuffle = false;

    /**
     * From IMS QTI:
     *
     * An ordered list of choices for filling the gaps. There may be fewer choices
     * than gaps if required.
     *
     * @var GapChoiceCollection
     * @qtism-bean-property
     */
    private $gapChoices;

    /**
     * From IMS QTI:
     *
     * The content of the interaction is simply a piece of content that contains
     * the gaps. If the block contains more than one gap then the interaction must
     * be bound to a response with multiple cardinality.
     *
     * @var \qtism\data\content\BlockStaticCollection
     * @qtism-bean-property
     */
    private $content;

    /**
     * Create a new GapMatchInteraction object.
     *
     * @param string $responseIdentifier The identifier of the associated response variable.
     * @param \qtism\data\content\interactions\GapChoiceCollection $gapChoices The GapChoices object composing the GapMatchInteraction.
     * @param \qtism\data\content\BlockStaticCollection $content The BlockStatic objects composing the GapMatchInteraction.
     * @param string $id The body of the bodyElement.
     * @param string $class The class of the bodyElement.
     * @param string $lang The language of the bodyElement.
     * @param string $label The label of the bodyElement.
     * @throws \InvalidArgumentException If any of the arguments is invalid.
     */
    public function __construct($responseIdentifier, GapChoiceCollection $gapChoices, BlockStaticCollection $content, $id = '', $class = '', $lang = '', $label = '')
    {
        parent::__construct($responseIdentifier, $id, $class, $lang, $label);
        $this->setGapChoices($gapChoices);
        $this->setContent($content);
        $this->setShuffle(false);
    }

    /**
     * Set whether the delivery engine must randomize the order in which the choices (not
     * the gaps) are initially presented.
     *
     * @param boolean $shuffle A boolean value.
     * @throws \InvalidArgumentException If $shuffle is not a boolean value.
     */
    public function setShuffle($shuffle)
    {
        if (is_bool($shuffle) === true) {
            $this->shuffle = $shuffle;
        } else {
            $msg = "The 'shuffle' argument must be a boolean value, '" . gettype($shuffle) . "' given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Whether the delivery engine must randomize the order in which the choices (not
     * the gaps) are initially presented.
     *
     * @return boolean
     */
    public function mustShuffle()
    {
        return $this->shuffle;
    }

    /**
     * Set the collection of choices for filling the gaps.
     *
     * @param \qtism\data\content\interactions\GapChoiceCollection $gapChoices A collection of at least one GapChoice object.
     * @throws \InvalidArgumentException If $gapChoices is empty.
     */
    public function setGapChoices(GapChoiceCollection $gapChoices)
    {
        if (count($gapChoices) > 0) {
            $this->gapChoices = $gapChoices;
        } else {
            $msg = "A GapMatchInteraction object must be composed of at least one GapChoice object, none given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Get the collection of choices for filling the gaps.
     *
     * @return \qtism\data\content\interactions\GapChoiceCollection A collection of at least one GapChoice object.
     */
    public function getGapChoices()
    {
        return $this->gapChoices;
    }

    /**
     * Get the content of the interaction as simply a piece of content that contains the gaps.
     *
     * @param \qtism\data\content\BlockStaticCollection $content A collection of at least one BlockStatic object.
     * @throws \InvalidArgumentException If $content is empty.
     */
    public function setContent(BlockStaticCollection $content)
    {
        if (count($content) > 0) {
            $this->content = $content;
        } else {
            $msg = "A GapMatchInteraction object must be composed of at lease one BlockStatic object, none given.";
            throw new InvalidArgumentException($msg);
        }
    }

    /**
     * Set the content of the interaction as simply a piece of content that contains the gaps.
     *
     * @return \qtism\data\content\BlockStaticCollection A collection of at least one BlockStatic object.
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * @see \qtism\data\content\interactions\Interaction::getResponseValidityConstraint()
     */
    public function getResponseValidityConstraint()
    {
        $responseValidityConstraint = new ResponseValidityConstraint(
            $this->getResponseIdentifier(),
            0,
            0
        );
        
        foreach ($this->getComponentsByClassName(array('gapImg', 'gapText')) as $gapChoice) {
            $responseValidityConstraint->addAssociationValidityConstraint(
                new AssociationValidityConstraint(
                    $gapChoice->getIdentifier(),
                    $gapChoice->getMatchMin(),
                    $gapChoice->getMatchMax()
                )
            );
        }
        
        return $responseValidityConstraint;
    }

    /**
     * @see \qtism\data\content\interactions\BlockInteraction::getComponents()
     */
    public function getComponents()
    {
        $parentComponents = parent::getComponents();

        return new QtiComponentCollection(array_merge($parentComponents->getArrayCopy(), $this->getGapChoices()->getArrayCopy(), $this->getContent()->getArrayCopy()));
    }

    /**
     * @see \qtism\data\QtiComponent::getQtiClassName()
     */
    public function getQtiClassName()
    {
        return 'gapMatchInteraction';
    }
}
