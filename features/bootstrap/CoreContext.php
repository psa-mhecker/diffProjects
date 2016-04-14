<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\MinkContext;

/**
 * core context
 */
class CoreContext extends MinkContext implements Context, SnippetAcceptingContext
{

    const IFRAME_RIGHT = "iframeRight";

    const TIME_WAIT = 3000;

    /**
     * @When je parcours :value
     */
    public function jeParcours($value)
    {
        $element = $this->getSession()
            ->getPage()
            ->find('named', array(
            'link',
            $value,
        ));
        if (null === $element) {
            throw new \Exception(sprintf('Could not find element with value: "%s"', $value));
        }
        $element->click();
    }

    /**
     * @Given j'attends
     * @Given I wait
     */
    public function jAttends()
    {
        $this->getSession()->wait(self::TIME_WAIT);
    }

    /**
     * @Given j'attends :value secondes
     * @Given I wait :values seconds
     */
    public function jAttendsSecondes($value)
    {
        $this->getSession()->wait($value * 1000);
    }

    /**
     * @When /^I wait for element name "([^"]*)"$/
     */
    public function iWaitForElementName($name)
    {
        $this->spin($name);
    }

    /**
     * @Given je clique sur :field
     * @Given I click on :field
     */
    public function jeCliqueSur($field)
    {
        $element = $this->getSession()->getPage()->findById($field);
        if (null === $element) {
    	   throw new \Exception(sprintf('Could not evaluate element with ID: "%s"', $field));
        }
                $script = <<<JS
            window.jQuery("#$field").click();
JS;
        $this->getSession()
            ->getDriver()
            ->executeScript($script);

    }

     /**
     * @When je clique sur noeud :field
     * @When I click on node :field
     */
    public function jeCliqueSurNoeud($field)
    {
        $element = $this->getSession()->getPage()->findById($field);
        if (null === $element) {
           throw new \Exception(sprintf('Could not evaluate element with ID: "%s"', $field));
        }
                $script = <<<JS
            window.jQuery("#$field").find("ins").first().click();
JS;
        $this->getSession()
            ->getDriver()
            ->executeScript($script);

    }

    /**
     * @When je clique sur page :field
     * @When I click on page :field
     */
    public function jeCliqueSurPage($field)
    {
        $element = $this->getSession()->getPage()->findById($field);
        if (null === $element) {
            throw new \Exception(sprintf('Could not evaluate element with ID: "%s"', $field));
        }
        $script = <<<JS
            window.jQuery("#$field").find("a").first().click();
JS;
        $this->getSession()
            ->getDriver()
            ->executeScript($script);

    }

    /**
     * @When je sélectionne l'option :field
     * @When I check :field with value :value
     */
    public function checkRadioButton($field, $value)
    {
        $element = $this->getSession()->getPage()->findAll('css', "#$field");
        if (null === $element) {
            throw new \Exception(sprintf('Could not evaluate element with ID: "%s"', $field));
        }

        $script = <<<JS
            window.jQuery("#$field").each(function(){
                if (this.val() == $value){
                    this.attr('checked', 'checked');
                }
            });

JS;
        $this->getSession()
            ->getDriver()
            ->executeScript($script);

    }

    /**
     * @Given /^I set window size to "([^"]*)" x "([^"]*)"$/
     * @Given /^Je met la dimension de la fenêtre a  "([^"]*)" x "([^"]*)"$/
     */
    public function iSetWindowSizeToX($width, $height) 
    {
    	$this->getSession()->resizeWindow((int)$width, (int)$height, 'current');
    }

    /**
     * @Then /^I should see the CSS selector "([^"]*)"$/
     * @Alors /^Je devrais voir le selecteur CSS "([^"]*)"$/
     */
    public function iShouldSeeTheCssSelector($css_selector) {
        $element = $this->getSession()->getPage()->find("css", $css_selector);
        if (empty($element) || !$element->isVisible()) {
            throw new \Exception(sprintf("The page '%s' does not contain the css selector '%s'", $this->getSession()->getCurrentUrl(), $css_selector));
        }
    }

