List of tags for handling cache

# Configurations
  redis > v2.6 is necessary for LUA scripts execution  
  Vhost SYMFONY__REDIS__CONNECTION tcp://127.0.0.1:6379
   

# Tags for Node
TODO


# Tags for Block 

## Tags present for all blocks

* project-NDP,
* type-strategy,
* protocol-[https, http]    ex: protocol-http 
* device-[mobile, desktop]  ex: device-mobile 
* contentType-html,
* site-[id]                 ex: site-1
* language-[Language code]    ex: locale-fr
* node-[id],                ex: page-1
* blockPermanentId-[id]     ex: blockPermanentId-1 

## Tags added depending on each block data

TO complete

# Rules 

## Rules for TTL

Blocks with countdown: PF02 => TTL should not be higher than 60 sec.
For block having Redirection Popin : TTL are handle by JS so no need for specific TTL for the block


## Rules for cache with tags

Todo check for preview

### General Behavior for block

Tags ar done in the FO file : "frontend/src/PsaNdp/MappingBundle/DisplayBlock/Strategies/AbstractPsaStrategy.php"
=> Function getCacheTags()
 
Tags Created :
  - Manadotry Tags: node, site, language 
  - Optional Tags: Content, CTA
  - Ex: node-2, site-1, language-fr, content-2, content-10, cta-12, cta-60 

### Behavior for block where data and configuration are defined in another block

For some blocks, data and configuration to be used in the DataSource are coming from 

Tags ar done in the FO file : "frontend/src/PsaNdp/MappingBundle/DisplayBlock/Strategies/AbstractPsaStrategy.php"
=> Function overrideBlock()
=> Function getCacheTags()

Block impacted :
  - Header / Footer : PT2 / PT22 / PT3
  - CTA Shopping : PC60
  - Navigation : PT21 / PT15 / PT17 /PF02 / PF23 / PN7 / PN18

*TODO Bug ?*
    - In BO, check why when saving a page that is not "general" page there is an invalidation for the tag navigation (il est normal que pour tout autre page que la page général on supprime le cache des block contenant une navigation (pt21, pn7, pn15 etc ) pour le cas ou une url de page ai changé .)
    - For General page: The tag "navigation-2-fr" can be removed since the node-id of the block data from "General" page will be added as tag for all block coming from "General". 
                        => Instead when saving a "General" page use the tag for invalidating a block "type-strategy, node-1, site-1, locale-fr"

| Nom de la tranche                  | Description                                                                                                                                   | Tags lié                       | Mode de décache                                               | Todo/done | Other                                                                                                                                      |
|------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------|--------------------------------|---------------------------------------------------------------|-----------|--------------------------------------------------------------------------------------------------------------------------------------------|
| PT2  Footer                        | Les données viennent de la page général. Le cache ce fait automatiquement grace au systeme d'override du block.                               | N/A                            | Standard invalidation thanks to the block overriding system.  | Done      |                                                                                                                                            |
| PT22 Expand                        | Les données viennent de la page général. Le cache ce fait automatiquement grace au systeme d'override du block.                               | N/A                            | Standard invalidation thanks to the block overriding system.  | Done      |                                                                                                                                            |
| PT3  Je veux                       | Les données viennent de la page général. Le cache ce fait automatiquement grace au systeme d'override du block.                               | N/A                            | Standard invalidation thanks to the block overriding system.  | Done      |                                                                                                                                            |
| PC60 Recapitulatif et CTA showroom | Les données des pages fille viennent de la page d'accueil du showroom. Le cache ce fait automatiquement grace au systeme d'override du block. | N/A                            | Standard invalidation thanks to the block overriding system.  | Done      |                                                                                                                                            |
| PT21 Menu                          | La tranche contient des urls de pages.                                                                                                        | Navigation-siteId-locale       | Invalidate all cache with tags Naviagation when you save page | Done      | It is normal for any page other than the General page is deleted from the cache, for block containing a navigation if page URL has changed |
| PT20 Master page                   | La tranche contient des urls de pages fille                                                                                                   | Navigation-siteId-locale       | Invalidate all cache with tags Naviagation when you save page | Done      | It is normal for any page other than the General page is deleted from the cache, for block containing a navigation if page URL has changed |
| PT15 Plan du site                  | La tranche contient des urls de pages.                                                                                                        | Navigation-siteId-locale       | Invalidate all cache with tags Naviagation when you save page | Done      | It is normal for any page other than the General page is deleted from the cache, for block containing a navigation if page URL has changed |
| PT17 Choix de langue               | La tranche contient des urls de pages.                                                                                                        | Navigation-siteId-locale       | Invalidate all cache with tags Naviagation when you save page | Done      | It is normal for any page other than the General page is deleted from the cache, for block containing a navigation if page URL has changed |
| PF02 Présentation showroom         | La tranche contient des urls de pages.                                                                                                        | Navigation-siteId-locale       | Invalidate all cache with tags Naviagation when you save page | Done      | It is normal for any page other than the General page is deleted from the cache, for block containing a navigation if page URL has changed |
| PF23 Rangebar                      | La tranche contient des urls de pages.                                                                                                        | Navigation-siteId-locale       | Invalidate all cache with tags Naviagation when you save page | Done      | It is normal for any page other than the General page is deleted from the cache, for block containing a navigation if page URL has changed |
| PN7  Tranche en tete               | La tranche contient des urls de pages.                                                                                                        | Navigation-siteId-locale       | Invalidate all cache with tags Naviagation when you save page | Done      | It is normal for any page other than the General page is deleted from the cache, for block containing a navigation if page URL has changed |
| PN18 IFrame                        | La tranche contient des urls de pages.                                                                                                        | Navigation-siteId-locale       | Invalidate all cache with tags Naviagation when you save page | Done      | It is normal for any page other than the General page is deleted from the cache, for block containing a navigation if page URL has changed |
| PF2 Presentation showroom          | La tranche contient des données remontées du référentiel model regroupement silhouette                                                        | lcdv6-{code lcdv6 du véhicule} | Invalidate all cache with this lcdv6 tags when you save page  | Done      |                                                                                                                                            |

