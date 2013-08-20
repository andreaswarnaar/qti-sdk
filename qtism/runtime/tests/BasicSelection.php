<?php

namespace qtism\runtime\tests;

use qtism\data\SectionPartCollection;
use qtism\data\AssessmentSection;
use qtism\runtime\tests\AbstractSelection;
use \SplObjectStorage;

/**
 * The BasicSelection class implements the basic Selection logic described by QTI.
 * 
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 *
 */
class BasicSelection extends AbstractSelection {
    
    /**
     * Select the child elements of the AssessmentSection object
     * held by the Selection object.
     * 
     * If child elements are selected multiple times, they are suffixed by a QTI
     * sequence number. For instance, if assessmentItemRef 'Q01' is selected 2 times,
     * the containing AssessmentSection object will be composed of 'Q01.1' and 'Q01.2'.
     * 
     * @return AssessmentSection An AssessmentSection object on which the selection occured.
     * @throws SelectionException If the select attribute of the Selection exceeds the number of child elements but the withReplacement attribute is set to true.
     */
    public function select() {
        $assessmentSection = clone $this->getAssessmentSection();
        $selection = $assessmentSection->getSelection();
        
        if (is_null($selection) === false) {
            $sectionParts = $assessmentSection->getSectionParts();
            
            $select = $selection->getSelect();
            $childCount = count($sectionParts);
            $withReplacement = $selection->isWithReplacement();
            
            if ($select > $childCount && $withReplacement !== true) {
                $assessmentSectionIdentifier = $assessmentSection->getIdentifier();
                $msg = "The number of children to select (${select}) cannot exceed the number ";
                $msg.= "the number of child elements defined (${childCount}) in assessmentSection '${assessmentSectionIdentifier}'.";
                throw new SelectionException($msg, SelectionException::LOGIC_ERROR);
            }
            
            $indexRef = new SplObjectStorage();
            $selectionBag = $sectionParts->getArrayCopy();
            $newSectionParts = array();
            
            foreach ($selectionBag as $k => $bagItem) {
                $indexRef[$bagItem] = $k;
            }
            
            for ($i = 0; $i < $select; $i++) {
            
                $bagSize = count($selectionBag);
                $randIndex = mt_rand(0, $childCount -1);
                $newSectionParts[$indexRef[$selectionBag[$randIndex]]] = $selectionBag[$randIndex];
            
                if ($withReplacement === false) {
                    unset($selectionBag[$randIndex]);
                    $selectionBag = array_values($selectionBag);
                    $childCount--;
                }
            }
            
            ksort($newSectionParts);
            $assessmentSection->setSectionParts(new SectionPartCollection($newSectionParts));
           
        }
        
        return $assessmentSection;
    }
}