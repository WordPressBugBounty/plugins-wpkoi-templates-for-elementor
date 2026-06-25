(function($) {

    function initParticleDrift($scope) {

        const container = $scope[0].querySelector('.wpkoi-particle-drift-wrapper');
        if (!container) return;

        const THREE = window.THREE;

        const settings = JSON.parse(container.getAttribute('data-settings') || '{}');

        const mobileMode = settings.mobile_mode || 'optimized';
        let isMobile = window.matchMedia("(max-width: 768px)").matches;

        if (isMobile && mobileMode === 'disabled') {
            return;
        }

        const qualityMode = settings.quality_mode || 'medium';
		
		let isVisible = false;

		const observer = new IntersectionObserver(entries => {
			isVisible = entries[0].isIntersecting;
		}, {
			threshold: 0
		});

		observer.observe(container);

        let pixelRatio = 1;
        let densityMultiplier = 1;
        let useMipmaps = true;

        switch (qualityMode) {

            case 'low':
                pixelRatio = Math.min(window.devicePixelRatio, 1);
                densityMultiplier = 0.5;
                useMipmaps = false;
                break;

            case 'high':
                pixelRatio = Math.min(window.devicePixelRatio, 2);
                densityMultiplier = 1.5;
                break;

            default:
                pixelRatio = Math.min(window.devicePixelRatio, 1.5);
                densityMultiplier = 1;
        }

        if (isMobile) {

            if (mobileMode === 'optimized') {
                densityMultiplier *= 0.5;
                pixelRatio = Math.min(pixelRatio, 1);
            }

            if (mobileMode === 'static') {
                densityMultiplier *= 0.6;
                pixelRatio = Math.min(pixelRatio, 1);
            }

        }

        let sizeMin = settings.size_min ?? 0.5;
        let sizeMax = settings.size_max ?? 1.5;

        const densityRaw = settings.density ?? 20;
        const density = densityRaw * 100 * densityMultiplier;
        const depthRaw = settings.depth ?? 20;
        const depth = depthRaw * 100;
        const opacityMin = settings.opacity_min ?? 0.5;
        const opacityMax = settings.opacity_max ?? 1;
        const textureType = settings.texture_type || 'cloud';

        const speedRaw = settings.speed ?? 30;
        const speed = 0.005 + (speedRaw / 100) * 0.075;
        const reverseDirection = settings.reverse_direction === 'yes';
        const fov = settings.fov ?? 30;
        const viewMode = settings.view_mode || 'default';

        const fogColor = settings.fog_color || '#4584b4';
        const enableTint = settings.enable_tint === 'yes';
        const tintColorRaw = settings.tint_color || '#ffffff';
        const tintStrength = settings.tint_strength ?? 0.5;
        let tintColor;

        try {
            tintColor = new THREE.Color(tintColorRaw);
        } catch (e) {
            tintColor = new THREE.Color('#ffffff');
        }
        const fogNear = -100;
        const fogFar = 3000;

        const blendMode = settings.blend_mode || 'normal';

        const mouseStrength = settings.mouse_strength ?? 0.25;
        const mouseAxis = settings.mouse_axis || 'both';
        const baseTiltY = settings.base_tilt_y ?? 0;

        let mouseClientX = 0;
        let mouseClientY = 0;

        const enableNoise = settings.enable_noise === 'yes';
        const noiseLevel = settings.noise_strength || 'normal';

        let noiseStrength = 0.0;

        switch (noiseLevel) {
            case 'low':
                noiseStrength = 0.01;
                break;
            case 'high':
                noiseStrength = 0.05;
                break;
            default:
                noiseStrength = 0.025;
        }

        const enableDepthFade = settings.enable_depth_fade === 'yes';
        const depthFadeLevel = settings.depth_fade_strength || 'normal';

        let depthFadeStrength = 0.0;

        switch (depthFadeLevel) {
            case 'low':
                depthFadeStrength = 0.6;
                break;
            case 'high':
                depthFadeStrength = 2.5;
                break;
            default:
                depthFadeStrength = 1.5;
        }

        if (sizeMin > sizeMax) {
            const temp = sizeMin;
            sizeMin = sizeMax;
            sizeMax = temp;
        }

        let scene, camera, renderer;
		let position = 0;
        let mouseX = 0,
            mouseY = 0;
        let windowHalfX = container.offsetWidth / 2;
        let windowHalfY = container.offsetHeight / 2;
        let start_time = Date.now();
        let meshes = [];
        let material;

        let targetMouseX = 0;
        let targetMouseY = 0;
        let isHovering = false;

        let group2;

        const cloudShader = {
            vertexShader: `
                varying vec2 vUv;
                void main() {
                    vUv = uv;
                    gl_Position = projectionMatrix * modelViewMatrix * vec4( position, 1.0 );
                }
            `,
            fragmentShader: `
                uniform sampler2D map;
uniform float fogNear;
uniform float fogFar;
uniform float opacityFactor;

uniform float tintEnabled;
uniform vec3 tintColor;
uniform float tintStrength;
uniform float noiseEnabled;
uniform float noiseStrength;
uniform float time;
uniform float depthFadeEnabled;
uniform float depthFadeStrength;

varying vec2 vUv;

void main() {

    float depth = gl_FragCoord.z / gl_FragCoord.w;
    float fogFactor = smoothstep( fogNear, fogFar, depth );

    vec2 uv = vUv;

if (noiseEnabled > 0.5) {
    float n = sin(uv.y * 10.0 + time) * noiseStrength;
    float n2 = cos(uv.x * 8.0 + time * 1.2) * noiseStrength;

    uv += vec2(n, n2);
}

vec4 color = texture2D(map, uv);

    float depthAlpha = gl_FragCoord.z * gl_FragCoord.z;
color.a *= depthAlpha;

if (depthFadeEnabled > 0.5) {
    float fadeRange = depthFadeStrength * 0.5 + 0.5; 
float fade = smoothstep(0.0, fadeRange, 1.0 - fogFactor);
fade = pow(fade, 1.5);
    color.a *= fade;
}

    

    if (tintEnabled > 0.5) {
        color.rgb = mix(color.rgb, tintColor, tintStrength);
    }

    color.a *= opacityFactor;

    gl_FragColor = color;
}
                
            `
        };

        scene = new THREE.Scene();

        camera = new THREE.PerspectiveCamera(
            fov,
            container.offsetWidth / container.offsetHeight,
            1,
            3000
        );

        renderer = new THREE.WebGLRenderer({
            antialias: false,
            alpha: true
        });

        renderer.outputColorSpace = THREE.SRGBColorSpace;

        renderer.setSize(container.offsetWidth, container.offsetHeight);
        renderer.setPixelRatio(pixelRatio);
        container.appendChild(renderer.domElement);

        renderer.domElement.style.mixBlendMode = blendMode;

        const loader = new THREE.TextureLoader();
        loader.setCrossOrigin('anonymous');

        const textureMap = {
            cloud: 'cloud.webp',
            smoke: 'smoke.webp',
            ink: 'ink.webp',
            dust: 'dust.webp',
            flame: 'flame.webp'
        };

        let textureUrl;

        if (textureType === 'custom') {
            if (settings.custom_texture?.url) {
                textureUrl = settings.custom_texture.url;
            } else {
                console.warn('No custom texture selected');
                return;
            }
        } else {
            textureUrl = wpkoiParticleDrift.pluginUrl +
                'elements/interactive-particle-drift/assets/img/' +
                textureMap[textureType];
        }

        loader.load(textureUrl, function(texture) {
            texture.colorSpace = THREE.SRGBColorSpace;

            initScene(texture);
            animate();
        });

        function initScene(texture) {

            camera.position.z = 6000;

            texture.magFilter = THREE.LinearFilter;
            texture.generateMipmaps = useMipmaps;

            if (!useMipmaps) {
                texture.minFilter = THREE.LinearFilter;
            } else {
                texture.minFilter = THREE.LinearMipMapLinearFilter;
            }

            const fog = new THREE.Fog(new THREE.Color(fogColor), fogNear, fogFar);
            scene.fog = fog;

            material = new THREE.ShaderMaterial({
                uniforms: {
                    map: {
                        value: texture
                    },
                    tintEnabled: {
                        value: enableTint ? 1.0 : 0.0
                    },
                    tintColor: {
                        value: tintColor
                    },
                    tintStrength: {
                        value: tintStrength
                    },
                    fogNear: {
                        value: fog.near
                    },
                    fogFar: {
                        value: fog.far
                    },
                    opacityFactor: {
                        value: 1.0
                    },
                    noiseEnabled: {
                        value: enableNoise ? 1.0 : 0.0
                    },
                    noiseStrength: {
                        value: noiseStrength
                    },
                    time: {
                        value: 0.0
                    },
                    depthFadeEnabled: {
                        value: enableDepthFade ? 1.0 : 0.0
                    },
                    depthFadeStrength: {
                        value: depthFadeStrength
                    },
                },
                vertexShader: cloudShader.vertexShader,
                fragmentShader: cloudShader.fragmentShader,
                depthWrite: false,
                depthTest: false,
                transparent: true
            });

            const planeGeo = new THREE.PlaneGeometry(64, 64);

            const count = density;

            for (let i = 0; i < count; i++) {
                const opacityFactor = opacityMin + Math.random() * (opacityMax - opacityMin);

                const mesh = new THREE.Mesh(planeGeo, material.clone());
                mesh.material.uniforms.opacityFactor.value = opacityFactor;

                mesh.position.x = Math.random() * 1000 - 500;
                mesh.position.y = -Math.random() * Math.random() * 200 - 15;
                mesh.position.z = Math.random() * depth;

                mesh.rotation.z = Math.random() * Math.PI;

                const scale = sizeMin + Math.random() * (sizeMax - sizeMin);
                mesh.scale.set(scale, scale, scale);

                scene.add(mesh);
                meshes.push(mesh);
            }

            for (let section = -2; section <= 0; section++) {

				if (section === 0) continue;

				const group = new THREE.Group();

				meshes.forEach(m => {

					const clone = m.clone();

					clone.position.z += section * depth;

					group.add(clone);

				});

				scene.add(group);

			}

            function applyViewMode() {

                switch (viewMode) {

                    case 'top':
                        camera.position.set(0, 0, 6000);
                        camera.rotation.z = Math.PI;
                        break;

                    case 'left':
                        camera.position.set(0, 0, 6000);
                        camera.rotation.z = Math.PI / 2;
                        break;

                    case 'right':
                        camera.position.set(0, 0, 6000);
                        camera.rotation.z = Math.PI * 1.5;
                        break;

                    case 'tilt':
                        camera.position.set(0, 0, 6000);
                        camera.rotation.z = 0.3;
                        break;

                    default:
                        camera.position.set(0, 0, 6000);
                        break;
                }
            }
            applyViewMode();
			
			position = 0;

			if (!reverseDirection) {
				camera.position.z = -position + depth;
			} else {
				camera.position.z = position;
			}

        }

        targetMouseY = baseTiltY;
        mouseY = baseTiltY;
        let rect = container.getBoundingClientRect();

        function onMouseMove(event) {

			if (!isVisible) {
				return;
			}

            rect = container.getBoundingClientRect();

            if (
                event.clientX < rect.left ||
                event.clientX > rect.right ||
                event.clientY < rect.top ||
                event.clientY > rect.bottom
            ) {
                isHovering = false;
                return;
            }

            isHovering = true;

            const localX = event.clientX - rect.left;
            const localY = event.clientY - rect.top;

            let mx = localX - windowHalfX;
            let my = localY - windowHalfY;

            if (mouseAxis === 'x') my = 0;
            if (mouseAxis === 'y') mx = 0;

            targetMouseX = mx * mouseStrength;
            targetMouseY = baseTiltY + (my * mouseStrength);
        }

        function onResize() {
            const width = container.offsetWidth;
            const height = container.offsetHeight;

            windowHalfX = width / 2;
            windowHalfY = height / 2;

            camera.aspect = width / height;
            camera.updateProjectionMatrix();

            renderer.setSize(width, height);
        }

        if (!isMobile) {

            window.addEventListener('mousemove', onMouseMove);

            window.addEventListener('mouseleave', () => {
                isHovering = false;
            });

            container.addEventListener('mouseleave', () => {
                isHovering = false;
            });

        }
        let resizeTimeout;

        window.addEventListener('resize', () => {

            rect = container.getBoundingClientRect();

            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(onResize, 100);

            const wasMobile = isMobile;
            isMobile = window.matchMedia("(max-width: 768px)").matches;

            const isEditor = typeof elementorFrontend !== 'undefined' && elementorFrontend.isEditMode();
        });

        function animate() {

            const now = performance.now();
            const time = now * 0.001;

            requestAnimationFrame(animate);
			
			if (!isVisible) {
				return;
			}

            if (!(isMobile && mobileMode === 'static')) {
                position = (position + speed * 100) % depth;
            }

            if (!isMobile) {
                if (!isHovering) {
                    targetMouseX += (0 - targetMouseX) * 0.05;
                    targetMouseY += (baseTiltY - targetMouseY) * 0.05;
                }
                mouseX += (targetMouseX - mouseX) * 0.05;
                mouseY += (targetMouseY - mouseY) * 0.05;
                camera.position.x += (mouseX - camera.position.x) * 0.05;
                camera.position.y += (-mouseY - camera.position.y) * 0.05;
            }

            if (!(isMobile && mobileMode === 'static')) {
                material.uniforms.time.value = time;
            }
            if (!(isMobile && mobileMode === 'static')) {
                if (!reverseDirection) {
                    camera.position.z = -position + depth;
                } else {
                    camera.position.z = position;
                }
            }

            renderer.render(scene, camera);
        }
	
		$scope.on('destroy', () => {
			window.removeEventListener('mousemove', onMouseMove);
    		observer.disconnect();
		});
    }

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/wpkoi-interactive-particle-drift.default',
            initParticleDrift
        );
    });

})(jQuery);