    /**
     * @Then /^I should not see the CSS selector "([^"]*)"$/
     * @Alors /^Je ne devrais pas voir le selecteur CSS "([^"]*)"$/
     */
    public function iShouldNotSeeAElementWithCssSelector($css_selector) {
        $element = $this->getSession()->getPage()->find("css", $css_selector);
        if (!empty($element) && $element->isVisible()) {
            throw new \Exception(sprintf("The page '%s' contains the css selector '%s'", $this->getSession()->getCurrentUrl(), $css_selector));
        }
    }

    /**
     *
     * @When /^(?:|I )click the element with CSS selector "([^"]*)"$/
     * @Quand /^(?:|Je )clique l'élément avec le selector CSS "([^"]*)"$/
     */
    public function iClickTheElementWithCssSelector($css_selector) {
        $element = $this->getSession()->getPage()->find("css", $css_selector);
        if (empty($element)) {
            throw new \Exception(sprintf("The page '%s' does not contain the css selector '%s'", $this->getSession()->getCurrentUrl(), $css_selector));
        }
        $element->click();
    }

    /**
     *
     * @When /^(?:|I )click on image "([^"]*)" on slice "([^"]*)"$/
     * @Quand /^(?:|Je )clique sur l'image "([^"]*)" dans la tranche "([^"]*)"$/
     */
    public function iClickOnImageOnSlice($url, $cssSelector) {
        $images = $this->getSession()->getPage()->findAll("css", $cssSelector.' img');
        $found = false;
        /** @var \Behat\Mink\Element\NodeElement $image */
        foreach($images as $image){
            if($url == $image->getAttribute('src')) {
                $found = $image;
            }
        }

        if(!$found) {
            throw new \Exception(sprintf("The slice '%s' does not contain image '%s'",$cssSelector, $url));
        }
        $found->click();
    }

    /**
     * @Then /^I should see image "([^"]*)" in slice "([^"]*)"$/
     * @Alors /^Je devrais voir l'image "([^"]*)" dans la tranche "([^"]*)"$/
     */
    public function iShouldSeeImage($url, $cssSelector) {
        $images = $this->getSession()->getPage()->findAll("css", $cssSelector.' img');
        $found = false;
        /** @var \Behat\Mink\Element\NodeElement $image */
        foreach($images as $image){
            $found = $found || ($url == $image->getAttribute('src') && $image->isVisible() );
        }

        if(! $found) {
            throw new \Exception(sprintf("The slice '%s' does not contain image '%s'",$cssSelector, $url));
        }
    }

    /**
     * @Then /^I should not see image "([^"]*)" in slice "([^"]*)"$/
     * @Alors /^Je ne devrais pas voir l'image "([^"]*)" dans la tranche "([^"]*)"$/
     */
    public function iShouldNotSeeImage($url, $cssSelector) {
        $images = $this->getSession()->getPage()->findAll("css", $cssSelector.' img');
        $found = false;
        /** @var \Behat\Mink\Element\NodeElement $image */
        foreach($images as $image){
            $found = $found || ($url == $image->getAttribute('src') && $image->isVisible() );
        }

        if($found) {
            throw new \Exception(sprintf("The slice '%s' contain image '%s'",$cssSelector, $url));
        }
    }


    /**
     * @Then /^size of image "([^"]*)" should be "([^"]*)" x "([^"]*)"$/
     * @Alors /la taille de l'image "([^"]*)" doit être  "([^"]*)" x "([^"]*)"$/
     */
    public function imageSizeShouldBe($url, $width, $heiht) {
        $size = getimagesize($url);
        if ($size[0] != $width && $size[1] != $heiht ) {
            throw new \Exception(sprintf("The size of %s is not good (%s,%s)", $url, $size[0], $size[1]));
        }
    }

    private function spin ($name, $wait = 5)
    {
        for ($i = 0; $i < $wait; $i++)
        {
            try {
                if ($this->getSession()->getPage()->findField($name)) {
                    return true;
                }
            } catch (Exception $e) {
                // do nothing
            }

            sleep(1);
        }

        throw new Exception(
            "Timeout thrown by " . $name
        );
    }

    private function debugVar($var)
    {
        $str = var_export($var ,true);
        fwrite(STDERR, $str."\n");

        return;
    }

}

