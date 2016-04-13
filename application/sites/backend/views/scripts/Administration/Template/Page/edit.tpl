{literal}
<script type="text/javascript">
	function listOrder(area, sens) {
		var ordre = 1;
		var champ = "LIGNE";
		switch (sens) {
			case 1:{
				ordre = 1;
				champ = "LIGNE";
				break;
			}
			case 2:{
				ordre = -1;
				champ = "LIGNE";
				break;
			}
			case 3:{
				ordre = 1;
				champ = "COLONNE";
				break;
			}
			case 4:{
				ordre = -1;
				champ = "COLONNE";
				break;
			}
		}
		var sParam = '&area=' + area + '&ordre=' + ordre + '&champ=' + champ;
{/literal}
		var url = unescape('{$request_uri}');
{literal}
		document.location.href= url + sParam;
	}
	function openArea(id, isPortal) {
		window.name='opener';
{/literal}
		var url = document.location.href.replace('&id={$id}','&tpid={$id}');
{literal}
		var area = window.open(url + '&d=' + isPortal + '&id=' + id, 'area', 'width=450,height=250,resizable=yes,scrollbars=yes,status=no');
		area.focus();
	}
	function openZone(id, aid, r, c, w, h, isPortal) {
		window.name='opener';
{/literal}
		var url = document.location.href.replace('&id={$id}','&aid='+ aid +'&tpid={$id}');
{literal}
		url += '&id=' + id;
		url += '&r=' + r;
		url += '&c=' + c;
		url += '&w=' + w;
		url += '&h=' + h;
		url += '&d=' + isPortal;
		var zone = window.open(url, 'zone', 'width=550,height=250,resizable=yes,scrollbars=yes,status=no');
		zone.focus();
	}
	function delArea(id) {
		if (confirm("ATTENTION !\nEtes-vous sûr(e) de vouloir supprimer cette zone et toutes les données associées dans les pages ?")) {
{/literal}
			document.location.href = '/_/Administration_Template_Page/?id='+id+'&id2={$id}&form_action=DEL&form_name=template_page_area';
{literal}
		}
	}
	function delZone(id, isPortal) {
		if (confirm("ATTENTION !\nEtes-vous sûr(e) de vouloir supprimer ce bloc et toutes les données associées dans les pages ?")) {
			typePage='';4
			if (isPortal == 1) {
				typePage='_portal';
			} 
			document.location.href = '/_/Administration_Template_Page/?id='+id+'&form_action=DEL&form_name=zone_template'+typePage;
		}
	}
	</script>
{/literal} {$content}
<script language="javascript">
{$highlight}
</script>