# features/NPDA_58_Afficher le numero du service client dans le footer.feature
# language: fr
Fonctionnalité: NPDA_58_Afficher le numéro de téléphone du service client dans le footer.feature
  Afin de contacter le service client
  En tant qu'Internaute
  Je dois visualiser le numéro de téléphone du service client.

  # Régles Métier :
# Titre : 50 caractères max, facultatif centré au dessus du numéro de téléphone
# Numéro de téléphone : 50 caractères max, facultatif centré au dessous du numéro de téléphone

Contexte:
  Etant donné je vais sur "/fr/gamme.html"

@javascript @Page
Scénario: test technique de la Page
 # Test technique de la page
 # page sans erreur
  Etant donné je ne devrais pas voir "RuntimeException"
 
@javascript @PT2
Scénario: test de la NDPA-58-PT2-ServiceClient
  Etant donné je devrais voir "SERVICE CLIENT"
  # Colonne 3
  Alors je devrais voir "SERVICE CLIENT"
  Et je devrais voir "0 970 809 123"


