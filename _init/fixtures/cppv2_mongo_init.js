//select db
use cppv2
//create collection if not exists
db.createCollection('psa_perso_indicateur')
db.createCollection('psa_perso_score')
db.createCollection('user_actions')
//create indexes
db.psa_perso_indicateur.ensureIndex({'user_id':1})
db.psa_perso_indicateur.ensureIndex({'session_id':1})
db.psa_perso_score.ensureIndex({'user_id':1,'product':1})
db.psa_perso_score.ensureIndex({'session_id':1,'product':1})
db.user_actions.ensureIndex({'session_id':1})
db.user_actions.ensureIndex({'time':1})