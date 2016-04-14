# features/Page_Gabarit.feature
# language: en
Feature: Page Gabarit showroom
  Afin de  tester une page ayant comme gabarit showroom
  En tant que développeur
  Il ne doit pas y avoir des regressions dans les pages déjà créées

  Background: I connect to the administration
    Given I am on "_/Index/login"
    When fill in "Identifiant" with "admin"
    Then the "login" field should contain "admin"
    When I fill in "Mot de passe" with "adminAL83"
    And I press "Valider"
    When I select "2_2" from "SITE_ID"
    And I wait "15" seconds
    And I should see "1_Accueil"

  @javascript @openPageGabarit
  Scenario: Access to page
    When I click on node "node_3893"
    And I wait "5" seconds
    And I click on node "node_4013"
    And I wait "5" seconds
    And I click on page "node_4271"
    And I wait "5" seconds

  @javascript @checkDataintoPage
  Scenario: validate data
    And I click on rubric "togglezone6129"
    And I wait "5" seconds
    And I click on rubric "togglezone6126"
    And I wait "5" seconds
    And I click on rubric "togglezone6128"
    And I wait "5" seconds
    And I click on rubric "togglezone6265"
    And I wait "5" seconds
    And I press "Publier"
    And I wait "5" seconds
