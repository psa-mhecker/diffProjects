# features/NPDA_162_pouvoir visualiser un mur média images.feature
# language: fr
Fonctionnalité: NPDA_162_Pouvoir visualiser un mur média images
  Afin de vérifier le collonnage
  En tant qu'Internaute
  Je dois visualiser un mur média composé d'images.(pas de video dans ce sprint)
# Régles Métier :
# REGLES METIER
# je dois pouvoir visualiser 6 médias 
# l'affichage de l'ensemble des médias doit correspondre à la maquette définie pour desktop
# les images sont affichées collées les unes aux autres.
# sur les 6 images, au moins une doit être mise en avant selon le template défini
# --> si moins de 6 médias saisis, le mur média ne s'affiche pas ( voir NPDA-161 : normalement c'est bloqué en BO)
# --> sur chaque média de type image, un picto loupe doit s'afficher, ferré en haut à droite du visuel.

Contexte:
  Etant donné je vais sur "/fr/services-et-accessoires/sprint-3-page-non-reg-behat.html"

@javascript @Page
Scénario: test technique de la Page
 # Test technique de la page
 # page sans erreur
  Etant donné je ne devrais pas voir "RuntimeException"
 
@javascript @PC79
Scénario: test de la NPDA_162_PC79
  Etant donné je devrais voir "TITRE PC 79 MUR MULTIMEDIA MANUEL 6 VISUELS"
  # Lien Google
  Alors je devrais voir "Je vais voir google"
