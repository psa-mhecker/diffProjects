# features/NDP-185_Vehicle_dimensions.feature
# language: en

Feature: NDP FO AFFICHAGE ANCRES PN13 NAV LIGHT
  In order to check a page containintg 8 achors for  PN13
  as a surfer
  I should see  8 anchors linked to 8 slices

Background:
  Given I am on "fr/services-et-accessoires/sprint-5-tests-angela.html"

  @javascript @PN13
  Scenario: test de la PN13 sur 8 ancres
    Then I should see "TITRE TEST PN13"
    Then I should see "TEST ANCRE 1 VERS PF6"
    Then I should see "TEST ANCRE 2 VERS PC9"
    Then I should see "TEST ANCRE 3 VERS PC7"
    Then I should see "TEST ANCRE 4 VERS PC5"
    Then I should see "TEST ANCRE 5 VERS PC9"
    Then I should see "TEST ANCRE 6 VERS PC7"
    Then I should see "TEST ANCRE 7 VERS PC8"
    Then I should see "TEST ANCRE 8 VERS PC12"
    Then I click on anchor tag with name "TEST ANCRE 1 VERS PF6"
    Then I should see "TITRE PF6 DRAG AND DROP"
    Then I click on anchor tag with name "TEST ANCRE 2 VERS PC9"
    Then I should see "TITRE PC9 VISUEL A DROITE"
    Then I click on anchor tag with name "TEST ANCRE 3 VERS PC7"
    Then I should see "TITRE PC7"
    Then I click on anchor tag with name "TEST ANCRE 4 VERS PC5"
    Then I should see "PC5 TITRE TXT SEUL UNE COLONNE"
    Then I click on anchor tag with name "TEST ANCRE 5 VERS PC9"
    Then I should see "TITRE PC9 VISUEL A DROITE"
    Then I click on anchor tag with name "TEST ANCRE 6 VERS PC7"
    Then I should see "TITRE PC7"
    Then I click on anchor tag with name "TEST ANCRE 7 VERS PC8"
    Then I should see "TITRE PC8"
    Then I click on anchor tag with name "TEST ANCRE 8 VERS PC12"
    Then I should see "TITRE PC12 3 COLONNES"