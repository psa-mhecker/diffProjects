<?php
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Mink\Exception\ElementNotFoundException;

/**
 * backend context
 */
class BoContext extends RawMinkContext
{

    const IFRAME_RIGHT = "iframeRight";

    /**
     * @Given je saisis les champs suivants:
     */
    public function jeSaisisLesChampsSuivants(TableNode $fields)
    {
        foreach ($fields->getRowsHash() as $field => $value) {
            $this->jeSaisisAvec($field, $value);
        }
    }

    /**
     * @Given je saisis :field avec :value
     */
    public function jeSaisisAvec($field, $value)
    {
        $script = <<<JS
			jQuery( 'td:contains("$field")' ).last().nextUntil( "input" ).find("input").first().attr('value',"$value");
JS;
        $this->getSession()
            ->getDriver()
            ->executeScript($script);
    }

    /**
     * @Given je choisis :option depuis la liste :select
     * @Given I choose :option from the list :select
     */
    public function jeChoisisDepuisLaListe($select, $option)
    {
        $script = <<<JS
			var selectGabarit = jQuery( 'td:contains("$select")' ).last().nextUntil( "select" );
            jQuery('option', selectGabarit).each(function() {
                  if(jQuery(this).text() == "$option") {
                    jQuery(this).attr('selected', 'selected');
                    jQuery(this).trigger( 'change' );
              }
            });
JS;
        $this->getSession()
            ->getDriver()
            ->executeScript($script);
    }

    /**
     * @Then I count :options options from the list :select
     */
    public function iCountOptionsFromList($select, $elementNumber)
    {
        $selectElements = $this->getSession()->getPage()->findAll('css', "#".$select." option");

        if (count($selectElements) !== (int)$elementNumber) {
            throw new \Exception(sprintf("The list '%s' contains '%s' options", $select, count($selectElements)));
        }
    }

    /**
     * @Given je vais sur la partie droite
     * @Given I go to right frame
     */
    public function jeVaisSurLaPartieDroite()
    {
        $this->getSession()->switchToIFrame(self::IFRAME_RIGHT);
    }

    /**
     * @Given je vais sur la partie principale
     */
    public function jeVaisSurLaPartiePrincipale()
    {
        $this->getSession()->switchToIFrame();
    }

    /**
     * @Given I press :button into :iFrame
     */
    public function iPressButtonIntoFrame($button, $iFrame)
    {
        if ($iFrame && $button){
            $script = <<<JS
            jQuery('#$iFrame #$button').click();
JS;
            $this->getSession()
                ->getDriver()
                ->executeScript($script);
        }
        else {
            throw new \Exception("please fill in all parameters !");
        }

    }

    /**
     * @When je clique sur rubrique :field
     * @When I click on rubric :field
     */
    public function jeCliqueSurRubrique($field)
    {
        $script = <<<JS
			var pageTitle = window.jQuery( 'td:contains("$field")' ).click();
JS;
        $this->getSession()
            ->getDriver()
            ->executeScript($script);
    }
}
