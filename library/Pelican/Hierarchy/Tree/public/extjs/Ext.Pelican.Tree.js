/* @constructor
 * Système d'arborescence ExtJS
 * @param {Object} config The config object
 * @xtype pelicantree
 */
Ext.namespace('Ext.Pelican');
Ext.Pelican.Tree = Ext.extend(Ext.tree.TreePanel, {
	
////////////////////////////////////////////////////////////////////////
//
//
// Parametres de base
//
//
////////////////////////////////////////////////////////////////////////
	
	id: 'pelicantree-panel',
	animate:true,
	enableDD:true,
	
	root: {
		nodeType: 'async',
		text: 'Racine',
		draggable: false,
		allowDrop: false,
		checked: null,
		id: '0'
	},
	
	//containerScroll: true,
	//rootVisible:true,
	rootVisible : false,
	width:400,
	//autoWidth: true,
	split:true,
	collapsible: true,
	//autoScroll:true,
	border: false,
	header : false,
	//autoScroll: false,
	margins: '5 0 5 5',
	listeners: {
	//		'click': {fn:function(a,b,c){Ext.getCmp('pelicantree-panel').viewMediaDirectory(a,b,c)}},
	//		'textchange': {fn:function(a,b,c){Ext.getCmp('pelicantree-panel').renameMediaDirectory(a,b,c)}}
		//'nodedrop':{fn:function(a,b,c){console.log('can drop ?');return false;}}
	},

	// Gestion de l'état de l'arborescence
	//stateEvents : ['click'],
    //stateId : 'pelicantree-panel',
    //stateful : true,
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
    /*getState : function () {
		
        var nodes = [];
        /*this.getRootNode().eachChild(function (child) {
            //function to store state of tree recursively
            /*var storeTreeState = function (node, expandedNodes) {
                if(node.isExpanded() && node.childNodes.length > 0) {
					console.log('path '+node.getPath());
                    expandedNodes.push(node.getPath());
                    node.eachChild(function (child) {
                        storeTreeState(child, expandedNodes);
                    });
                }
            };
            storeTreeState(child, nodes);
        });*/

	/*	var sel = this.getSelectionModel().getSelectedNode();
		var currentNode;
		var currentPath;
		
		if(sel){
			currentNode = sel.id;
			currentPath = sel.getPath();
			currentAction = sel.attributes.action;
		}
			
        return {
            expandedPath : currentPath,
			node : currentNode,
			nodeAction : currentAction
        }
    },

	applyState : function (state) {
	    var that = this;
	    this.getLoader().on('load', function () {
	        //read state in from cookie, not from what is passed in
	        var cookie = Ext.state.Manager.get(that.stateId);

	        /*var nodes = cookie.expandedNodes;
	        for(var i = 0; i < nodes.length; i++) {
	            if(typeof nodes[i] != 'undefined') {
	                that.expandPath(nodes[i]);
	            }
	        }*/
			/*if(typeof cookie.nodeAction!='undefined') {
				eval(cookie.nodeAction);
			}else{
				if(that.getRootNode().childNodes.length>0) {
					eval(that.getRootNode().childNodes[0].attributes.action);
				}
			}
			
			if(typeof cookie.expandedPath!='undefined') {
				that.selectPath(cookie.expandedPath);
			}
			//if(typeof cookie.node!='undefined' && that.getNodeById(cookie.node)) {
			//	that.getNodeById(cookie.node).select();
			//}	
			
	    });
	},*/
	
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
		url : null,
		uiProviders: {select: Ext.tree.SelectNodeUI}
	},

	checkboxes: false,

	// Creation
	allowNodeCreate : false,
	allowRootNodeCreate : false,
	
	// Suppression
	allowNodeDelete : false,
	allowRootDelete : false,
	// TODO - allowRootNodeDelete : true,
	
	// Edition
	allowNodeMove : true,
	allowNodeMoveOutside : true,
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
		
		// Initialisation du chargement des noeuds de l'arborescence
		if(this.api.list.match(/\?/)){
			this.loader.url = this.api.list+'&action=list_dir';
		}else{
			this.loader.url = this.api.list+'?action=list_dir';
		}
		
		// Si on doit avoir des checkbox pour chaque noeud de l'arborescence, on initialise les attributs par défaut
		if(this.checkboxes){
			this.loader.baseAttrs = {checked: false, uiProvider:'select', name:'DIRECTORY_ID[]'};
		}
		
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

		if(this.expandRoot && this.getRootNode()){
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

Ext.reg('pelicantree', Ext.Pelican.Tree);




Ext.tree.SelectNodeUI = function() {
    Ext.tree.SelectNodeUI.superclass.constructor.apply(this, arguments);
};



Ext.extend(Ext.tree.SelectNodeUI, Ext.tree.TreeNodeUI, {
    renderElements : function(n, a, targetNode, bulkRender){
        // add some indent caching, this helps performance when rendering a large tree
        this.indentMarkup = n.parentNode ? n.parentNode.ui.getChildIndent() : '';

        var cb = Ext.isBoolean(a.checked),
            nel,
            href = a.href ? a.href : Ext.isGecko ? "" : "#",
            buf = ['<li class="x-tree-node"><div ext:tree-node-id="',n.id,'" class="x-tree-node-el x-tree-node-leaf x-unselectable ', a.cls,'" unselectable="on">',
            '<span class="x-tree-node-indent">',this.indentMarkup,"</span>",
            '<img src="', this.emptyIcon, '" class="x-tree-ec-icon x-tree-elbow" />',
            '<img src="', a.icon || this.emptyIcon, '" class="x-tree-node-icon',(a.icon ? " x-tree-node-inline-icon" : ""),(a.iconCls ? " "+a.iconCls : ""),'" unselectable="on" />',
            cb ? ('<input class="x-tree-node-cb" type="checkbox" value="'+a.id+'" '+(a.name ? 'name="'+a.name+'"' : "")+' ' + (a.checked ? 'checked="checked" />' : '/>')) : '',
            '<a hidefocus="on" class="x-tree-node-anchor" href="',href,'" tabIndex="1" ',
             a.hrefTarget ? ' target="'+a.hrefTarget+'"' : "", '><span unselectable="on">',n.text,"</span></a></div>",
            '<ul class="x-tree-node-ct" style="display:none;"></ul>',
            "</li>"].join('');

        if(bulkRender !== true && n.nextSibling && (nel = n.nextSibling.ui.getEl())){
            this.wrap = Ext.DomHelper.insertHtml("beforeBegin", nel, buf);
        }else{
            this.wrap = Ext.DomHelper.insertHtml("beforeEnd", targetNode, buf);
        }
        
        this.elNode = this.wrap.childNodes[0];
        this.ctNode = this.wrap.childNodes[1];
        var cs = this.elNode.childNodes;
        this.indentNode = cs[0];
        this.ecNode = cs[1];
        this.iconNode = cs[2];
        var index = 3;
        if(cb){
            this.checkbox = cs[3];
            // fix for IE6
            this.checkbox.defaultChecked = this.checkbox.checked;
            index++;
        }
        this.anchor = cs[index];
        this.textNode = cs[index].firstChild;
    }
});



