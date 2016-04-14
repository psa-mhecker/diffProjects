<?php

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use PsaNdp\MappingBundle\Utils\AbstractPsaMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160223114129 extends AbstractPsaMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->upTranslations(
            array(
                'NDP_TAGGAGE_DESCRIPTION' => array(
                    'expression' => "Ces listes déroulantes sont remplies par défaut. Merci de vérifier si cela correspond au contenu de la page telle que vous l’avez créé et de modifier les valeurs si besoin. Vous trouverez dans Sharepoint DPM des explications pour chaque liste déroulante : http://collab.inetpsa.com/sites/DPM/DPM_ToolBox/Forms/AllItems.aspx",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
            )
        );
        $this->replaceTranslations(
            array(
                'NDP_TAGGAGE_DESCRIPTION' => array(
                    'expression' => "Drop-down menus are configured by default. Please make sure the value matches with the content of your page. If needed, you can modify it. For more information about it : http://collab.inetpsa.com/sites/DPM/DPM_ToolBox/Forms/AllItems.aspx",
                    'bo' => 1,
                    'LANGUE_ID' => '2'
                ),
            )
        );
        $this->replaceTranslations(
            array(
                'NDP_TAGGAGE_DESCRIPTION' => array(
                    'expression' => "Las listas desplegables se completan por defecto. Por favor, verifique si los valores corresponden al contendo de la página y modifíquelos en caso de ser necesario. Para más información, visite el sitio : http://collab.inetpsa.com/sites/DPM/DPM_ToolBox/Forms/AllItems.aspx",
                    'bo' => 1,
                    'LANGUE_ID' => '4'
                ),
            )
        );

        /* -------------------------------------------------------------------- */
        $this->upTranslations(
            array(
                'SITETYPELEVEL2_INFO' => array(
                    'expression' => "* showroom: Page/dispositif présentant les caractéristiques du produit\r\n* configurator: Page/dispositif permettant la personnalisation du projet de l'internaute\r\n* edealer	Page/dispositif permettant de mettre en avant les offres du point de vente\r\n* webstore: Page/dispositif permettant la vente en ligne ou permettant de connaitre le stock en ligne\r\n* appointment: Page/dispositif permetttant la prise de rendez vous avec le point de vente\r\n* promotion: Page/dispositif présentant une opération de communication spécifique (par exemple les 'landing page')\r\n* index: HPomepage, redirigeant vers tous les autres Page/dispositifs (un seul index par site)\r\n* member area: Page/dispositif correspondant à l'espace membre\r\n* calculator: Page/dispositif permettant le calcul de financement\r\n* dealer locator: Page/dispositif correspondant à la recherche de dealer\r\n* navigation: Page/dispositif pour la navigation (master page, etc)\r\n* car selector: Page/dispositif correspondant au choix d'un véhicule parmis une liste exhaustive\r\n* others: Page/dispositif non identifié (valeur par defaut)\r\n",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
            )
        );
        $this->replaceTranslations(
            array(
                'SITETYPELEVEL2_INFO' => array(
                    'expression' => "* showroom: Page/website where product details are shown\r\n* configurator: Page/website where user can personalise their car\r\n* edealer: Page/website where dealers offers are shown\r\n* webstore: Page/website where user can buy a car or know cars stock\r\n* appointment: Page/website where user can make an appointment with the dealer\r\n* promotion: Page/website for a specific marketing operation (landing page for example)\r\n* index: Homepage of the website which redirects to others websites (only one index per site)\r\n* member area: Member area page/website\r\n* calculator: Financial calculating page/website\r\n* dealer locator: Page/website where user looks for a dealer\r\n* navigation: Navigation's web pages (master page, …)\r\n* car selector: Page/website where user selects a car in a list\r\n* others: Non identified website (default value)\r\n",
                    'bo' => 1,
                    'LANGUE_ID' => '2'
                ),
            )
        );
        $this->replaceTranslations(
            array(
                'SITETYPELEVEL2_INFO' => array(
                    'expression' => "* showroom: Página/Sitio web donde se muestran las características del producto\r\n* configurator: Página/Sitio web que permite personalizar su vehículo\r\n* edealer: Página/Sitio web donde se muestran las promociones del concesionario\r\n* webstore: Página/Sitio web que permite comprar un auto o conocer el stock disponible\r\n* appointment: Página/Sitio web que permite concertar una cita con un concesionario\r\n* promotion: Página/Sitio web para una campaña de marketing específica (por ejemplo: landing page)\r\n* index: Página de inicio del  sitio web que redirige hacia los otros sitios web (una sóla página de inicio por sitio)\r\n* member area: Página/Sitio web que corresponde al espacio cliente\r\n* calculator: Página/Sitio web que permite calcular la financiación\r\n* dealer locator: Página/Sitio web de búsqueda de concesionarios\r\n* navigation: Página/Sitio web para la navegación (página maser, etc)\r\n* car selector: Página/Sitio web de selección de vehículos\r\n* others: Página/Sitio web no identificada/o (valor por defecto)\r\n",
                    'bo' => 1,
                    'LANGUE_ID' => '4'
                ),
            )
        );

        /* -------------------------------------------------------------------- */
        $this->upTranslations(
            array(
                'SITETARGET_INFO' => array(
                    'expression' => "* B2B: Pages pour les clients et prospects Business-to-Business\r\n* B2C: Pages pour les clients et prospects Business-to-Customer\r\n* all: Pages pour tous les clients et prospects\r\n",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
            )
        );
        $this->replaceTranslations(
            array(
                'SITETARGET_INFO' => array(
                    'expression' => "* B2B: Pages for Business-to-Business customers and prospects\r\n* B2C: Pages for Business-to-Customer customers and prospects\r\n* all: Pages for all customers and prospects\r\n",
                    'bo' => 1,
                    'LANGUE_ID' => '2'
                ),
            )
        );
        $this->replaceTranslations(
            array(
                'SITETARGET_INFO' => array(
                    'expression' => "* B2B: Páginas para los clientes y prospectos Business-to-Business\r\n* B2C: Páginas para los clientes y prospectos Business-to-Customer\r\n* all: Páginas para todo tipo de cliente y prospecto\r\n",
                    'bo' => 1,
                    'LANGUE_ID' => '4'
                ),
            )
        );

        /* -------------------------------------------------------------------- */
        $this->upTranslations(
            array(
                'SITEFAMILY_INFO' => array(
                    'expression' => "* new cars: Pages correspondant au marché des voitures neuves\r\n* used cars: Pages correspondant au marché des voitures d'occasions\r\n* export cars: Pages correspondant au marché des voitures à l'export\r\n* accessories: Pages correspondant au marché des accessoires\r\n* after-sales: Pages correspondant au marché de l'après-vente\r\n* insurance: Pages correspondant au marché de l'assurance\r\n* assistance: Pages correspondant au marché de l'assistance\r\n* cycles: Pages correspondant au marché des vélos\r\n* scooters: Pages correspondant au marché des scooters\r\n* car rental: Pages correspondant au marché de la location de voitures\r\n* cycles rental: Pages correspondant au marché de la location de vélos\r\n* scooter rental: Pages correspondant au marché de la location de scooter\r\n* financing: Pages correspondant au marché du financement\r\n* design: Pages correspondant au marché du design\r\n* merchandising: Pages correspondant au marché des produits dérivés\r\n* connected services: Pages correspondant au marché des services connectés\r\n* brand (default value): Pages pour lesquels aucune valeur ne correspond (valeur par défaut)\r\n",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
            )
        );
        $this->replaceTranslations(
            array(
                'SITEFAMILY_INFO' => array(
                    'expression' => "* new cars: Pages related to new cars market\r\n* used cars: Pages related to used cars market\r\n* export cars: Pages related to export cars market\r\n* accessories: Pages related to accessories\r\n* after-sales: Pages related to after-sales market\r\n* insurance: Pages related to insurance market\r\n* assistance: Pages related to assistance market\r\n* cycles: Pages related to cycles market\r\n* scooters: Pages related to scooters market\r\n* car rental: Pages related to car rental market\r\n* cycles rental: Pages related to cycles rental market\r\n* scooter rental: Pages related to scooter rental market\r\n* financing: Pages related to financial rental market\r\n* design: Pages related to design market\r\n* merchandising: Pages related to merchandising market\r\n* connected services: Pages related to connected services market\r\n* brand (default value): Pages for which there is no corresponding value (default value)\r\n",
                    'bo' => 1,
                    'LANGUE_ID' => '2'
                ),
            )
        );
        $this->replaceTranslations(
            array(
                'SITEFAMILY_INFO' => array(
                    'expression' => "* new cars: Páginas relacionadas al mercado de vehículos nuevos\r\n* used cars: Páginas relacionadas al mercado de vehículos usados\r\n* export cars: Páginas relacionadas al mercado de vehículos de exportación\r\n* accessories: Páginas relacionadas al mercado de accesorios\r\n* after-sales: Páginas relacionadas al mercado de post-venta\r\n* insurance: Páginas relacionadas al mercado de seguros\r\n* assistance: Páginas relacionadas al mercado de asistencia técnica\r\n* cycles: Páginas relacionadas al mercado de bicicletas\r\n* scooters: Páginas relacionadas al mercado de scooters\r\n* car rental: Páginas relacionadas al mercado de alquiler de vehículos\r\n* cycles rental: Páginas relacionadas al mercado de alquiler de bicicletas\r\n* scooter rental: Páginas relacionadas al mercado de alquiler de scooters\r\n* financing: Páginas relacionadas al mercado de financiación\r\n* design: Páginas relacionadas al mercado del diseño\r\n* merchandising: Páginas relacionadas al mercado del merchandising\r\n* connected services: Páginas relacionadas al mercado de servicios conectados\r\n* brand (default value): Página para la cual no se corresponde ningún valor (valor por defecto)\r\n",
                    'bo' => 1,
                    'LANGUE_ID' => '4'
                ),
            )
        );

        /* -------------------------------------------------------------------- */
        $this->upTranslations(
            array(
                'PAGECATEGORY_INFO' => array(
                    'expression' => "* home page: Les welcome page de chaque dispositif\r\n* product page: Fiches produits de chaque dispositif\r\n* search results: page	Pages de resultats (après le lancement d’une recherche)\r\n* form page: Formulaires\r\n* lead pag: Pages de confirmation de lead\r\n* transaction page: Pages de confirmation de transaction (quand vente en ligne)\r\n* selection assistance page: Pages permettant l'aide au choix\r\n* dealer locator page: Pages permettant de trouver un point de vente\r\n* range page: Pages présentant la gamme de véhicules\r\n* error page: Pages d'erreurs (ex:404)\r\n* prehome page: Page qui s'affiche avant la homepage du dispositif\r\n* master page: Pages qui listent tout le contenu du site\r\n* confirmation page: Page de confirmation pour les formulaires ne générant pas de lead\r\n* cart page: Pages présentant le contenu du panier\r\n* checkout page: Pages correspondant au processus de paiement\r\n* no corresponding value: Pages pour lesquels aucune valeur ne correspond (valeur par défaut)\r\n",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
            )
        );
        $this->replaceTranslations(
            array(
                'PAGECATEGORY_INFO' => array(
                    'expression' => "* home page: The welcome page of a website\r\n* product page: Product pages of a website (like showroom)\r\n* search results page: Result pages (after a search)\r\n* form page: Forms\r\n* lead page: Pages confirming a lead has been made\r\n* transaction page: Page confirming a transaction has been made\r\n* selection assistance page: Pages allowing user to filter/select vehicules from differents criterias\r\n* dealer locator page: Pages to find a dealer\r\n* range page: Pages describing cars range\r\n* error page: Error pages (eg. 404)\r\n* prehome page: Pre-home page dislayed before homepage\r\n* master page: Pages listing all the content of the website\r\n* confirmation page: Confirmation pages for forms which don't generate lead\r\n* cart page: Page displaying the content of user's cart\r\n* checkout page: Pages corresponding to the checkout funnel to the payment\r\n* no corresponding value: Pages for which there is no corresponding value (default value)\r\n",
                    'bo' => 1,
                    'LANGUE_ID' => '2'
                ),
            )
        );
        $this->replaceTranslations(
            array(
                'PAGECATEGORY_INFO' => array(
                    'expression' => "* home page: Página de inicio de un sitio web\r\n* product page: Página de producto\r\n* search results page: Página de resultados (luego de lanzar una búsqueda)\r\n* form page: Formularios\r\n* lead page: Página de confirmación de oportunidades\r\n* transaction page: Página de confirmación de una transacción\r\n* selection assistance page: Página de ayuda a la selección de vehículos\r\n* dealer locator page: Página que permite encontrar un concesionario\r\n* range page: Página que describe la gama de vehículos\r\n* error page: Página de error (ex. 404)\r\n* prehome page: Página que se muestra antes de la página de inicio de un sitio web\r\n* master page: Página que muestra todo el contenido de un sitio web\r\n* confirmation page: Página de confirmación de los formularios que no generan oportunidades\r\n* cart page: Página que muestra el contenido del carrito de compras\r\n* checkout page: Página que corresponde al proceso de pago\r\n* no corresponding value: Página para la cual no se corresponde ningún valor (valor por defecto)\r\n",
                    'bo' => 1,
                    'LANGUE_ID' => '4'
                ),
            )
        );

        /* -------------------------------------------------------------------- */
        $this->upTranslations(
            array(
                'NDP_TAGGAGE_SELECTED_DATA' => array(
                    'expression' => "Merci de vérifier si les valeurs ci-dessous correspondent au contenu de la page telle que vous l’avez créé. Ces valeurs peuvent être modifiées si besoin.",
                    'bo' => 1,
                    'LANGUE_ID' => '1'
                ),
            )
        );
        $this->replaceTranslations(
            array(
                'NDP_TAGGAGE_SELECTED_DATA' => array(
                    'expression' => "Please verify that the values below corresponds with the content of the page. You can modify it if needed.",
                    'bo' => 1,
                    'LANGUE_ID' => '2'
                ),
            )
        );
        $this->replaceTranslations(
            array(
                'NDP_TAGGAGE_SELECTED_DATA' => array(
                    'expression' => "Por favor, verifique si los valores a continuación corresponden al contenido de la página. Puede modificarlo si es necesario.",
                    'bo' => 1,
                    'LANGUE_ID' => '4'
                ),
            )
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->downTranslations(array(
            'NDP_TAGGAGE_DESCRIPTION',
            'SITETYPELEVEL2_INFO',
            'SITETARGET_INFO',
            'SITEFAMILY_INFO',
            'PAGECATEGORY_INFO',
            'NDP_TAGGAGE_SELECTED_DATA',
        ));
    }
}
