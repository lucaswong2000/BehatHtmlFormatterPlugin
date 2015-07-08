<?php
/**
 * Xml renderer for Behat report
 * @author Yanchao <yanchaowang@gmail.com>
 */

namespace emuse\BehatHTMLFormatter\Renderer ;

class XmlRenderer
{

    public function __construct()
    {
    
    }
    
    /**
     * Renders before an exercice.
     *
     * @param object   : BehatHTMLFormatter object
     * @return string  : XML generated
     */    
    public function renderBeforeExercise($obj) {
    
        $print = '<?xml version="1.0" encoding="UTF-8"?><TestIHM>' ;
        
        return $print ;
    }
    
    /**
     * Renders after an exercice.
     *
     * @param object   : BehatHTMLFormatter object
     * @return string  : HTML generated
     */        
    public function renderAfterExercise($obj) {
        //--> features results
        $strFeatPassed = '' ;
        if (count($obj->getPassedFeatures()) > 0) {
            $strFeatPassed = '<Passed>'.count($obj->getPassedFeatures()).' </Passed>';
        }      
        
        $strFeatFailed = '<Failed>0</Failed>' ;    
        $sumRes = 'passed' ;        
        if (count($obj->getFailedFeatures()) > 0) {
            $strFeatFailed = '<Failed>'.count($obj->getFailedFeatures()).' </Failed>';
            $sumRes = 'failed' ;
        } 
        
        //--> scenarios results
        $strScePassed = '<Passed>0</Passed>' ;
        if (count($obj->getPassedScenarios()) > 0) {
            $strScePassed = '<Passed>'.count($obj->getPassedScenarios()).'</Passed>';
        }             
        
        $strSceFailed = '<Failed>0</Failed>' ;
        if (count($obj->getFailedScenarios()) > 0) {
            $strSceFailed = '<Failed>'.count($obj->getFailedScenarios()).' </Failed>';
        } 
        
        //--> steps results
        $strStepsPassed = '<Passed>0</Passed>' ;
        if (count($obj->getPassedSteps()) > 0) {
            $strStepsPassed = '<Passed>'.count($obj->getPassedSteps()).'</Passed>';
        }             
        
        $strStepsPending = '<Pending>0</Pending>' ;
        if (count($obj->getPendingSteps()) > 0) {
            $strStepsPending = '<Pending>'.count($obj->getPendingSteps()).'</Pending>';
        } 

        $strStepsSkipped = '<Skipped>0</Skipped>' ;
        if (count($obj->getSkippedSteps()) > 0) {
            $strStepsSkipped = '<Skipped>'.count($obj->getSkippedSteps()).'</Skipped>';
        } 
        
        $strStepsFailed = '<Failed>0</Failed>' ;
        if (count($obj->getFailedSteps()) > 0) {
            $strStepsFailed = '<Failed>'.count($obj->getFailedSteps()).'</Failed>';
        } 
        
        
        //totals
        $featTotal = (count($obj->getFailedFeatures()) + count($obj->getPassedFeatures()));
        $sceTotal = (count($obj->getFailedScenarios()) + count($obj->getPassedScenarios())) ;
        $stepsTotal = (count($obj->getFailedSteps()) + count($obj->getPassedSteps()) + count($obj->getSkippedSteps()) + count($obj->getPendingSteps())) ;

        //list of pending steps to display
        $strPendingList = '' ;
        if (count($obj->getPendingSteps()) > 0) {
            foreach($obj->getPendingSteps() as $pendingStep) {
                $strPendingList .= '
                    <PendingStep>' . $pendingStep->getKeyword() . ' ' . $pendingStep->getText() . '</PendingStep>' ;
            }
                $strPendingList = '<PendingSteps>' . $strPendingList . '</PendingSteps>';
        }

        
        $print = '
        <Summary result="'.$sumRes.'">
			<Features>'.$strFeatPassed.$strFeatFailed.'</Features>
			<Scenarios>'.$strScePassed.$strSceFailed.'</Scenarios>
			<Steps>'.$strStepsPassed.$strStepsFailed. $strStepsSkipped.'</Steps>
			<time>'.$obj->getTimer().'</time>
			<memory>'.$obj->getMemory().'</memory>
		</Summary></TestIHM>';    

        return $print ;
    }
    
    
    /**
     * Renders before a suite.
     *
     * @param object   : BehatHTMLFormatter object
     * @return string  : HTML generated
     */        
    public function renderBeforeSuite($obj) {
        $print = '
        <Suite name="' . htmlspecialchars( $obj->getCurrentSuite()->getName(), ENT_XML1) . '">';
        
        return $print ;
    
    }     

    /**
     * Renders after a suite.
     *
     * @param object   : BehatHTMLFormatter object
     * @return string  : HTML generated
     */     
    public function renderAfterSuite($obj) {
        return '</Suite>' ;
    } 
    
