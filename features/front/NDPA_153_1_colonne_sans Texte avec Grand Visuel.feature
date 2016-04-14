# features/NPDA_153_1_colonne_sans Texte avec Grand Visuel.feauture
# language: fr
Fonctionnalité: NDPA-153 1 colonne sans Texte Grand Visuel
  Afin de vérifier le collonnage
  En tant qu'Internaute
  Je dois isualiser un contenu 1 colonne grand visuel sans texte 
# Régles Métier :
# Titre 60 caractères max sur 2 lignes avec césure, facultatif, centré au dessus du visuel
# sous titre, 60 caractères max sur 2 lignes avec césure, facultatif, sous le titre
# Média, facultatif, placé sous le titre et le sous titre l'image peut etre cliquable
# Taille des visuels en 1280 max: 1280 x 429 de base (variable)
# Harminisation des titres/sous titres:

Contexte:
  Etant donné je vais sur "/fr/services-et-accessoires/sprint-3-page-non-reg-behat.html"

@javascript @Page
Scénario: test technique de la Page
 # Test technique de la page
 # page sans erreur
  Etant donné je ne devrais pas voir "RuntimeException"

@javascript @PC5
Scénario: test de la NDPA-153-PC5
  Etant donné je devrais voir "TITRE PC5 TEXTE SEUL"
  Alors je devrais voir "SOUS TITRE PC 5 TEXTE SEUL"
  Et je devrais voir "TITRE ZONE DE TEXTE PC5 1 COLONNE"
  Et je devrais voir "DEBUT PC5 TXT SEUL Auxerunt"
  Et je devrais voir "CTA 3 STANDARD"
  Et je devrais voir "ESSAYEZ LA 308"