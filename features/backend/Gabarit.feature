# features/Gabarit.feature
# language: en
Feature: Gabarit
  Afin de  tester la modification de gabarit
  En tant que webmaster
  Je dois pouvoir modifier un gabarit (Gabarit)

Background: I connect to the administration
  Given I am on "_/Index/login"
  When fill in "Identifiant" with "admin"
  Then the "login" field should contain "admin"
  When I fill in "Mot de passe" with "adminAL83"
  And I press "Valider"
  When I select "1_1" from "SITE_ID"
  And I wait "5" seconds
  Then I should see "Gabarits"

@javascript @FistGabarit
Scenario: Edit first gabarit (NDP_TP_404)
  When I click on rubric "sdtreeO_1110"
  And I wait "5" seconds
  And I click on rubric "_10_7"
  And I wait "15" seconds
  And I press "button_save" into "frame_right_bottom"
  And I wait "5" seconds


@javascript @SecondGabarit
Scenario: Edit second gabarit (NDP_TP_FAQ)
  When I click on rubric "sdtreeO_1110"
  And I wait "5" seconds
  And I click on rubric "_11_7"
  And I wait "15" seconds
  And I press "button_save" into "frame_right_bottom"
  And I wait "5" seconds
