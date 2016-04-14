#!/bin/sh


export TYPE_ENVIRONNEMENT=PSA_INTEGRATIONGIT
# Env pour le FO
export SYMFONY_ENV=intgit
export FRONTEND_VAR_PATH=../../var/frontend
export BACKEND_VAR_PATH=../../nfs/var/
export SYMFONY__HTTP__MEDIA="http://media.ndp.git.inetpsa.com"
export SYMFONY__REDIS__CONNECTION="tcp://yval1ea0.inetpsa.com:6379?database=0"
alias op='/psa/commun/adminsys/bin/op'

