# features/front.feature
# language: fr
Fonctionnalité: NDPA-152 Rendre le contenu 1 colonne facultatif
  Afin de vérifier le collonnage
  En tant que Internaute
  Je dois pouvoir voir du contenu 1 colonne facultatif

Contexte:
  Etant donné je vais sur "/fr/services-et-accessoires/sprint-3-page-non-reg-behat.html"

  @javascript @Page
Scénario: test technique de la Page
 # Test technique de la page
 # page sans erreur
  Etant donné je ne devrais pas voir "RuntimeException"
  
@javascript @PC5
Scénario: test de la NDPA-152-PC5
  Etant donné je devrais voir "TITRE PC5 TEXTE SEUL"
  Alors je devrais voir "SOUS TITRE PC 5 TEXTE SEUL"
  Et je devrais voir "TITRE ZONE DE TEXTE PC5 1 COLONNE"
  Et je devrais voir "DEBUT PC5 TXT SEUL Auxerunt"
  Et je devrais voir "CTA 3 STANDARD"
  Et je devrais voir "ESSAYEZ LA 308"