/* @constructor
 * Système d'arborescence ExtJS
 * @param {Object} config The config object
 * @xtype checktree
 */
Ext.namespace('Ext.Pelican');
Ext.Pelican.CheckTree = Ext.extend(Ext.tree.TreePanel, {
	
////////////////////////////////////////////////////////////////////////
//
//
// Parametres de base
//
//
////////////////////////////////////////////////////////////////////////
	
	id: 'pelicanchecktree-panel',
	animate:true,
	enableDD:true,
	rootVisible:true,
	width:400,
	split:true,
	collapsible: true,
	border: false,
	header : false,
	margins: '5 0 5 5',
	selectNode: function (bSuccess, oSelNode) {
		if(oSelNode.attributes && oSelNode.attributes.action){
			eval(oSelNode.attributes.action);
		}
	},

	doDefault : function (path) {
		//try{console.log("doDefault "+path);}catch(e){}
		if(path){
			this.selectPath('/0/'+path,null, this.selectNode);
		}else{
			if(this.getSelectionModel().getSelectedNode()){
				//alert('cas 2 : reselection de la page courante')
				this.selectPath(this.getSelectionModel().getSelectedNode().getPath(), null, this.selectNode);
			}
		}
	},
	
	// Titre de l'arborescence
	title:'Répertoires',
		
	// Api d'interaction avec le serveur
	api: {
		create: null,
		destroy: null,
		rename: null,
		move: null,
		list: null
	},
	loader: {
		url : null
	},

	// Creation
	allowNodeCreate : false,
	allowRootNodeCreate : false,
	
	// Suppression
	allowNodeDelete : false,
	allowRootDelete : false,
	// TODO - allowRootNodeDelete : true,
	
	// Edition
	allowNodeMove : true,
	allowNodeRename : false,

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
		
		//Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
		
		//this.dataUrl = this.api.list+'?action=list_dir';
		this.loader.url = this.api.list+'?action=list_dir';
		//this.plugins = [new Ext.ux.state.TreePanel(this.parent())];
		var newIndex = 0;


		////////////////////////////////////////////////////////////////////
		// Bouton de creation de repertoires
		////////////////////////////////////////////////////////////////////
		var toolbar_items = [];
		if(this.allowNodeCreate) {
			toolbar_items.push ({
				text: 'Nouveau',
				id: 'pelicantree-panel-create-node',
				side: 'left',
				iconCls: 'folder-add',
				handler: function(){
					var selectedNode = Ext.getCmp('pelicantree-panel').getSelectionModel().getSelectedNode();
					if(selectedNode && selectedNode.isExpandable() && !selectedNode.isExpanded()){
						selectedNode.expand(false, true, Ext.getCmp('pelicantree-panel').createMediaDirectory);
					}else{
						if( !selectedNode ){
							selectedNode = Ext.getCmp('pelicantree-panel').getRootNode();
						}else{
							selectedNode.leaf = false;
						}
						
						Ext.getCmp('pelicantree-panel').createMediaDirectory(selectedNode);
					}

				}
			});
		}


		////////////////////////////////////////////////////////////////////
		// Bouton de suppression de repertoires
		////////////////////////////////////////////////////////////////////
		if(this.allowNodeDelete) {
			toolbar_items.push ({
					text: 'Supprimer',
					id: 'pelicantree-panel-delete-node',
					side: 'left',
					iconCls: 'folder-del',
					handler: function(){
						var selectedNode = Ext.getCmp('pelicantree-panel').getSelectionModel().getSelectedNode();
						if(selectedNode){
							Ext.getCmp('pelicantree-panel').getSelectionModel().unselect();
							Ext.getCmp('pelicantree-panel').deleteMediaDirectory(selectedNode);
						}
					}
				});
		}


		////////////////////////////////////////////////////////////////////
		// Barre de menu de gestion des répertoires de médias
		////////////////////////////////////////////////////////////////////
		if(this.allowNodeDelete || this.allowNodeCreate){
			this.fbar = new Ext.Toolbar({
				items:[toolbar_items],
				buttonAlign: 'left'
			});
		}
		
		Ext.Pelican.Tree.superclass.initComponent.call(this);

		if(this.expandRoot){
			this.getRootNode().expand();
		}
		if(!this.allowRootDelete && Ext.getCmp('pelicantree-panel-delete-node') ){
			Ext.getCmp('pelicantree-panel-delete-node').disable();
		}
		if(!this.allowRootNodeCreate && Ext.getCmp('pelicantree-panel-create-node') ){
			Ext.getCmp('pelicantree-panel-create-node').disable();
		}


		////////////////////////////////////////////////////////////////////
		// Système de renommage des répertoires
		////////////////////////////////////////////////////////////////////
		if(this.allowNodeRename){
			this.editor = new Ext.tree.TreeEditor(this, {/* fieldconfig here */ }, {
				allowBlank:false,
				blankText:'Veuillez indiquer un nom',
				selectOnFocus:true
			});
		}
		
		if(this.defaultnode){
			this.doDefault(this.defaultnode);
		}
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
	createTreeNode: function(selectedNode){
			
		var node = new Ext.tree.TreeNode({
			text:Ext.getCmp('pelicantree-panel').generateNewNodeName(),
			cls:'x-tree-node-icon',
			allowDrag:true
		});
	
		selectedNode.appendChild([node]);
		
		// S'il s'agissait d'un repertoire sans enfant, il faut l'ouvrir avant de selectionner le nouveau repertoire
		if(!selectedNode.isExpanded()){
			selectedNode.expand();
		}
		Ext.getCmp('pelicantree-panel').getSelectionModel().select(node);
		$.post(Ext.getCmp('pelicantree-panel').api.create, { action:'create_dir', root: selectedNode.id, folder_name: node.text });
	},


	////////////////////////////////////////////////////////////////////
	// Supprime le repertoire selectionne
	////////////////////////////////////////////////////////////////////
	deleteTreeNode: function(node){
		var id = node.id;
		if(this.api && this.api.destroy){
			if(confirm("Etes-vous sur de vouloir supprimer ce repertoire ?")){
				this.getSelectionModel().select(this.getRootNode());
				this.fireEvent('click', this.getRootNode());
				node.destroy();
				$.post(Ext.getCmp('pelicantree-panel').api.destroy, { id: id, action:'del_dir' });
			}
		}
	},
	

	////////////////////////////////////////////////////////////////////
	// Fonction permettant de charger la liste des médias du répertoire sélectionné
	////////////////////////////////////////////////////////////////////
	viewTreeNode: function(el){

		if(this.store){
			this.store.load({params:{id:el.id}});
		}

		// Le bouton supprimer est désactivé sur la racine si la conf l'indique
		if(Ext.getCmp('pelicantree-panel-delete-node')){
			if( this.allowRootDelete || el.id!=this.getRootNode().id ){
				Ext.getCmp('pelicantree-panel-delete-node').enable();
			}else{
				Ext.getCmp('pelicantree-panel-delete-node').disable();
			}
		}

		// Le bouton supprimer est désactivé sur la racine si la conf l'indique
		if(Ext.getCmp('pelicantree-panel-create-node')){
			if( this.allowRootNodeCreate || el.id!=this.getRootNode().id ){
				Ext.getCmp('pelicantree-panel-create-node').enable();
			}else{
				Ext.getCmp('pelicantree-panel-create-node').disable();
			}
		}
	},
	

	////////////////////////////////////////////////////////////////////
	// Fonction permettant de renommer un repertoire
	////////////////////////////////////////////////////////////////////
	renameTreeNode: function(node, newText, oldText){
		var id = node.id;
		if(oldText!=newText && confirm("Etes-vous sur de vouloir renommer ce repertoire ?")){
			$.post(this.api.destroy, { id: id, action:'rename_dir', old_name: oldText, folder_name: newText });
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

Ext.reg('checktree', Ext.Pelican.Tree);
