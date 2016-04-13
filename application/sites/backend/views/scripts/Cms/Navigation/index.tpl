{$body}
{literal}
<script type="text/javascript">
function allblank(obj) {
	var testBlank = false;
	testBlank = isBlank(obj.rechercheTexte.value);
	testBlank = testBlank && isBlank(obj.rechercheSite.value);
	testBlank = testBlank && isBlank(obj.rechercheAuteur.value);
	testBlank = testBlank && isBlank(obj.rechercheDateDebut.value);
	testBlank = testBlank && isBlank(obj.rechercheDateFin.value);
	testBlank = testBlank && isBlank(obj.rechercheContentType.value);
	testBlank = testBlank && isBlank(obj.recherchePage.value);
	return testBlank
}

var ongletRubrique="0";
/**
* Gestion des onglets du volet de gauche
*
* @return void
* @param document docSearch document d'où est lancée la commande
* @param string onglet Identifiant de l'onglet
*/
function activeOngletRubrique(docSearch, onglet) {
	docSearch.fFormContentSearch.rechercheTexte.value = "";
	if (ongletRubrique) {
		docSearch.getElementById("ongletRubrique"+ongletRubrique+"_1").src = docSearch.getElementById("ongletRubrique"+ongletRubrique+"_1").src.replace("_on_","_off_");
		docSearch.getElementById("ongletRubrique"+ongletRubrique+"_3").src = docSearch.getElementById("ongletRubrique"+ongletRubrique+"_3").src.replace("_on_","_off_");
		docSearch.getElementById("ongletRubrique"+ongletRubrique+"_2").style.backgroundImage = docSearch.getElementById("ongletRubrique"+ongletRubrique+"_2").style.backgroundImage.replace("_on_","_off_");
		docSearch.getElementById("divRubrique"+ongletRubrique).style.display = "none";
	}
	ongletRubrique = onglet;
	docSearch.getElementById("ongletRubrique"+ongletRubrique+"_1").src = docSearch.getElementById("ongletRubrique"+ongletRubrique+"_1").src.replace("_off_","_on_");
	docSearch.getElementById("ongletRubrique"+ongletRubrique+"_3").src = docSearch.getElementById("ongletRubrique"+ongletRubrique+"_3").src.replace("_off_","_on_");
	docSearch.getElementById("ongletRubrique"+ongletRubrique+"_2").style.backgroundImage = docSearch.getElementById("ongletRubrique"+ongletRubrique+"_2").style.backgroundImage.replace("_off_","_on_");
	docSearch.getElementById("divRubrique"+ongletRubrique).style.display = "";
	if (onglet == "1") {
		docSearch.fFormContentSearch.submit();
	} else {
{/literal}
		dtreepage{$treeId}.doDefault(top.initTree);
{literal}
		top.initTree=false;
	}
}
</script>
{/literal}