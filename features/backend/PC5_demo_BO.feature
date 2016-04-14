# features/PC5_demo_BO.feature
# language: fr
Fonctionnalité: TEST BO PC5 sans image
  Afin de  tester une page de contenu texte en deux colonnes
  En tant que webmaster
  Je dois pouvoir créer une page de texte en deux colonnes avec un titre, un sous-titre et un titre zone texte.(pas d'images)   (PC 5) 

Contexte: Je me connecte sur l'administrateur Peugeot
  Etant donné je suis sur "_/Index/login"
  Et je remplis "login" avec "admin"
  Et je remplis "password" avec "adminAL83"
  # Et je coche "lang1"
  Et je presse "Valider"
  Et je sélectionne "2_2" depuis "SITE_ID"
  Et j'attends "10" secondes
  Alors je devrais voir "0_Général"
  Et je vais sur la partie droite
  Et je devrais voir "Titre court"
  Et je vais sur la partie principale
  Et je devrais voir "0_Général"  
  
@javascript @PC5
Scénario: Accéder à une rubrique
  Etant donné je suis "1_Accueil"
  Alors je suis "3_SERVICES ET ACCESSOIRES"
  Et j'attends "3" secondes
  Et je clique sur noeud "node_3815"
  Et j'attends "3" secondes
  Et je suis "5_Sprint4 tests Angela nav full"
  Et je vais sur la partie droite
  Et j'attends "5" secondes
  Et je devrais voir "PC5 - 1 column media or text_ratio XXX_content"
  Et je clique sur "togglezonezoneDynamique_0"
  Et je devrais voir "#ndp-pc5-une-colonne_150_1"  
  Et je saisis "Titre" avec "TITRE PC5"
  Et je saisis "Sous-titre" avec "SOUS TITRE PC5"
  Et je saisis "Titre zone texte" avec "TITRE ZONE TEXTE PC5"
  #Et je coche "2 colonnes"
  Et je saisis "Texte colonne 1" avec "TEXTE COLONNE 1 Etenim si attendere diligenter, existimare vere de omni hac causa volueritis, sic constituetis, iudices, nec descensurum quemquam ad hanc accusationem fuisse, cui, utrum vellet, liceret, nec, cum descendisset, quicquam habiturum spei fuisse, nisi alicuius intolerabili libidine et nimis acerbo odio niteretur. Sed ego Atratino, humanissimo atque optimo adulescenti meo necessario, ignosco, qui habet excusationem vel pietatis vel necessitatis vel aetatis. Si voluit accusare, pietati tribuo, si iussus est, necessitati, si speravit aliquid, pueritiae. Ceteris non modo nihil ignoscendum, sed etiam acriter est resistendum. PC5 FIN1"
  Et je saisis "Texte colonne 2" avec "TEXTE COLONNE 2 Etenim si attendere diligenter, existimare vere de omni hac causa volueritis, sic constituetis, iudices, nec descensurum quemquam ad hanc accusationem fuisse, cui, utrum vellet, liceret, nec, cum descendisset, quicquam habiturum spei fuisse, nisi alicuius intolerabili libidine et nimis acerbo odio niteretur. Sed ego Atratino, humanissimo atque optimo adulescenti meo necessario, ignosco, qui habet excusationem vel pietatis vel necessitatis vel aetatis. Si voluit accusare, pietati tribuo, si iussus est, necessitati, si speravit aliquid, pueritiae. Ceteris non modo nihil ignoscendum, sed etiam acriter est resistendum. PC5 FIN2"
# Et je coche "Aucun media"
Et je vais sur la partie principale
# Et je presse "Enregistrer"
# Et je presse "A publier"
# Et je presse "Publier"  
#  Et je presse "Ajouter"
  Et j'attends "5" secondes
