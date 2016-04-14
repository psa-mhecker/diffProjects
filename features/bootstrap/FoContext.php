<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Mink\Exception\ElementNotFoundException;

/**
 * Front context
 */
class FoContext extends RawMinkContext
{
    /**
     * @Then /^I click on anchor tag with name "([^"]*)"$/
     */
    public function iClickOnAnchorTagWithName($name)
    {
        $element = $this->getSession()->getPage()->findLink($name);

        if ($element->hasAttribute('href')) {
            $href = $element->getAttribute('href');
            $class = substr($href, 1);

            $slice = $this->getSession()->getPage()->find('css', '.'.$class);

            if (null === $slice) {
                throw new \LogicException('Could not find the element');
            }
        } else {
            throw new \Exception(sprintf("The anchor '%s' did not contain href attribute", $name));
        }
    }
}
