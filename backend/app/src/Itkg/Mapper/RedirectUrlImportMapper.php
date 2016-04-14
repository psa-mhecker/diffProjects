<?php

namespace Itkg\Mapper;

use PSA\MigrationBundle\Entity\Page\PsaPage;
use Symfony\Component\HttpFoundation\Response;
use XtoY\Mapper\Mapper;

class RedirectUrlImportMapper extends Mapper
{
    /**
     * @var \Pelican_Db;
     */
    protected $con;

    /**
     * @var int
     */
    protected $siteId;

    /**
     * @var int
     */
    protected $langueId;

    /**
     * @var \Itkg\Reporter\RedirectImportReporter
     */
    protected $reporter;

    /**
     * @var PsaPage;
     */
    protected $rootPage;
    /**
     * @var array
     */
    private $unique;

    /**
     * @param \Pelican_Db $con
     *
     * @return RedirectUrlImportMapper
     */
    public function setCon(\Pelican_Db $con)
    {
        $this->con = $con;

        return $this;
    }

    /**
     * @param int $siteId
     *
     * @return RedirectUrlImportMapper
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;

        return $this;
    }

    /**
     * @param int $langueId
     *
     * @return RedirectUrlImportMapper
     */
    public function setLangueId($langueId)
    {
        $this->langueId = $langueId;

        return $this;
    }

    public function convert($line)
    {
        throw new \Exception('not implemented, resource greedy ');
    }

    /**
     * @param PsaPage $rootPage
     *
     * @return RedirectUrlImportMapper
     */
    public function setRootPage(PsaPage $rootPage)
    {
        $this->rootPage = $rootPage;

        return $this;
    }

    public function batchConvert($rewritePages)
    {
        $data = [];
        // filtrage des redirection deja existante
        $existingRewritePages = $this->getAllRewrite();
        $this->formatUrl($rewritePages);
        $newRewrite = $this->diffPages($rewritePages, $existingRewritePages);
        $newRewrite = $this->filterInvalid($newRewrite);
        foreach ($newRewrite as $rewrite) {
            $data[] = parent::convert($rewrite);
        }

        $result = $this->getPagesIds($data);

        return $result;
    }

    /**
     * on passe toutes les urls en lowercase.
     *
     * @param $rewritePages
     */
    protected function formatUrl(&$rewritePages)
    {
        array_walk($rewritePages, function (&$value) {
            $value[2] = strtolower($value[2]);
            $value[3] = strtolower($value[3]);
        });
    }

    /**
     * retourne les url de redirection pour le site courante.
     *
     * @return array
     */
    protected function getAllRewrite()
    {
        $sql = 'SELECT REWRITE_URL FROM #pref#_rewrite WHERE SITE_ID= :SITE_ID AND LANGUE_ID= :LANGUE_ID AND (REWRITE_TYPE="PAGE" OR REWRITE_TYPE="EXTERNAL")';
        $bind = [':SITE_ID' => $this->siteId, ':LANGUE_ID' => $this->langueId];
        $this->con->query($sql, $bind);

        return  $this->con->data['REWRITE_URL'];
    }

    /**
     * filtres les pages de celle existantes.
     *
     * @param $rewritePages
     * @param $existingRewritePages
     *
     * @return array
     */
    protected function diffPages($rewritePages, $existingRewritePages)
    {
        array_walk($existingRewritePages, 'strtolower');
        $self = $this;
        // on supprime toute les pages qui existe deja dans la table rewrite
        return array_filter($rewritePages, function ($page) use ($existingRewritePages, $self) {
            $return = !in_array($page[2], $existingRewritePages);
            if (!$return) {
                $this->reporter->addExistingRedirection($page);
            }

            return $return;
        });
    }

    /**
     * filtres les pages en doublon et contenant un http.
     *
     * @param $rewritePages
     *
     * @return array
     */
    protected function filterInvalid($rewritePages)
    {
        $this->unique = [];

        // on supprime toute les pages qui existe deja dans la table rewrite
        return array_filter($rewritePages, function ($page) {
            $isHttp = preg_match('#^https?://#', $page[2]);
            if ($isHttp) {
                $this->reporter->addInvalidRedirection($page);
            }
            if (in_array($page[2], $this->unique)) {
                $isHttp = true;
            } else {
                $this->unique[] = $page[2];
            }

            return !$isHttp;
        });
    }
    /**
     * groupes les pages en fonction du type de redirection
     * http -> externe en 301
     * / -> 410
     *  autre interne en 301.
     *
     * @param $pages
     *
     * @return array
     */
    protected function splitPagesByProtocol($pages)
    {
        $return = ['PAGES' => [], 'EXTERNAL' => [], 'PAGES_410' => []];
        $regexp = '#^https?://#';
        foreach ($pages as $page) {
            $key = 'PAGES';
            if (preg_match($regexp, $page['DEST_URL'])) {
                $key = 'EXTERNAL';
            }
            if ($page['DEST_URL'] == '/') {
                $key = 'PAGES_410';
            }
            $return[$key][] = $page;
        }

        return $return;
    }

