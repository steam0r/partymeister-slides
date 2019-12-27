import * as THREE from 'three';
import {FBXLoader} from "three/examples/jsm/loaders/FBXLoader";
import Base from "./base";

export default class Gin extends Base {
    constructor() {
        super();
        this.angle = 0;
        this.yAxis = null;
        this.cameraPivot = null;
        this.particle = null;

        this.camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        this.scene = new THREE.Scene();

        document.querySelector('.main').style.backgroundImage = 'linear-gradient(to bottom,  #083D38 0%,#8200c9 100%)';
    }

    setUpScene() {
        super.setUpScene();
    }

    loadScene() {
        let loader = new FBXLoader();

        loader.load(
            '/models/Flasche2.fbx',
            (model) => {

                this.renderer = new THREE.WebGLRenderer({alpha: true});

                this.renderer.gammaOutput = true;
                this.renderer.gammaFactor = 2.2;

                model.children[1].children[1].material.opacity = 0.5;
                model.children[1].children[1].renderOrder = 1;

                model.children[1].children[2].material = model.children[1].children[1].material.clone();
                model.children[1].children[2].material.opacity = 0.6;
                model.children[1].children[2].material.color = new THREE.Color(0xFF8C00);

                model.children[1].children[3].position.y = -0.5;
                model.children[1].children[3].material.side = 2;

                let envMap = new THREE.TextureLoader()
                    .setPath('/models/')
                    .load('studio_small_03.jpg');
                envMap.format = THREE.RGBFormat;
                envMap.mapping = THREE.EquirectangularReflectionMapping;
                model.children[1].children[1].material.envMap = envMap;

                this.scene.add(model.children[1]);

                this.renderer.autoClear = true;
                this.renderer.setClearColor(0x000000, 0.0);

                var ambientLight = new THREE.AmbientLight(0x404040, 1); // soft white light
                this.scene.add(ambientLight);

                var lights = [];
                lights[0] = new THREE.DirectionalLight(0xffffff, 1);
                lights[0].position.set(1, 0, 0);
                lights[1] = new THREE.DirectionalLight(0x083D38, 1);
                lights[1].position.set(0.75, 1, 0.5);
                lights[2] = new THREE.DirectionalLight(0x8200C9, 1);
                lights[2].position.set(-0.75, -1, 0.5);
                this.scene.add(lights[0]);
                this.scene.add(lights[1]);
                this.scene.add(lights[2]);

                this.cameraPivot = new THREE.Object3D()
                this.yAxis = new THREE.Vector3(0, 1, 0);

                this.scene.add(this.cameraPivot);
                this.cameraPivot.add(this.camera);
                this.camera.position.set(15, 0, 0);
                this.camera.lookAt(this.cameraPivot.position);

                this.particle = new THREE.Object3D();
                this.wireframe = new THREE.Object3D();

                this.scene.add(this.particle);

                let pGeometry = new THREE.TetrahedronGeometry(2, 0);

                let pMaterial = new THREE.MeshPhongMaterial({
                    color: 0xffffff,
                    shading: THREE.FlatShading
                });

                for (var i = 0; i < 1000; i++) {
                    var mesh = new THREE.Mesh(pGeometry, pMaterial);
                    mesh.position.set(Math.random() - 0.5, Math.random() - 0.5, Math.random() - 0.5).normalize();
                    mesh.position.multiplyScalar(90 + (Math.random() * 700));
                    mesh.rotation.set(Math.random() * 2, Math.random() * 2, Math.random() * 2);
                    this.particle.add(mesh);
                }
            }
        );
    }

    animate(timestamp) {
        if (this.renderer) {
            this.currentFrame++;
            this.uniforms["time"].value = timestamp / 1000;

            let x = this.currentFrame % 750;
            let add = 0;
            if (x > 0 && x <= 375) {
                add = add + (x / 75);
                this.camera.rotation.x = 0.5;
            } else {
                add = add + (-x / 75);
                this.camera.rotation.x = -0.5;
            }

            this.cameraPivot.rotateOnAxis(this.yAxis, 0.01);
            this.camera.position.set(25 + add, 0, 0);

            this.particle.rotation.x += 0.0050;
            this.particle.rotation.y += 0.0050;
            this.particle.rotation.z += 0.0030;

            this.renderer.clear();
            this.renderer.render(this.scene, this.camera);

        }
        this.animationFrameRequest = requestAnimationFrame(this.animate.bind(this));
    }
}
