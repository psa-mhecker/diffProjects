(function(THREE, object3D) {
    "use strict";
    //
    // CONSTRUCTEUR
    //
    var Cube = function(size, segment, tabMaterials) {
        THREE.Object3D.call(this);

        var geometry = new THREE.PlaneBufferGeometry(size, size, segment, segment);

        var plane;

        plane = new THREE.Mesh(geometry, tabMaterials[5]);
        plane.position.z = -size * 0.5;
        this.add(plane);

        plane = new THREE.Mesh(geometry, tabMaterials[4]);
        plane.position.z = size * 0.5;
        plane.rotation.y = Math.PI;
        this.add(plane);

        plane = new THREE.Mesh(geometry, tabMaterials[1]);
        plane.position.x = size * 0.5;
        plane.rotation.y = -Math.PI * 0.5;
        this.add(plane);

        plane = new THREE.Mesh(geometry, tabMaterials[0]);
        plane.position.x = -size * 0.5;
        plane.rotation.y = Math.PI * 0.5;
        this.add(plane);

        plane = new THREE.Mesh(geometry, tabMaterials[3]);
        plane.position.y = -size * 0.5;
        plane.rotation.x = -Math.PI * 0.5;
        this.add(plane);

        plane = new THREE.Mesh(geometry, tabMaterials[2]);
        plane.position.y = size * 0.5;
        plane.rotation.x = Math.PI * 0.5;
        this.add(plane);



    };

    var p = Cube.prototype = Object.create(THREE.Object3D.prototype);
    p.constructor = THREE.Mesh;

    //
    //  VARIABLES PRIVEE
    //

    //
    //  VARIABLES PUBLIC
    //

    //
    //  FUNCTIONS
    //

    //
    //  NAMESPACE
    //    
    object3D.Cube = Cube;
}(window.THREE, NameSpace('inside.object3D')));
