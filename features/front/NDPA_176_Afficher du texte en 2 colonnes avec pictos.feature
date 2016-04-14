# features/NPDA_176_Afficher du texte en 2 colonnes avec pictos.feature
# language: fr
Fonctionnalité: NPDA_176_Afficher du texte en 2 colonnes avec pictos
  Afin de vérifier le collonnage
  En tant qu'Internaute
  Je dois visualiser 2 colonnes de texte avec pictos. 

  # Régles Métier :
# REGLES METIER
# visualiser 2 colonnes de texte avec pictos. 
# ...

Contexte:
  Etant donné je vais sur "/fr/services-et-accessoires/sprint-3-page-non-reg-behat.html"

@javascript @Page
Scénario: test technique de la Page
 # Test technique de la page
 # page sans erreur
  Etant donné je ne devrais pas voir "RuntimeException"
 
@javascript @PC12 
Scénario: test de la NDPA-176-PC12
  Etant donné je devrais voir "TITRE PC12 3 COLONNES AVEC VISUEL"
  # Colonne 1
  Alors je devrais voir "TITRE COLONNE 1"
  Et je devrais voir "PC12 COLONNE 1 Auxerunt"
  Et je devrais voir "CTA 3 Standard"
  Et je devrais voir "Demandez une offre commerciale"
  # Colonne 2
  Et je devrais voir "TITRE COLONNE 2"
  Et je devrais voir "PC12 COLONNE 2 Auxerunt"
  Et je devrais voir "CTA 4"
  Et je devrais voir "CTA PC40 PRENEZ UN RDV APRES-VENTE"
  # Colonne 3
  Et je devrais voir "TITRE COLONNE 3"
  Et je devrais voir "PC12 COLONNE 2 Auxerunt"
  Et je devrais voir "CTA 3 Standard"
  Et je devrais voir "CTA test Libelle du CTA"