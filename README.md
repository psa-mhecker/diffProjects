PSA New Digital Peugeot
===================

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/itkg/psa-ndp/badges/quality-score.png?b=develop&s=67b9682affd7ac1e9375cca847b1e90066eada74)](https://scrutinizer-ci.com/g/itkg/psa-ndp/?branch=develop)

[![Code Coverage](https://scrutinizer-ci.com/g/itkg/psa-ndp/badges/coverage.png?b=develop&s=b6a5cfd89631c65d3829a707e3ba33dd75955199)](https://scrutinizer-ci.com/g/itkg/psa-ndp/?branch=develop)

[![Build Status](https://scrutinizer-ci.com/g/itkg/psa-ndp/badges/build.png?b=develop&s=23761456b37a6d2396687964938f837dec2b6865)](https://scrutinizer-ci.com/g/itkg/psa-ndp/build-status/develop)


Naming Rules
-----------------
 1. Services:

Given a block (a.k.a "Tranche") ***PF 12 Réseaux Sociaux***
Data Source Service class name should be
```
Pf14ReseauxSociauxDataSource
```
this class must implent the DataSourceInterface interface

services déclarations mus be in YAML format. Thus the  Pf14ReseauxSociauxDataSource associated service should be as follows:

```
 psa_ndp_mapping.pf14_reseaux_sociaux_data_source:
        class: %psa_ndp_mapping.pf14_reseaux_sociaux_data_source.class%
```


Assets
-----------------
Assets (javascripts, styles, fonts, ui images) are managed using [Grunt](http://gruntjs.com/).

Grunt and Grunt plugins are both installed using [npm](www.npmjs.com), the [Node.js](nodejs.org) package manager. If you don’t have Node.js installed on your ubuntu machine please follo these steps.

first of all add the ppa
```
sudo apt-add-repository ppa:chris-lea/node.js
```

reload the package list

```
sudo apt-get update
```
install nodejs and npm

```
sudo apt-get install nodejs npm

## if conflict or error with npm, try to install only node js
sudo apt-get install nodejs
```

Once you have npm and nodejs installed you can install the grunt-cli package

```
sudo npm install -g grunt-cli
```

The you will have to install all the dependencies defined in the package.json file, to do this just run
```
sudo npm install
```

Once dependencies installed just run grunt to do the jobs defined in Gruntfile.js

```
grunt --force
```

Mobile 
-----------------

To be ready to use mobile detection by wurfl, you have to create link on wurfl database file in wurfl temp folder which is located in fontend_var_path/../ folder. 

e.g. :

    ln -s /var/www/var/wurfl/wurfl.xml /var/wurfl/wurfl.xml

Commands
------------------

- Cleaning migration directory

     show file older than 5 days but dont deleted them
        
        ./frontend/app/console psa:migration:clean -a 5 -r
        
     really delete files older than 6 days 
            
        ./frontend/app/console psa:migration:clean -a 6
             

- An easy to generate slice in Front Office

        cd frontend
        ./app/console psa:generate:slice -c pc99 -N "A Slice Name"

    this will generate the following file :

     * the strategy file
     * the datasource file
     * the object block file
     * the transformer file

    And add configuration into config files

- SiteMap generation

        cd frontend
        php ./app/console psa:generate:sitemap

    This will generate one sitemap.xml for each site in each language in a definable path "sitemap.default.path" in "app/config/parameters.yml"

        Options:
            * -S [siteId]
            * -L [LangueCode]

    To see sitemap into browser [SITE_URL]/sitemap.xml => this will open sitemap.xml into the first given language from query

        Options:
            * ?lg=[LangueCode] => this will return sitemap.xml for the given language code


### TODO 

 * Generate Migration ?
 * Generate Test Class ? 
 * Generate Backend class ?

  
### TABLE TO REMOVE
 * psa_service : ok
 * psa_arbre_decisionnel ?
 * psa_barre_outils ?
 * psa_benefice ?
 * psa_civility
 * psa_paragraph ?
 * psa_paragraph_media ?
 * psa_pays ?
 * psa_perso_*
 * psa_pub
 

  
  


