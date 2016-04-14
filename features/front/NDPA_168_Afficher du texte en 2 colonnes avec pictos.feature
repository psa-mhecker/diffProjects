# features/NPDA_168_Afficher du texte en 2 colonnes avec pictos
# language: en
Feature: NPDA_168_Afficher du texte en 2 colonnes avec pictos
  Afin de vérifier le collonnage
  En tant qu'Internaute
  Je dois visualiser 2 colonnes de texte avec pictos. 

# Régles Métier :
# REGLES METIER
# Titre principal : 60 caractères max
# Titre colonne : 60 caractères max
# Sur Desktop le titre de la colonne est situé à droite du picto et aligné sur le texte descriptif
#  ...
Background:
  Given I am on "/fr/services-et-accessoires/sprint-3-page-non-reg-behat.html"

@javascript @Page
Scenario: test technique de la Page
 # Test technique de la page
 # page sans erreur
  Given I set window size to "1280" x "1024"
  Then I should not see "RuntimeException"
 
@javascript @PC8
Scenario: test de la NDPA-168-PC8
  Then I should see "TITRE PC8 DEUX COLONNES"
  # Colonne 1
  Then I should see "TITRE COLONNE 1 PC8"
  And I should see "PC8 DEBUT COLONNE 1 Auxerunt"
  And I should see "CTA 4"
  And I should see "CTA2"
  # Colonne 2
  And I should see "TITRE COLONNE 2 PC8"
  And I should see "PC8 DEBUT COLONNE 2 Auxeru"
  And I should see "ESSAYEZ LA 308"
  And I should see "CTA PC40 PRENEZ UN RDV APRES-VENTE"