    /**
     * mets a jour les données de $pages pour créer les entrées dans la table rewrite.
     *
     * @param $pages
     *
     * @return array
     */
    protected function getPagesIds($pages)
    {
        // on va filtrer les pages avec redirection interne ou externe
        $pages = $this->splitPagesByProtocol($pages);
        // on a 3 tableaux
        // les pages redirigeant vers un site externe
        $result = $this->generateExternalRewrite($pages['EXTERNAL']);
        // les pages dont la redirection pointe vers le / donc on considère que c'est un 410
        $result = array_merge($result, $this->generate410Rewrite($pages['PAGES_410']));
        //pour les dernieres on cherche la pages correspondantes mais par defaut ca sera un 410
        $indexedPages = $this->groupPagesByUrl($pages['PAGES']);
        $this->updatePagesIdsFromPages($indexedPages);
        $this->updatePagesIdsFromRewrites($indexedPages);

        foreach ($indexedPages as $url => $groupedPages) {
            foreach ($groupedPages['PAGES'] as $idx => $newPages) {
                if ($newPages['REWRITE_RESPONSE'] == null) {
                    $this->reporter->addIgnoredRedirection($newPages);
                } else {
                    $newPages['REWRITE_ORDER'] = $idx + 1;
                    $result[] = $newPages;
                }
            }
        }

        return $result;
    }

    /**
     * genere les redirections pour toutes les pages qui commencent par http (url externes).
     *
     * @param $externals
     *
     * @return array
     */
    protected function generateExternalRewrite($externals)
    {
        $result = [];
        foreach ($externals as $rewrite) {
            $rewrite['REWRITE_TYPE'] = 'EXTERNAL';
            $rewrite['EXTERNAL_URL'] = $rewrite['DEST_URL'];
            $rewrite['PAGE_ID'] = $this->rootPage->getPageId();
            $rewrite['REWRITE_ID'] = $this->rootPage->getPageId();
            $rewrite['REWRITE_ORDER'] = 1;
            $rewrite['REWRITE_RESPONSE'] = Response::HTTP_MOVED_PERMANENTLY;

            $result[] = $rewrite;
        }

        return $result;
    }

    /**
     * Generer les redirection pour toutes les pages qui pointe vers /.
     *
     * @param $missings
     *
     * @return array
     */
    protected function generate410Rewrite($missings)
    {
        $result = [];
        foreach ($missings as $rewrite) {
            $rewrite['REWRITE_TYPE'] = 'PAGE';
            $rewrite['EXTERNAL_URL'] = null;
            $rewrite['PAGE_ID'] = $this->rootPage->getPageId();
            $rewrite['REWRITE_ID'] = $this->rootPage->getPageId();
            $rewrite['REWRITE_ORDER'] = 1;
            $rewrite['REWRITE_RESPONSE'] = Response::HTTP_GONE;

            $result[] = $rewrite;
        }

        return $result;
    }

    /**
     * Groupes les pages par url de la page destination pour les recherches.
     *
     * @param $pages
     *
     * @return array
     */
    protected function groupPagesByUrl($pages)
    {
        $result = [];
        foreach ($pages as $idx => $page) {
            $page['REWRITE_TYPE'] = null;
            $page['EXTERNAL_URL'] = null;
            $page['PAGE_ID'] = $this->rootPage->getPageId();
            $page['REWRITE_ID'] = $this->rootPage->getPageId();
            $page['REWRITE_ORDER'] = 1;
            $page['REWRITE_RESPONSE'] = null;
            if (!isset($result[$page['DEST_URL']])) {
                $result[$page['DEST_URL']] = ['PAGES' => []];
            }
            $result[$page['DEST_URL']]['PAGES'][] = $page;
        }

        return $result;
    }

    /**
     * met à jour les informations de $pages  en cherchant dans les pages existantes.
     *
     * @param $pages
     */
    protected function updatePagesIdsFromPages(&$pages)
    {
        // il faut chercher tous les id de page pour les url destination
        $sql = 'SELECT
                    p.PAGE_ID as ID, pv.PAGE_CLEAR_URl as URL
                FROM #pref#_page p
                INNER JOIN #pref#_page_version pv ON p.PAGE_ID=pv.PAGE_ID AND p.LANGUE_ID=pv.LANGUE_ID AND p.PAGE_CURRENT_VERSION=pv.PAGE_VERSION
                WHERE pv.STATE_ID <>5 AND p.SITE_ID=:SITE_ID AND p.LANGUE_ID=:LANGUE_ID';
        $bind = [':SITE_ID' => $this->siteId, ':LANGUE_ID' => $this->langueId];
        $result = $this->con->queryTab($sql, $bind);
        $this->updatePages($result, $pages);
    }

    /**
     * met à jour les informations de $pages  en cherchant l'url dans les url réécrites.
     *
     * @param $pages
     */
    protected function updatePagesIdsFromRewrites(&$pages)
    {
        // il faut chercher aussi dans les redirection  existante
        $sql = 'SELECT REWRITE_ID as ID, REWRITE_URL as URL FROM #pref#_rewrite WHERE SITE_ID= :SITE_ID AND LANGUE_ID= :LANGUE_ID AND REWRITE_TYPE="PAGE"';
        $bind = [':SITE_ID' => $this->siteId, ':LANGUE_ID' => $this->langueId];
        $result = $this->con->queryTab($sql, $bind);
        $this->updatePages($result, $pages);
    }

    /**
     * met à jour les informations de $pages si on trouve son url dans $result.
     *
     * @param $result
     * @param $pages
     */
    protected function updatePages($result, &$pages)
    {
        foreach ($result as $row) {
            if (isset($pages[$row['URL']])) {
                foreach ($pages[$row['URL']]['PAGES'] as $idx => $page) {
                    $pages[$row['URL']]['PAGES'][$idx]['REWRITE_ID'] = $row['ID'];
                    $pages[$row['URL']]['PAGES'][$idx]['PAGE_ID'] = $row['ID'];
                    $pages[$row['URL']]['PAGES'][$idx]['REWRITE_TYPE'] = 'PAGE';
                    $pages[$row['URL']]['PAGES'][$idx]['REWRITE_RESPONSE'] = Response::HTTP_MOVED_PERMANENTLY;
                }
            }
        }
    }
}
