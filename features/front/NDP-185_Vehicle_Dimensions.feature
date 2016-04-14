# features/NDP-185_Vehicle_dimensions.feature
# language: en

Feature: Vehicle dimensions
  In order to see the images of the vehicule dimensions (height, width, length, car trunk for example)
  As an internaute
  I need to be able to choose a thumbnail
Background:
  Given I am on "fr/test-non-regression/showroom-208-5-portes/design/pc77-dimensions-vehicules-1.html"

  @javascript @Page
  Scenario: Technical Page Test
    Given I set window size to "1280" x "1024"
    Then I should not see "RuntimeException"
	And I should not see "Oops"
	And I should not see "PAGE 404"

  @javascript @PC77 @slideshow @1024px
  Scenario: Vehicle dimensions (1024px)
	# Default display
	Given I set window size to "1280" x "1024"
	Then I should see "Titre de la tranche PC77" in the ".slice-pc77 h2" element
	And I should see "Sous-titre de la tranche comptant exactement 60 caractères !"
	And I should see image "http://recette-media.staging.psa-ndp.interakting.org/image/95/2/longueur.6901.6952.11.jpg?autocrop=1" in slice ".slice-pc77"
    And size of image "http://recette-media.staging.psa-ndp.interakting.org/image/95/2/longueur.6901.6952.11.jpg?autocrop=1" should be "1280" x "720"
	And I should see "Longueur : 4,253 m"
	And I should see image "http://recette-media.staging.psa-ndp.interakting.org/image/95/2/longueur.6901.6952.12.jpg?autocrop=1" in slice ".slice-pc77"
	And I should see image "http://recette-media.staging.psa-ndp.interakting.org/image/94/8/arriere.6909.6948.12.jpg?autocrop=1" in slice ".slice-pc77"
	And I should see image "http://recette-media.staging.psa-ndp.interakting.org/image/95/0/largeur.6905.6950.12.jpg?autocrop=1" in slice ".slice-pc77"
	And I should see image "http://recette-media.staging.psa-ndp.interakting.org/image/58/4/peugeot308sw-link-mypeugeot-2015-01.6584.12.jpg?autocrop=1" in slice ".slice-pc77"
	And size of image "http://recette-media.staging.psa-ndp.interakting.org/image/95/2/longueur.6901.6952.12.jpg?autocrop=1" should be "200" x "100"
	And size of image "http://recette-media.staging.psa-ndp.interakting.org/image/94/8/arriere.6909.6948.12.jpg?autocrop=1" should be "200" x "100"
	And size of image "http://recette-media.staging.psa-ndp.interakting.org/image/95/0/largeur.6905.6950.12.jpg?autocrop=1" should be "200" x "100"
	And size of image "http://recette-media.staging.psa-ndp.interakting.org/image/58/4/peugeot308sw-link-mypeugeot-2015-01.6584.12.jpg?autocrop=1" should be "200" x "100"
	And I should see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(1).active"
	And I should see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(2)"
	And I should see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(3)"
	And I should see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(4)"
    And I should not see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(2).active"
    And I should not see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(3).active"
    And I should not see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(4).active"
	And I should see "LONGUEUR" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(1) > div > p" element
	And I should see "LARGEUR" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(2) > div > p" element
	And I should see "HAUTEUR" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(3) > div > p" element
	And I should see "COFFRE" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(4) > div > p" element
	And I should not see the CSS selector ".slice-pc77 li[data-orbit-slide='0']"
	And	I should not see the CSS selector ".slice-pc77 li[data-orbit-slide='1']"
	And	I should not see the CSS selector ".slice-pc77 li[data-orbit-slide='2']"
    And	I should not see the CSS selector ".slice-pc77 li[data-orbit-slide='3']"
	And I should see "Mentions légales locales renseignées par le webmaster dans le back-office."

    # Click on an automatic thumbnail
	When I click on image "http://recette-media.staging.psa-ndp.interakting.org/image/94/8/arriere.6909.6948.12.jpg?autocrop=1" on slice ".slice-pc77"
	Then I should see "Titre de la tranche PC77 comptant exactement 60 caractères !"
	And I should see "Sous-titre de la tranche comptant exactement 60 caractères !"
	And I should see image "http://recette-media.staging.psa-ndp.interakting.org/image/94/8/arriere.6909.6948.11.jpg?autocrop=1" in slice ".slice-pc77"
	And size of image "http://recette-media.staging.psa-ndp.interakting.org/image/94/8/arriere.6909.6948.11.jpg?autocrop=1" should be "1280" x "720"
	And I should see "Largeur : 2,043 m / 1,863 m"
	And I should see image "http://recette-media.staging.psa-ndp.interakting.org/image/95/2/longueur.6901.6952.12.jpg?autocrop=1" in slice ".slice-pc77"
	And I should see image "http://recette-media.staging.psa-ndp.interakting.org/image/94/8/arriere.6909.6948.12.jpg?autocrop=1" in slice ".slice-pc77"
	And I should see image "http://recette-media.staging.psa-ndp.interakting.org/image/95/0/largeur.6905.6950.12.jpg?autocrop=1" in slice ".slice-pc77"
	And I should see image "http://recette-media.staging.psa-ndp.interakting.org/image/58/4/peugeot308sw-link-mypeugeot-2015-01.6584.12.jpg?autocrop=1" in slice ".slice-pc77"
	And size of image "http://recette-media.staging.psa-ndp.interakting.org/image/95/2/longueur.6901.6952.12.jpg?autocrop=1" should be "200" x "100"
	And size of image "http://recette-media.staging.psa-ndp.interakting.org/image/94/8/arriere.6909.6948.12.jpg?autocrop=1" should be "200" x "100"
	And size of image "http://recette-media.staging.psa-ndp.interakting.org/image/95/0/largeur.6905.6950.12.jpg?autocrop=1" should be "200" x "100"
	And size of image "http://recette-media.staging.psa-ndp.interakting.org/image/58/4/peugeot308sw-link-mypeugeot-2015-01.6584.12.jpg?autocrop=1" should be "200" x "100"
	And I should see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(1)"
	And I should see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(2).active"
	And I should see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(3)"
	And I should see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(4)"
	And I should not see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(1).active"
    And I should not see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(3).active"
    And I should not see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(4).active"
	And I should see "LONGUEUR" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(1) > div > p" element
	And I should see "LARGEUR" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(2) > div > p" element
	And I should see "HAUTEUR" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(3) > div > p" element
	And I should see "COFFRE" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(4) > div > p" element
	And I should not see the CSS selector ".slice-pc77 li[data-orbit-slide='0']"
	And	I should not see the CSS selector ".slice-pc77 li[data-orbit-slide='1']"
	And	I should not see the CSS selector ".slice-pc77 li[data-orbit-slide='2']"
    And	I should not see the CSS selector ".slice-pc77 li[data-orbit-slide='3']"
	And I should see "Mentions légales locales renseignées par le webmaster dans le back-office."

	# Click on a manual thumbnail
    When I click on image "http://recette-media.staging.psa-ndp.interakting.org/image/58/4/peugeot308sw-link-mypeugeot-2015-01.6584.12.jpg?autocrop=1" on slice ".slice-pc77"
	Then I should see "Titre de la tranche PC77 comptant exactement 60 caractères !"
	And I should see "Sous-titre de la tranche comptant exactement 60 caractères !"
	And I should see image "http://recette-media.staging.psa-ndp.interakting.org/image/95/4/toit.6913.6954.11.jpg?autocrop=1" in slice ".slice-pc77"
	And size of image "http://recette-media.staging.psa-ndp.interakting.org/image/95/4/toit.6913.6954.11.jpg?autocrop=1" should be "1280" x "720"
	And I should see "Description concernant le toit"
	And I should see image "http://recette-media.staging.psa-ndp.interakting.org/image/95/2/longueur.6901.6952.12.jpg?autocrop=1" in slice ".slice-pc77"
	And I should see image "http://recette-media.staging.psa-ndp.interakting.org/image/94/8/arriere.6909.6948.12.jpg?autocrop=1" in slice ".slice-pc77"
	And I should see image "http://recette-media.staging.psa-ndp.interakting.org/image/95/0/largeur.6905.6950.12.jpg?autocrop=1" in slice ".slice-pc77"
	And I should see image "http://recette-media.staging.psa-ndp.interakting.org/image/58/4/peugeot308sw-link-mypeugeot-2015-01.6584.12.jpg?autocrop=1" in slice ".slice-pc77"
	And size of image "http://recette-media.staging.psa-ndp.interakting.org/image/95/2/longueur.6901.6952.12.jpg?autocrop=1" should be "200" x "100"
	And size of image "http://recette-media.staging.psa-ndp.interakting.org/image/94/8/arriere.6909.6948.12.jpg?autocrop=1" should be "200" x "100"
	And size of image "http://recette-media.staging.psa-ndp.interakting.org/image/95/0/largeur.6905.6950.12.jpg?autocrop=1" should be "200" x "100"
	And size of image "http://recette-media.staging.psa-ndp.interakting.org/image/58/4/peugeot308sw-link-mypeugeot-2015-01.6584.12.jpg?autocrop=1" should be "200" x "100"
	And I should see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(1)"
	And I should see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(2)"
	And I should see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(3)"
	And I should see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(4).active"
	And I should not see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(1).active"
	And I should not see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(2).active"
    And I should not see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(3).active"
    And I should see "LONGUEUR" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(1) > div > p" element
	And I should see "LARGEUR" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(2) > div > p" element
	And I should see "HAUTEUR" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(3) > div > p" element
	And I should see "COFFRE" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(4) > div > p" element
	And I should not see the CSS selector ".slice-pc77 li[data-orbit-slide='0']"
	And	I should not see the CSS selector ".slice-pc77 li[data-orbit-slide='1']"
	And	I should not see the CSS selector ".slice-pc77 li[data-orbit-slide='2']"
    And	I should not see the CSS selector ".slice-pc77 li[data-orbit-slide='3']"
	And I should see "Mentions légales locales renseignées par le webmaster dans le back-office."

	# Click on a thumbnail's title
	When I click the element with CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(2) > div > p"
    Then I should see "Titre de la tranche PC77 comptant exactement 60 caractères !"
    And I should see "Sous-titre de la tranche comptant exactement 60 caractères !"
	And I should see image "http://recette-media.staging.psa-ndp.interakting.org/image/94/8/arriere.6909.6948.11.jpg?autocrop=1" in slice ".slice-pc77"
	And size of image "http://recette-media.staging.psa-ndp.interakting.org/image/94/8/arriere.6909.6948.11.jpg?autocrop=1" should be "1280" x "720"
	And I should see "Largeur : 2,043 m / 1,863 m"
	And I should see image "http://recette-media.staging.psa-ndp.interakting.org/image/95/2/longueur.6901.6952.12.jpg?autocrop=1" in slice ".slice-pc77"
	And I should see image "http://recette-media.staging.psa-ndp.interakting.org/image/94/8/arriere.6909.6948.12.jpg?autocrop=1" in slice ".slice-pc77"
	And I should see image "http://recette-media.staging.psa-ndp.interakting.org/image/95/0/largeur.6905.6950.12.jpg?autocrop=1" in slice ".slice-pc77"
	And I should see image "http://recette-media.staging.psa-ndp.interakting.org/image/58/4/peugeot308sw-link-mypeugeot-2015-01.6584.12.jpg?autocrop=1" in slice ".slice-pc77"
	And size of image "http://recette-media.staging.psa-ndp.interakting.org/image/95/2/longueur.6901.6952.12.jpg?autocrop=1" should be "200" x "100"
	And size of image "http://recette-media.staging.psa-ndp.interakting.org/image/94/8/arriere.6909.6948.12.jpg?autocrop=1" should be "200" x "100"
	And size of image "http://recette-media.staging.psa-ndp.interakting.org/image/95/0/largeur.6905.6950.12.jpg?autocrop=1" should be "200" x "100"
	And size of image "http://recette-media.staging.psa-ndp.interakting.org/image/58/4/peugeot308sw-link-mypeugeot-2015-01.6584.12.jpg?autocrop=1" should be "200" x "100"
	And I should see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(1)"
	And I should see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(2).active"
	And I should see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(3)"
	And I should see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(4)"
	And I should not see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(1).active"
	And I should not see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(3).active"
	And I should not see the CSS selector ".slice-pc77 ul > li.item-thumbnail:nth-child(4).active"
	And I should see "LONGUEUR" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(1) > div > p" element
	And I should see "LARGEUR" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(2) > div > p" element
	And I should see "HAUTEUR" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(3) > div > p" element
	And I should see "COFFRE" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(4) > div > p" element
	And I should not see the CSS selector ".slice-pc77 li[data-orbit-slide='0']"
	And	I should not see the CSS selector ".slice-pc77 li[data-orbit-slide='1']"
	And	I should not see the CSS selector ".slice-pc77 li[data-orbit-slide='2']"
    And	I should not see the CSS selector ".slice-pc77 li[data-orbit-slide='3']"
	And I should see "Mentions légales locales renseignées par le webmaster dans le back-office."

  @javascript @PC77 @slideshow @640px
  Scenario: Vehicle dimensions (640 px)
  	# Default display
    Given I set window size to "320" x "640"
	Then I should see "Titre de la tranche PC77 comptant exactement 60 caractères !"
	And I should see "Sous-titre de la tranche comptant exactement 60 caractères !"
	And I should see image "http://recette-media.staging.psa-ndp.interakting.org/image/95/2/longueur.6901.6952.11.jpg?autocrop=1" in slice ".slice-pc77"
	#And size of image "http://recette-media.staging.psa-ndp.interakting.org/image/95/2/longueur.6901.6952.11.jpg?autocrop=1" should be "640" x "480"
	And I should not see image "http://recette-media.staging.psa-ndp.interakting.org/image/95/2/longueur.6901.6952.12.jpg?autocrop=1" in slice ".slice-pc77"
	And I should not see image "http://recette-media.staging.psa-ndp.interakting.org/image/94/8/arriere.6909.6948.12.jpg?autocrop=1" in slice ".slice-pc77"
	And I should not see image "http://recette-media.staging.psa-ndp.interakting.org/image/95/0/largeur.6905.6950.12.jpg?autocrop=1" in slice ".slice-pc77"
	And I should not see image "http://recette-media.staging.psa-ndp.interakting.org/image/58/4/peugeot308sw-link-mypeugeot-2015-01.6584.12.jpg?autocrop=1" in slice ".slice-pc77"
	And I should not see "LONGUEUR" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(1) > div > p" element
	And I should not see "LARGEUR" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(2) > div > p" element
	And I should not see "HAUTEUR" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(3) > div > p" element
	And I should not see "COFFRE" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(4) > div > p" element
    And I should see the CSS selector ".slice-pc77 li[data-orbit-slide='0'].active"
	And	I should see the CSS selector ".slice-pc77 li[data-orbit-slide='1']"
	And	I should see the CSS selector ".slice-pc77 li[data-orbit-slide='2']"
    And	I should see the CSS selector ".slice-pc77 li[data-orbit-slide='3']"
	And	I should not see the CSS selector ".slice-pc77 li[data-orbit-slide='1'].active"
	And	I should not see the CSS selector ".slice-pc77 li[data-orbit-slide='2'].active"
	And	I should not see the CSS selector ".slice-pc77 li[data-orbit-slide='3'].active"
	And I should see "Longueur : 4,253 m"
	And I should see "Mentions légales locales renseignées par le webmaster dans le back-office."

	# Click on a bullet point
	When I click the element with CSS selector ".slice-pc77 ol > li:nth-child(2)"
	Then I should see "Titre de la tranche PC77 comptant exactement 60 caractères !"
	And I should see "Sous-titre de la tranche comptant exactement 60 caractères !"
	And I should see image "http://recette-media.staging.psa-ndp.interakting.org/image/94/8/arriere.6909.6948.11.jpg?autocrop=1" in slice ".slice-pc77"
	#And size of image "http://recette-media.staging.psa-ndp.interakting.org/image/94/8/arriere.6909.6948.11.jpg?autocrop=1" should be "640" x "480"
	And I should not see image "http://recette-media.staging.psa-ndp.interakting.org/image/95/2/longueur.6901.6952.12.jpg?autocrop=1" in slice ".slice-pc77"
	And I should not see image "http://recette-media.staging.psa-ndp.interakting.org/image/94/8/arriere.6909.6948.12.jpg?autocrop=1" in slice ".slice-pc77"
	And I should not see image "http://recette-media.staging.psa-ndp.interakting.org/image/95/0/largeur.6905.6950.12.jpg?autocrop=1" in slice ".slice-pc77"
	And I should not see image "http://recette-media.staging.psa-ndp.interakting.org/image/58/4/peugeot308sw-link-mypeugeot-2015-01.6584.12.jpg?autocrop=1" in slice ".slice-pc77"
   	And I should not see "LONGUEUR" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(1) > div > p" element
	And I should not see "LARGEUR" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(2) > div > p" element
	And I should not see "HAUTEUR" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(3) > div > p" element
	And I should not see "COFFRE" in the ".slice-pc77 ul > li.item-thumbnail:nth-child(4) > div > p" element
	And I should see the CSS selector ".slice-pc77 li[data-orbit-slide='0']"
	And	I should see the CSS selector ".slice-pc77 li[data-orbit-slide='1'].active"
	And	I should see the CSS selector ".slice-pc77 li[data-orbit-slide='2']"
    And	I should see the CSS selector ".slice-pc77 li[data-orbit-slide='3']"
	And	I should not see the CSS selector ".slice-pc77 li[data-orbit-slide='0'].active"
	And	I should not see the CSS selector ".slice-pc77 li[data-orbit-slide='2'].active"
	And	I should not see the CSS selector ".slice-pc77 li[data-orbit-slide='3'].active"
	And I should see "Largeur : 2,043 m / 1,863 m"
	And I should see "Mentions légales locales renseignées par le webmaster dans le back-office."


	# Test display non indispensable pour test responsive ! ou test Wraith
	# + Au touch sur mobile ? + Au slide sur le visuel sur mobile ? ...

	