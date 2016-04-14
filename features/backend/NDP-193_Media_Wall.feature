# features/NDP-193_Media_Wall.feature
# language: en

Feature: Media wall
  In order to configurate the media wall
  As an webmaster
  I need to be able to choose and organize the media

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
    And I click on node "node_4006"
    And I click on node "node_4007"
    And I click on node "node_4016"
  When I follow "1_PC23 - Mur media"
    And I wait "4" seconds
   And I go to right frame
   And I wait "1" seconds
   Then I should see "/fr/test-non-regression/behat/showroom-208-5-portes/design/pc23-mur-media.html"
  
  @javascript @PC23 @unit
  Scenario: Initialization slice + header

 When I fill in "PAGE_TITLE" with "PC23 - Mur media - Titre comptant exactement 60 caractères !123456"
  And I fill in "PAGE_TITLE_BO" with "PC23 - Mur media - Titre comptant exactement 60 caractères !123456"
 Then the "PAGE_TITLE" field should contain "PC23 - Mur media - Titre comptant exactement 60 caractères !"
  And the "PAGE_TITLE_BO" field should contain "PC23 - Mur media - Titre comptant exactement 60 caractères !"
  And je clique sur rubrique "- Général -"

@javascript @PC23 @unit
Scenario: Mur media - PC23
  When I click on rubric "PC23"
  When I fill in "Titre" with "Titre de la tranche PC23 comptant exactement 60 caractères !123456"
  And I fill in "Sous titre" with "Sous-titre de la tranche comptant exactement 60 caractères !123456"
  Then the "Titre" field should contain "Titre de la tranche PC23 comptant exactement 60 caractères !"
  And the "Sous titre" field should contain "Sous-titre de la tranche comptant exactement 60 caractères !"
  And I should see "Showroom *"
  And I should see "Images"
 
