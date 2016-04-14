(function(THREE, object3D) {
    "use strict";
    //
    // CONSTRUCTEUR
    //
    var PointOfInterest = function(x, y, z, data) {

        THREE.Object3D.call(this);

        this.data = data;
        var wireMaterial = new THREE.MeshBasicMaterial({
            color: 0x00ff00,
            visible: false,
            wireframe: true,
        });

        var geom = new THREE.SphereGeometry(0.1, 1, 1);
        var poi = THREE.SceneUtils.createMultiMaterialObject(geom, [wireMaterial]);
        this.position.x = x;
        this.position.y = y;
        this.position.z = z;
        this.add(poi);
    };

    var p = PointOfInterest.prototype = Object.create(THREE.Object3D.prototype);
    p.constructor = THREE.Mesh;
    //
    //  VARIABLES PRIVEE
    //

    //
    //  VARIABLES PUBLIC
    //
    p.data = null;
    p.canvasX = 0;
    p.canvasY = 0;
    p.worldPosition = new THREE.Vector3();

    //
    //  FUNCTIONS
    //

    //
    //  NAMESPACE
    //    
    object3D.PointOfInterest = PointOfInterest;
}(window.THREE, NameSpace('inside.object3D')));