### Behavior for transverse


| Transverse           | Description                                                                                                                                                                                            | Tags lié                                       | Mode de décache                                                       | Todo/done                                                                                              | Other  |
|----------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|------------------------------------------------|-----------------------------------------------------------------------|--------------------------------------------------------------------------------------------------------|--------|
| National parameters  | Les données sont dans Administration_Site_Controller                                                                                                                                                   | site-id, type-robots, type-block, type-content | Invalidate all (block, robots, content?, ...) for a siteId            | Todo faire comme la traduction pour le décache BO                                                      |        |
| Translation          | Les données sont dans backend/application/sites/backend/controllers/Administration/Traduction.php                                                                                                      | type-block                                     | Invalidate all block                                                  | Done                                                                                                   |        |
| Cta                  | Les données sont dans les ctaReferences des blocks, des contents et des multi. Il ne faut prendre que les cta de type référentiel. L'administration ce fait dans le BO administration référentiel cta. | cta-id, language-locale, site-id               | Invalidate all block with tag cta-id, language-locale et site-id.     | Todo parser les blocks pour récupérer les cta du block, des contents du block et des multi du block.   |        |
| Content              | Les données sont dans les ContentReferences des blocks.                                                                                                                                                | content-id, language-id, site-id               | Invalidate all block with tag content-id, language-locale et site-id. | Todo parser les blocks pour récupérer les contenus du block et des multis du block.                    |        |


## Rules for decache

### Service
Decache is done in BO using service 'psa_ndp.cache.redis' 
```
$redisCache = Pelican_Application::getContainer()->get('psa_ndp.cache.redis');
$redisCache->removeKeysFromTags(array('node-1', 'site-2', 'language-fr'));
```

### Page and Block
1. When page and its blocks are saved :
  1.a The page can modified (new blocks or different blocks order)
  1.b The data of blocks can be modified

Thus, both node and blocks must be invalidated.
Decache are done in the BO file : "/backend/application/library/Ndp/Cache.php".
=> decacheOrchestra()

Tags used for page and blocks invalidation:
  - Tags: node, site, language 
  - Ex: node-2, site-1, language-fr 


### Referentiel

2. When BO referentiels are modified :
  TO COMPLETE
  
### Robots
3. When a site is save :

All robots must be invalidated.
Decache are done in the BO file : "/backend/application/sites/backend/controllers/Administration/Site.php"
=> Function saveAction()

Tags used for robots invalidation:
  - Tags: type-robots, site
  - Ex: type-robots, site-2
     
### Translation
4. When translation are regenerated :
  
All blocks must be invalidated.
Decache are done in the BO file : "/backend/application/sites/backend/controllers/Administration/Traduction.php".
=> Function generateCacheAction()

Tags used for blocks invalidation:
  - Tags: type-block
  - Ex: type-block