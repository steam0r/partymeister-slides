import * as THREE from 'three';
import {FBXLoader} from "three/examples/jsm/loaders/FBXLoader";
import Base from "./base";

export default class Cube extends Base {
    constructor() {
        super();
        this.speed = 0.01;
        this.cube = null;

        this.camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        this.scene = new THREE.Scene();
    }

    setUpScene() {
        super.setUpScene();
    }

    loadScene() {
        this.renderer = new THREE.WebGLRenderer({alpha: true});
        this.cube = new THREE.Mesh(new THREE.CubeGeometry(2, 2, 2), new THREE.MeshNormalMaterial());
        this.scene.add(this.cube);

        this.camera.position.set(0, 3.5, 5);
        this.camera.lookAt(this.scene.position);
    }

    animate(timestamp) {
        if (this.renderer) {
            this.currentFrame++;
            this.uniforms["time"].value = timestamp / 1000;

            this.cube.rotation.x -= this.speed * 2;
            this.cube.rotation.y -= this.speed;
            this.cube.rotation.z -= this.speed * 3;

            this.renderer.render(this.scene, this.camera);

        }
        this.animationFrameRequest = requestAnimationFrame(this.animate.bind(this));
    }
}
