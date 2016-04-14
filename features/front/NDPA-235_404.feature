# features/NDP-185_Vehicle_dimensions.feature
# language: en

Feature: NDP FO Display 404 page
  In order to check a 404 page
  as a surfer
  I should see 404 page

  Background:
    Given I am on "fr/services-et-accessoires/toto.html"

  @javascript
  Scenario: test de la page 404
    Then I should see "Page 404"
    And I should see "TITRE DE LA PC5 404"
    And I should see "Sous titre de la PC5 de la 404"
    And I should see image "http://recette-media.staging.psa-ndp.interakting.org/image/95/8/1961peugeot404.6958.41.jpg" in slice ".slice-pc5"
    And I should see "CTA2"
