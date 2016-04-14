# features/PC53_APV_Detail.feature
# language: en
Feature: PC53 Détail Service Aprés vente
  Afin de  tester une page de service après vente
  En tant que webmaster
  Je dois pouvoir créer une page de service après vente avec un apv associé.(pas de CTA)   (PC 53)

Background: I connect to the administration
  Given I am on "_/Index/login"
  When fill in "Identifiant" with "admin"
  Then the "login" field should contain "admin"
  When I fill in "Mot de passe" with "adminAL83"
  And I press "Valider"
  When I select "2_2" from "SITE_ID"
  And I wait "5" seconds
  Then I should see "0_Général"
  And I should see "1_Accueil"

@javascript @PC53
Scenario: Accéder à une rubrique
  When I click on page "node_3997"
  And I wait "5" seconds
  And I click on rubric "togglezone6116"
  And I wait "5" seconds
  And je choisis "2" depuis la liste "multi1_ZONE_PARAMETERS"
  When I check "multi1_LEVEL1_CTA0_[PAGE_ZONE_CTA_STATUS]" with value "2"
  And je choisis "10" depuis la liste "multi1_LEVEL1_CTA0_[SELECT_CTA][CTA_ID]"
  And I press "Publier"
  And I wait "5" seconds