    /**
     * Renders before a feature.
     *
     * @param object   : BehatHTMLFormatter object
     * @return string  : HTML generated
     */        
    public function renderBeforeFeature($obj) {
    
        //feature head
		$print = '
		<Feature id="'.$obj->getCurrentFeature()->getId().'" result="'. $obj->getCurrentFeature()->getPassedClass()  . '" title="'. htmlspecialchars($obj->getCurrentFeature()->getName(), ENT_XML1) . '">';
        return $print ;
    }     

    /**
     * Renders after a feature.
     *
     * @param object   : BehatHTMLFormatter object
     * @return string  : HTML generated
     */     
    public function renderAfterFeature($obj) {
		$print = '';
		$print .= '<results overall="'. $obj->getCurrentFeature()->getPassedClass() .'">'; 		
		if ($obj->getCurrentFeature()->getTotalAmountOfScenarios() > 0 && $obj->getCurrentFeature()->getPassedClass() === 'failed') {
			$print .= '<PassedPercent>'. round($obj->getCurrentFeature()->getPercentPassed(), 2) . '%</PassedPercent>';
		}
		$print .='</results></Feature>';
        return $print ;
    }    

	
    /**
     * Renders before a scenario.
     *
     * @param object   : BehatHTMLFormatter object
     * @return string  : HTML generated
     */            
    public function renderBeforeScenario($obj) {
        //scenario head
//        $print = '
//            <div class="scenario">
//                <ul class="tags">' ;
//        foreach($obj->getCurrentScenario()->getTags() as $tag) {
//            $print .= '
//                    <li>@' . $tag .'</li>';
//        }         
//        $print .= '
//                </ul>';        
//        
//        $print .= '
//                <h3>
//                    <span class="keyword">' . $obj->getCurrentScenario()->getId() . ' Scenario: </span>
//                    <span class="title">' . $obj->getCurrentScenario()->getName() . '</span>
//                </h3>
//                <ol>' ;
        
        $print = '<Scenario name="' .  htmlspecialchars($obj->getCurrentSuite()->getName() .'--'.$obj->getCurrentFeature()->getName() .'--'. $obj->getCurrentScenario()->getName(), ENT_XML1) .'"> ';
        //$print .= 'result="';
        //$print .= $obj->getCurrentScenario()->isPassed();
		
        return $print ;
    }     

    /**
     * Renders after a scenario.
     *
     * @param object   : BehatHTMLFormatter object
     * @return string  : HTML generated
     */     
    public function renderAfterScenario($obj) {
	if ($obj->getCurrentScenario()->isPassed() == 1){
		$passed = "Passed";
	}else{
		$passed = "Failed";
	}
	$print = '<results overall="'.$passed.'">';
        $print .= '</results></Scenario>';
        
        return $print ;
    }   
    
    /**
     * Renders before an outline.
     *
     * @param object   : BehatHTMLFormatter object
     * @return string  : HTML generated
     */            
    public function renderBeforeOutline($obj) {
        return '' ;
    }
    
    /**
     * Renders after an outline.
     *
     * @param object   : BehatHTMLFormatter object
     * @return string  : HTML generated
     */     
    public function renderAfterOutline($obj) {
        return '' ;
    } 
    
    /**
     * Renders before a step.
     *
     * @param object   : BehatHTMLFormatter object
     * @return string  : HTML generated
     */        
    public function renderBeforeStep($obj) {

        return '' ;
    }
    
    /**
     * Renders after a step.
     *
     * @param object   : BehatHTMLFormatter object
     * @return string  : HTML generated
     */        
    public function renderAfterStep($obj) {

        $steps = $obj->getCurrentScenario()->getSteps() ;
        $step = end($steps) ; //needed because of strict standards

        //path displayed only if available (it's not available in undefined steps)
        $strPath = '' ;
        if ($step->getDefinition() !== NULL ) {
            $strPath = $step->getDefinition()->getPath() ;
        } 
        
        $stepResultClass = '' ;
        if ($step->isPassed()) { 
            $stepResultClass = 'passed' ;
        }
        if ($step->isFailed()) { 
            $stepResultClass = 'failed' ;
        }
        if ($step->isSkipped()) { 
            $stepResultClass = 'skipped' ;
        }
        if ($step->isPending()) { 
            $stepResultClass = 'pending' ;
        }
        
		$print = '<Step keyword="'. $step->getKeyWord() .'" result="'.$stepResultClass.'">';
		$print .='<text>'.htmlspecialchars($step->getText(), ENT_XML1).'</text>';
		$print .='<path>'.$strPath.'</path>';
        $print .="</Step>";
		
        return $print ;
    }   
    
}
