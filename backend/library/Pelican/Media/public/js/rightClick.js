		function interceptHref(h, from, to) {

			var params = String(h).replace("javascript:" + from, to) + ";";
			eval(unescape(params));
		}

		function moveFolder(action, id, pid, allowAdd, allowDel, lib) {

			parent.current.move[action] = id;

			if (action ==  "paste") {

				top.setFolder(id, pid, allowAdd, allowDel, lib);
				top.setAction("move", "folder");
				parent.current.move["cut"] = "";
				parent.current.move["paste"] = "";
			}
		}

		function moveMedia(id, pid, allowAdd, allowDel, lib) {
			parent.current.move["cut"] = top.oriDragObj;
			parent.current.move["paste"] = id;
			top.setAction("move", "file");
			parent.current.move["cut"] = "";
			parent.current.move["paste"] = "";
		}

		function right() {
			if(event.srcElement.id.indexOf('webfx-tree') != -1 || event.srcElement.id.indexOf('sdtree') != -1) {
				contexttree(event.srcElement);
			}
		}

		function getFolderId( jstring ){

			jstring = jstring.replace("javascript:parent.goMedia(", "");
			jstring = jstring.split(',')[0];
			return jstring;
		}

		function mediadrop(obj) {

			if (top.typeDragObj == "file") {
				interceptHref(obj.href,"parent.goMedia","moveMedia");
			}
			if (top.typeDragObj == "folder") {
				parent.current.move["cut"] = top.oriDragObj;
				interceptHref(obj,"parent.goMedia(","moveFolder('paste',");
			}
		}

		treedrop = mediadrop;
		document.oncontextmenu = right;