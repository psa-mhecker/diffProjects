/* @constructor
 * Create a new Mediatree
 * @param {Object} config The config object
 * @xtype mediatree
 */

Ext.Mediatree = Ext.extend(Ext.tree.TreePanel, {
	
////////////////////////////////////////////////////////////////////////
//
//
// Parametres de base
//
//
////////////////////////////////////////////////////////////////////////
	
	id: 'mediatree-panel',
	animate:true,
	enableDD:true,
	containerScroll: true,
	rootVisible:true,
	width:200,
	split:true,
	collapsible: true,
	autoScroll:true,
	style: '',
	margins: '5 0 5 5',
	listeners: {
			//'click': {fn:function(a,b,c){Ext.getCmp('mediatree-panel').viewMediaDirectory(a,b,c)}},
			//'textchange': {fn:function(a,b,c){Ext.getCmp('mediatree-panel').renameMediaDirectory(a,b,c)}}
	},

	// Titre de l'arborescence
	title:'Répertoires',
		
	// Api d'interaction avec le serveur
	api: {
		'create': null,
		'delete': null,
		'rename': null,
		'move': null,
		'list': null
	},
	
	// Creation
	allowNodeCreate : true,
	allowRootNodeCreate : true,
	buttonAlign : 'left',
	
	// Suppression
	allowNodeDelete : true,
	allowRootDelete : false,
	// TODO - allowRootNodeDelete : true,
	
	// Edition
	allowNodeMove : true,
	allowNodeRename : true,

	expandRoot : true,
	
	// Depot de donnee a recharger en cas de changement dans l'arbo
	store : null,
	
	
////////////////////////////////////////////////////////////////////////
//
//
// Initialisation
//
//
////////////////////////////////////////////////////////////////////////

    initComponent: function() {
		

		this.dataUrl = this.api.list+'?action=list_dir';
		
		var newIndex = 0;


		////////////////////////////////////////////////////////////////////
		// Bouton de creation de repertoires
		////////////////////////////////////////////////////////////////////
		var toolbar_items = [];
		if(this.allowNodeCreate) {
			toolbar_items.push ({
				text: 'Nouveau',
				id: 'mediatree-panel-create-node',
				iconCls: 'folder-add',
				handler: function(){
					var selectedNode = Ext.getCmp('mediatree-panel').getSelectionModel().getSelectedNode();
					if(selectedNode && selectedNode.isExpandable() && !selectedNode.isExpanded()){
						selectedNode.expand(false, true, Ext.getCmp('mediatree-panel').createMediaDirectory);
					}else{
						if( !selectedNode ){
							selectedNode = Ext.getCmp('mediatree-panel').getRootNode();
						}else{
							selectedNode.leaf = false;
						}
						
						Ext.getCmp('mediatree-panel').createMediaDirectory(selectedNode);
					}
					
				
					//setTimeout(function(){
					//	medias_directories_editor.editNode = node;
					//	medias_directories_editor.startEdit(node.ui.textNode);
					//}, 10);
				}
			});
		}


		////////////////////////////////////////////////////////////////////
		// Bouton de suppression de repertoires
		////////////////////////////////////////////////////////////////////
		if(this.allowNodeDelete) {
			toolbar_items.push ({
					text: 'Supprimer',
					id: 'mediatree-panel-delete-node',
					iconCls: 'folder-del',
					handler: function(){
						var selectedNode = Ext.getCmp('mediatree-panel').getSelectionModel().getSelectedNode();
						if(selectedNode){
							Ext.getCmp('mediatree-panel').getSelectionModel().unselect();
							Ext.getCmp('mediatree-panel').deleteMediaDirectory(selectedNode);
						}
					}
				});
		}


		////////////////////////////////////////////////////////////////////
		// Barre de menu de gestion des répertoires de médias
		////////////////////////////////////////////////////////////////////
		if(this.allowNodeDelete || this.allowNodeCreate){
			this.fbar = new Ext.Toolbar({
				items:toolbar_items
			});
		}

		Ext.Mediatree.superclass.initComponent.call(this);

		if(this.expandRoot){
			//console.log('expand');
			this.getRootNode().expand();
			//console.log('expanded');
		}
		if(!this.allowRootDelete && Ext.getCmp('mediatree-panel-delete-node') ){
		console.log('allowRootDelete');
			Ext.getCmp('mediatree-panel-delete-node').disable();
		}
		if(!this.allowRootNodeCreate && Ext.getCmp('mediatree-panel-create-node') ){
		console.log('allowRootNodeCreate');
			Ext.getCmp('mediatree-panel-create-node').disable();
		}


		////////////////////////////////////////////////////////////////////
		// Système de renommage des répertoires
		////////////////////////////////////////////////////////////////////
		/*if(this.allowNodeMove){
		console.log('allowNodeMove');
			this.editor = new Ext.tree.TreeEditor(this, { }, {
				allowBlank:false,
				blankText:'Veuillez indiquer un nom',
				selectOnFocus:true
			});
			console.log('allowNodeMove');
		}*/
	},
   
   
////////////////////////////////////////////////////////////////////////
//
//
// Fonctions d'edition de l'arborescence
//
//
////////////////////////////////////////////////////////////////////////

	
	////////////////////////////////////////////////////////////////////
	// Creation d'un nouveau repertoire
	////////////////////////////////////////////////////////////////////
	createMediaDirectory: function(selectedNode){
			
		var node = new Ext.tree.TreeNode({
			text:Ext.getCmp('mediatree-panel').generateNewNodeName(),
			cls:'x-tree-node-icon',
			allowDrag:true
		});
	
		selectedNode.appendChild([node]);
		
		// S'il s'agissait d'un repertoire sans enfant, il faut l'ouvrir avant de selectionner le nouveau repertoire
		if(!selectedNode.isExpanded()){
			selectedNode.expand();
		}
		Ext.getCmp('mediatree-panel').getSelectionModel().select(node);
		$.post(Ext.getCmp('mediatree-panel').api.create, { action:'create_dir', root: selectedNode.id, folder_name: node.text });
	},


	////////////////////////////////////////////////////////////////////
	// Supprime le repertoire selectionne
	////////////////////////////////////////////////////////////////////
	deleteMediaDirectory: function(node){
		var id = node.id;
		if(this.api && this.api.delete){
			if(confirm("Etes-vous sur de vouloir supprimer ce repertoire ?")){
				this.getSelectionModel().select(this.getRootNode());
				this.fireEvent('click', this.getRootNode());
				node.destroy();
				$.post(Ext.getCmp('mediatree-panel').api.delete, { id: id, action:'del_dir' });
			}
		}
	},
	

	////////////////////////////////////////////////////////////////////
	// Fonction permettant de charger la liste des médias du répertoire sélectionné
	////////////////////////////////////////////////////////////////////
	viewMediaDirectory: function(el){

		if(this.store){
			this.store.load({params:{id:el.id}});
		}

		// Le bouton supprimer est désactivé sur la racine si la conf l'indique
		if(Ext.getCmp('mediatree-panel-delete-node')){
			if( this.allowRootDelete || el.id!=this.getRootNode().id ){
				Ext.getCmp('mediatree-panel-delete-node').enable();
			}else{
				Ext.getCmp('mediatree-panel-delete-node').disable();
			}
		}

		// Le bouton supprimer est désactivé sur la racine si la conf l'indique
		if(Ext.getCmp('mediatree-panel-create-node')){
			if( this.allowRootNodeCreate || el.id!=this.getRootNode().id ){
				Ext.getCmp('mediatree-panel-create-node').enable();
			}else{
				Ext.getCmp('mediatree-panel-create-node').disable();
			}
		}
	},
	

	////////////////////////////////////////////////////////////////////
	// Fonction permettant de renommer un repertoire
	////////////////////////////////////////////////////////////////////
	renameMediaDirectory: function(node, newText, oldText){
		var id = node.id;
		if(oldText!=newText && confirm("Etes-vous sur de vouloir renommer ce repertoire ?")){
			$.post(this.api.delete, { id: id, action:'rename_dir', old_name: oldText, folder_name: newText });
		}
	},


////////////////////////////////////////////////////////////////////////
//
//
// Outils
//
//
////////////////////////////////////////////////////////////////////////

	// Genere un nouveau nom de repertoire
	generateNewNodeName: function(){
		return 'Repertoire';
	}
});
Ext.reg('mediatree', Ext.Mediatree);