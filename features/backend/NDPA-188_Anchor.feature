# features/NDPA-188_Anchor.feature
# language: en

Feature: Anchor
  In order to configure the slice anchor
  As an webmaster
  I need to be able to choose and organize the anchor

  Background: I connect to the administration
    Given I am on "_/Index/login"
    When fill in "Identifiant" with "admin"
    Then the "login" field should contain "admin"
    When I fill in "Mot de passe" with "adminAL83"
    #Et je clique sur "lang1" (à développer)
    And I press "Valider"
    # Utilisation de l'id de l'élément car il existe plusieurs "administrateur"
    When I select "2_2" from "SITE_ID"
    And I wait
    Then I should see "0_Général"
    And I should see "1_Accueil"
    When I click on node "node_3893"
    And I click on node "node_4013"
    And I click on node "node_4030"
    When I follow "10_ANCRES PN13"
    And I wait "4" seconds
    And I go to right frame
    And I wait "1" seconds
    Then I should see "/fr/test-non-regression/behat/ancres-pn13.html"

  @javascript @PN13
  Scenario: Anchor PN13
    When I click on "togglezonezoneDynamique_0"
    And I wait "4" seconds
    Then I should see "#ndp-pn13-ancres_150_1"
    When I fill in "multiZone150_0_ZONE_TITRE" with "Titre de la tranche PN13 comptant exactement 60 caractères !123456"
    Then the "multiZone150_0_ZONE_TITRE" field should contain "Titre de la tranche PN13 comptant exactement 60 caractères !"
    Then I count "8" options from the list "multiZone150_0_ANCHOR0_PAGE_ZONE_MULTI_VALUE"
    When I press "Ajouter une ancre"
    Then I should see "n° 2"
    Then I fill in "multiZone150_0_ANCHOR1_PAGE_ZONE_MULTI_TITRE" with "Test Ancre 2 vers PC12"
    Given I choose "N°5  PC12 - 3 columns media or text_content - Titre pc12 3 colonnes" from the list "multiZone150_0_ANCHOR1_PAGE_ZONE_MULTI_VALUE"
    Then I should not see "n° 3"
    When I press "Ajouter une ancre"
    When I press "Ajouter une ancre"
    When I press "Ajouter une ancre"
    When I press "Ajouter une ancre"
    When I press "Ajouter une ancre"
    When I press "Ajouter une ancre"
    Then I should see "n° 8"
    When I press "Ajouter une ancre"
    Then I should not see "n° 9"
