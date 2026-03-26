(function ($) {

    function initDistortedImage($scope) {

        const $wrapper = $scope.find('.wpkoi-distorted-image-wrapper');
        if (!$wrapper.length) return;

        const rawSettings = $wrapper.attr('data-settings');
		if (!rawSettings) return;

		let settings;

		try {
			settings = JSON.parse(rawSettings);
		} catch(e) {
			return;
		}
        if (!settings || !settings.image) return;
		
		settings.rgbGlitch        = settings.rgbGlitch === true;
		settings.mouseEffect      = settings.mouseEffect === true;
		settings.disableMobile    = settings.disableMobile === true;
		settings.turbulenceEnabled= settings.turbulenceEnabled === true;
		settings.instanceDensity  = settings.instanceDensity || 1;
		settings.rgbGlitchSpeed  = settings.rgbGlitchSpeed || 1;
		settings.rgbGlitchAmount = settings.rgbGlitchAmount || 0.02;
		settings.mouseMode = settings.mouseMode || 'hole';

        const container = $wrapper[0];
        if (!container) return;
		
		let mediaQuery = window.matchMedia("(max-width: 767px)");
        let currentIsMobile = mediaQuery.matches;
		let currentIsStaticMobile = settings.disableMobile && currentIsMobile;
		
		let resizeObserver;
		let animationId;
		let mesh;
		let mouse = new THREE.Vector2(0.5, 0.5);
		let mouseActive = false;
		let mouseVelocity = new THREE.Vector2(0, 0);
		let lastMouse = new THREE.Vector2(0.5, 0.5);
		let scrollHandler = null;
		let bubbles;
		let curves = [];
		let delays = [];
		let dummy = new THREE.Object3D();
		let bubbleMaterial;
		let bubbleData = [];
		let bricks;
		let brickData = [];
		let brickCurves = [];
		let imageAspect = 1;
		let eventsInitialized = false;
		let hoverActive = false;
		let scrollActive = false;
		let scrollTimeout = null;
		let lastFrame = 0;
		let visible = true;

		const observer = new IntersectionObserver(entries => {
			visible = entries[0].isIntersecting;
		});

		observer.observe(container);
		
		const FPS_LIMIT = 30;

        const scene = new THREE.Scene();

        const camera = new THREE.PerspectiveCamera(45, 1, 0.1, 1000);
        camera.position.z = 2;

        const renderer = new THREE.WebGLRenderer({
            alpha: true,
            antialias: false,
 			powerPreference: "high-performance"
        });

        renderer.setPixelRatio(
			currentIsMobile ? 1 : Math.min(window.devicePixelRatio, 1.5)
		);
        container.appendChild(renderer.domElement);

        const loader = new THREE.TextureLoader();
		loader.setCrossOrigin('anonymous');

        loader.load(settings.image, function (texture) {
			
			try {
				const url = new URL(settings.image, window.location.origin);
				if (!['http:', 'https:'].includes(url.protocol)) return;
			} catch(e) {
				return;
			}

			texture.minFilter = THREE.LinearFilter;
    		texture.anisotropy = renderer.capabilities.getMaxAnisotropy();
			imageAspect = texture.image.width / texture.image.height;
			setupCanvasSize(imageAspect);

			if (settings.projectionMode === "brick") {
				setupBrickProjection(texture, imageAspect);
			} else if (settings.projectionMode === "bubbles") {
				setupBubblesProjection(texture, imageAspect);
			} else if (settings.projectionMode === "spikes") {
				setupSpikesProjection(texture, imageAspect);
			} else if (settings.projectionMode === "wavex") {
				setupWaveXProjection(texture, imageAspect);
			} else if (settings.projectionMode === "rgbmouse") {
				setupRGBMouse(texture, imageAspect);
			} else {
				setupProjectionEngine(texture, imageAspect);
			}
			
			initResizeObserver();
			initMediaQueryListener();
		},
		undefined,
		function(error){
			console.warn('Distorted Image: texture load failed', error);
		});
		
		function initEvents() {

			if (eventsInitialized) return;
			eventsInitialized = true;

			if (settings.animationMode === 'scroll') {

				scrollHandler = () => {
					scrollActive = true;
					clearTimeout(scrollTimeout);

					scrollTimeout = setTimeout(() => {
						scrollActive = false;
					}, 120);
				};
			}

			if (settings.animationMode === 'hover') {

				container.addEventListener('mouseenter', () => {
					hoverActive = true;
				});

				container.addEventListener('mouseleave', () => {
					hoverActive = false;
				});
			}

			if(settings.mouseEffect){
				window.addEventListener('mousemove', updateMousePosition);
			}
		}
		
		function updateMousePosition(e) {

			const rect = container.getBoundingClientRect();

			const inside =
				e.clientX >= rect.left &&
				e.clientX <= rect.right &&
				e.clientY >= rect.top &&
				e.clientY <= rect.bottom;

			if (!inside) {
				mouseActive = false;
				return;
			}

			mouseActive = true;

			mouse.x = (e.clientX - rect.left) / rect.width;
			mouse.y = 1.0 - (e.clientY - rect.top) / rect.height;
		}
		
		function initResizeObserver() {

			if (!window.ResizeObserver) return;

			resizeObserver = new ResizeObserver(entries => {

				for (let entry of entries) {

					const width = entry.contentRect.width;

					if (!width || width === 0) return;

					const height = width / imageAspect;

					if (container.clientHeight !== height) {
						container.style.height = height + "px";
					}

					renderer.setSize(width, height);

					camera.aspect = width / height;
					camera.updateProjectionMatrix();

					renderer.render(scene, camera);
				}
			});

			resizeObserver.observe(container);
		}
		
		function initMediaQueryListener() {

			mediaQuery.addEventListener('change', (e) => {

				const newIsMobile = e.matches;
				const newIsStaticMobile = settings.disableMobile && newIsMobile;

				if (newIsStaticMobile === currentIsStaticMobile) return;

				currentIsMobile = newIsMobile;
				currentIsStaticMobile = newIsStaticMobile;

				if (animationId) {
					cancelAnimationFrame(animationId);
				}

				if (mesh && mesh.material) {
					setupAnimation(mesh.material);
				}

				renderer.render(scene, camera);
			});
		}
		
		function dampedSin(distance, attenuation, frequency, phase) {
			return Math.sin(distance * frequency + phase) *
				   Math.exp(-distance * attenuation);
		}
		
		function generatePoints(width, height, spacing = 0.13) {

			const points = [];
			const rowHeight = spacing * Math.sqrt(3) / 2;

			let row = 0;

			for (let y = -height / 2; y <= height / 2; y += rowHeight) {

				const offset = (row % 2 === 0) ? 0 : spacing / 2;

				for (let x = -width / 2; x <= width / 2; x += spacing) {
					points.push({
						x: x + offset,
						y: y
					});
				}

				row++;
			}

			return points;
		}

		function generateCurve(x, y) {

			const points = [];
			const segments = 2;

			for (let i = 0; i < segments; i++) {
				points.push(
					new THREE.Vector3(
						x,
						y,
						0
					)
				);
			}

			return new THREE.CatmullRomCurve3(points);
		}
		
		function alignOnCurve(object, curve, t) {

			const point = curve.getPoint(t);
			const tangent = curve.getTangent(t);

			object.position.copy(point);

			const axis = new THREE.Vector3(0, 1, 0);
			const quaternion = new THREE.Quaternion();

			quaternion.setFromUnitVectors(axis, tangent.clone().normalize());
			object.quaternion.copy(quaternion);
		}
		
		function getMouseWorldPosition() {

			const vector = new THREE.Vector3(
				(mouse.x * 2 - 1),
				(mouse.y * 2 - 1),
				0
			);

			vector.unproject(camera);

			const dir = vector.sub(camera.position).normalize();
			const distance = -camera.position.z / dir.z;

			return camera.position.clone().add(dir.multiplyScalar(distance));
		}

        function setupBubblesProjection(texture, imageAspect) {

			const visibleHeight = 2 * Math.tan(THREE.MathUtils.degToRad(camera.fov) / 2) * camera.position.z;
			const scaleMultiplier = settings.scaleMultiplier || 1;
			const planeHeight = visibleHeight * scaleMultiplier;
			const planeWidth = planeHeight * imageAspect;

			const baseSpacing = 0.09;
			const spacing = baseSpacing / settings.instanceDensity;

			const points = generatePoints(planeWidth, planeHeight, spacing);

			bubbleData = points.map(p => ({
				baseX: p.x,
				baseY: p.y,
				x: p.x,
				y: p.y,
				vx: 0,
				vy: 0
			}));

			const baseRadius = 0.055;
			const radius = baseRadius / settings.instanceDensity;

			const geometry = new THREE.SphereGeometry(radius, 12, 12);

			const material = new THREE.ShaderMaterial({
				uniforms: {
					u_texture: { value: texture },
					u_planeWidth: { value: planeWidth },
					u_planeHeight: { value: planeHeight },
					u_rgbGlitch: { value: settings.rgbGlitch ? 1.0 : 0.0 },
					u_rgbGlitchSpeed: { value: settings.rgbGlitchSpeed || 1 },
					u_rgbGlitchAmount: { value: settings.rgbGlitchAmount || 0.02 },
					u_time: { value: 0 },
					u_vertexSpeed: { value: settings.vertexSpeed || 1 },
					u_intensity: { value: 0 },
					u_lightIntensity: { value: settings.lightIntensity ?? 0.8 },
					u_ambientLevel: { value: settings.ambientLevel ?? 0.5 },
				},
				transparent: true,
    			depthWrite: false,
				vertexShader: `
					varying vec3 vWorldPos;
					varying vec3 vNormal;

					void main() {

						vNormal = normalize(normalMatrix * normal);

						vec4 worldPosition = modelMatrix * instanceMatrix * vec4(position, 1.0);
						vWorldPos = worldPosition.xyz;

						gl_Position = projectionMatrix * modelViewMatrix * instanceMatrix * vec4(position, 1.0);
					}
					`,
				fragmentShader: `
					uniform sampler2D u_texture;
					uniform float u_planeWidth;
					uniform float u_planeHeight;
					uniform float u_rgbGlitch;
					uniform float u_rgbGlitchSpeed;
					uniform float u_rgbGlitchAmount;
					uniform float u_time;
					uniform float u_vertexSpeed;
					uniform float u_intensity;
					uniform float u_lightIntensity;
					uniform float u_ambientLevel;

					varying vec3 vWorldPos;
					varying vec3 vNormal;

					void main() {

						vec2 uv;
						uv.x = (vWorldPos.x / u_planeWidth) + 0.5;
						uv.y = (vWorldPos.y / u_planeHeight) + 0.5;

						if (uv.x < 0.0 || uv.x > 1.0 || uv.y < 0.0 || uv.y > 1.0) discard;

					vec4 tex;

					if(u_rgbGlitch > 0.5){

						float t = u_time * u_rgbGlitchSpeed;

						float glitchPulse = smoothstep(0.7, 1.0, sin(t * 2.0) * 0.5 + 0.5);

						float strength = u_intensity * glitchPulse;

						vec2 offset = vec2(
							sin(t * 6.0),
							cos(t * 4.0)
						) * u_rgbGlitchAmount * strength;

						float r = texture2D(u_texture, uv + offset).r;
						float g = texture2D(u_texture, uv).g;
						float b = texture2D(u_texture, uv - offset).b;
						float a = texture2D(u_texture, uv).a;

						tex = vec4(r,g,b,a);

					} else {

						tex = texture2D(u_texture, uv);
					}

						vec3 lightDir = normalize(vec3(0.25, 0.5, 1.0));
						float diffuse = max(dot(normalize(vNormal), lightDir), 0.0);

						float lighting =
							u_ambientLevel +
							diffuse * u_lightIntensity;

						vec3 color = tex.rgb * lighting;

						if (tex.a < 0.01) discard;
						gl_FragColor = vec4(color, tex.a);
					}
				`,
			});

			bubbles = new THREE.InstancedMesh(geometry, material, points.length);
			bubbles.instanceMatrix.setUsage(THREE.DynamicDrawUsage);
			scene.add(bubbles);

			curves = [];
			delays = [];

			const minX = -planeWidth * 0.8;

			points.forEach((p, i) => {

				const curve = generateCurve(p.x, p.y);
				curves.push(curve);
				delays.push(Math.log(Math.sqrt(p.x*p.x + p.y*p.y) + 1));

				alignOnCurve(dummy, curve, 0);
				dummy.updateMatrix();
				bubbles.setMatrixAt(i, dummy.matrix);
			});

			mesh = bubbles;

			setupAnimation(material);
		}
		
		function setupSpikesProjection(texture, imageAspect) {

			const distance = camera.position.z;
			const vFOV = THREE.MathUtils.degToRad(camera.fov);
			const visibleHeight = 2 * Math.tan(vFOV / 2) * distance;

			const scaleMultiplier = settings.scaleMultiplier || 1;

			const planeHeight = visibleHeight * scaleMultiplier;
			const planeWidth = planeHeight * imageAspect;

			const geometry = new THREE.PlaneGeometry(
				planeWidth,
				planeHeight,
				400,
				400
			);

			const vertexShader = `
				uniform float u_time;
				uniform float u_strength;
				uniform float u_intensity;
				uniform float u_vertexSpeed;
				uniform float u_bubbleDensity;
				uniform float u_turbulenceEnabled;
				uniform float u_turbulenceSpeed;
				uniform float u_turbulenceFrequency;
				uniform float u_turbulenceAmplitude;
				uniform float u_turbulenceFalloff;
				uniform vec2 u_mouse;
				uniform float u_mouseRadius;
				uniform float u_mouseIntensity;

				varying vec2 vUv;
				varying vec3 vWorldPos;

				float random(vec2 st){
					return fract(sin(dot(st.xy,
						 vec2(12.9898,78.233)))*
						 43758.5453123);
				}

				void main(){

					vUv = uv;
					vec3 pos = position;

					float t = u_time * u_vertexSpeed;

					vec2 grid = uv * u_bubbleDensity;
					vec2 id = floor(grid);
					vec2 f = fract(grid);

					float seed = random(id);

					vec2 center = vec2(
						0.5 + (random(id + 1.3) - 0.5) * 0.6,
						0.5 + (random(id + 2.1) - 0.5) * 0.6
					);

					float dist = distance(f, center);

					float radius = 0.35 + seed * 0.15;

					float bubble = 0.0;

					if(dist < radius){

						float normalized = dist / radius;

						float height = pow(1.0 - normalized, 1.5);

						float breathe = sin(t * 1.5 + seed * 6.28) * 0.5 + 0.5;

						bubble = height * breathe;
					}

					float spikeScale = 0.35;
					float spikeDisplacement =
						bubble *
						u_strength *
						u_intensity *
						spikeScale;

					float turbulence = 0.0;

					if (u_turbulenceEnabled > 0.5) {

						float centerDist = length(pos.xy);

						turbulence =
							sin(centerDist * u_turbulenceFrequency - u_time * u_turbulenceSpeed) *
							exp(-centerDist * u_turbulenceFalloff) *
							u_turbulenceAmplitude *
							u_intensity;
					}

					pos.z += spikeDisplacement + turbulence;

					vec2 diff = uv - u_mouse;
					float mdist = length(diff);
					float mouseInfluence = 1.0 - smoothstep(0.0, u_mouseRadius, mdist);
					mouseInfluence *= u_mouseIntensity;

					pos.z += mouseInfluence * 0.25;
					pos.xy -= diff * mouseInfluence * 0.2;

					vec4 worldPosition = modelMatrix * vec4(pos,1.0);
					vWorldPos = worldPosition.xyz;

					gl_Position = projectionMatrix * viewMatrix * worldPosition;
				}
			`;

			const fragmentShader = `
				uniform sampler2D u_texture;
				uniform float u_rgbGlitch;
				uniform float u_rgbGlitchSpeed;
				uniform float u_rgbGlitchAmount;
				uniform float u_time;
				uniform float u_vertexSpeed;
				uniform float u_intensity;
				uniform vec2 u_mouse;
				uniform float u_mouseRadius;
				uniform float u_mouseIntensity;
				uniform float u_lightIntensity;
				uniform float u_ambientLevel;

				varying vec2 vUv;
				varying vec3 vWorldPos;

				void main() {

					vec2 uv = vUv;

					float holeMask = 0.0;

					if(u_mouseIntensity > 0.001) {

						float radius = u_mouseRadius * 0.4;
						float dist = distance(uv, u_mouse);

						float innerRadius = radius * 0.3;
						float fadeWidth = radius * 0.5;

						float fade = 1.0 - smoothstep(innerRadius, innerRadius + fadeWidth, dist);
						holeMask = (dist < innerRadius) ? 1.0 : fade;

						holeMask *= u_mouseIntensity;
					}

					vec4 tex;

					if(u_rgbGlitch > 0.5){

						float t = u_time * u_rgbGlitchSpeed;

						float glitchPulse = smoothstep(0.7, 1.0, sin(t * 2.0) * 0.5 + 0.5);

						float strength = u_intensity * glitchPulse;

						vec2 offset = vec2(
							sin(t * 6.0),
							cos(t * 4.0)
						) * u_rgbGlitchAmount * strength;

						float r = texture2D(u_texture, uv + offset).r;
						float g = texture2D(u_texture, uv).g;
						float b = texture2D(u_texture, uv - offset).b;
						float a = texture2D(u_texture, uv).a;

						tex = vec4(r,g,b,a);

					}  else {

						tex = texture2D(u_texture, uv);
					}

					vec3 dx = dFdx(vWorldPos);
					vec3 dy = dFdy(vWorldPos);
					vec3 normal = normalize(cross(dx, dy));

					vec3 viewDir = normalize(cameraPosition - vWorldPos);
					float fresnel = pow(1.0 - dot(normal, viewDir), 3.0);

					vec3 lightDir = normalize(vec3(0.2, 0.6, 1.0));
					float diffuse = clamp(dot(normal, lightDir), 0.0, 1.0);

					float lighting =
						u_ambientLevel +
						diffuse * u_lightIntensity +
						fresnel * 0.3;

					vec3 color = tex.rgb * lighting;
					float alpha = tex.a * (1.0 - holeMask);

					gl_FragColor = vec4(color, alpha);
				}
			`;

			const material = new THREE.ShaderMaterial({
				uniforms: {
					u_time: { value: 0 },
					u_texture: { value: texture },
					u_strength: { value: settings.projectionStrength || 1 },
					u_intensity: { value: 0 },
					u_vertexSpeed: { value: settings.vertexSpeed },
					u_mouse: { value: mouse },
					u_mouseIntensity: { value: 0 },
					u_mouseRadius: { value: settings.holeRadius || 0.3 },
					u_rgbGlitch: { value: settings.rgbGlitch ? 1.0 : 0.0 },
					u_rgbGlitchSpeed: { value: settings.rgbGlitchSpeed || 1 },
					u_rgbGlitchAmount: { value: settings.rgbGlitchAmount || 0.02 },
					u_bubbleDensity: { value: 22.0 },
					u_lightIntensity: { value: settings.lightIntensity ?? 0.8 },
					u_ambientLevel: { value: settings.ambientLevel ?? 0.5 },
					u_turbulenceEnabled: { value: settings.turbulenceEnabled ? 1.0 : 0.0 },
					u_turbulenceSpeed: { value: settings.turbulenceSpeed },
					u_turbulenceFrequency: { value: settings.turbulenceFrequency },
					u_turbulenceAmplitude: { value: settings.turbulenceAmplitude },
					u_turbulenceFalloff: { value: settings.turbulenceFalloff },
				},
				vertexShader,
				fragmentShader,
				transparent: true,
				extensions: {
					derivatives: true
				}
			});

			mesh = new THREE.Mesh(geometry, material);
			scene.add(mesh);

			setupAnimation(material, 'projection');
		}
		
		function generateBrickPoints(width, height, brickWidth, brickHeight) {

			const points = [];

			const cols = Math.floor(width / brickWidth);
			const rows = Math.floor(height / brickHeight);

			const startX = -cols * brickWidth / 2;
			const startY = -rows * brickHeight / 2;

			for (let j = 0; j < rows; j++) {

				const offset = (j % 2 === 0) ? 0 : brickWidth / 2;

				for (let i = 0; i < cols; i++) {

					points.push({
						x: startX + i * brickWidth + brickWidth / 2 + offset,
						y: startY + j * brickHeight + brickHeight / 2
					});
				}
			}

			return points;
		}
		
		function generateBrickCurve(x, y) {

			const points = [];
			const segments = 3;

			for (let i = 0; i < segments; i++) {
				points.push(
					new THREE.Vector3(
						x,
						y,
						0
					)
				);
			}

			return new THREE.CatmullRomCurve3(points);
		}


        function setupBrickProjection(texture, imageAspect) {

			const visibleHeight =
				2 * Math.tan(THREE.MathUtils.degToRad(camera.fov) / 2) *
				camera.position.z;

			const scaleMultiplier = settings.scaleMultiplier || 1;

			const planeHeight = visibleHeight * scaleMultiplier;
			const planeWidth = planeHeight * imageAspect;
			
			const density = settings.instanceDensity || 1;

			const brickWidth  = 0.25 / density;
			const brickHeight = 0.10 / density;
			const brickDepth  = 0.12;

			const points = generateBrickPoints(
				planeWidth,
				planeHeight,
				brickWidth,
				brickHeight
			);

			brickData = points.map(p => ({
				baseX: p.x,
				baseY: p.y,
				x: p.x,
				y: p.y,
				vx: 0,
				vy: 0
			}));

			const geometry = new THREE.BoxGeometry(
				brickWidth,
				brickHeight,
				brickDepth
			);

			const material = new THREE.ShaderMaterial({
				uniforms: {
					u_texture: { value: texture },
					u_planeWidth: { value: planeWidth },
					u_planeHeight: { value: planeHeight },
					u_time: { value: 0 },
					u_intensity: { value: 0 },
					u_vertexSpeed: { value: settings.vertexSpeed || 1 },
					u_rgbGlitch: { value: settings.rgbGlitch ? 1.0 : 0.0 },
					u_rgbGlitchSpeed: { value: settings.rgbGlitchSpeed || 1 },
					u_rgbGlitchAmount: { value: settings.rgbGlitchAmount || 0.02 },
					u_lightIntensity: { value: settings.lightIntensity ?? 0.8 },
					u_ambientLevel: { value: settings.ambientLevel ?? 0.5 },
				},
				vertexShader: `
					varying vec3 vWorldPos;
					varying vec3 vNormal;

					void main() {
						vNormal = normalize(normalMatrix * normal);
						vec4 worldPosition = modelMatrix * instanceMatrix * vec4(position, 1.0);
						vWorldPos = worldPosition.xyz;
						gl_Position = projectionMatrix * viewMatrix * worldPosition;
					}
				`,
				fragmentShader: `
					uniform sampler2D u_texture;
					uniform float u_planeWidth;
					uniform float u_planeHeight;
					uniform float u_time;
					uniform float u_vertexSpeed;
					uniform float u_rgbGlitch;
					uniform float u_rgbGlitchSpeed;
					uniform float u_rgbGlitchAmount;
					uniform float u_intensity;
					uniform float u_lightIntensity;
					uniform float u_ambientLevel;

					varying vec3 vWorldPos;
					varying vec3 vNormal;

					void main() {

						vec2 uv;
						uv.x = (vWorldPos.x / u_planeWidth) + 0.5;
						uv.y = (vWorldPos.y / u_planeHeight) + 0.5;

						if (uv.x < 0.0 || uv.x > 1.0 || uv.y < 0.0 || uv.y > 1.0)
							discard;

						vec4 tex;

						if(u_rgbGlitch > 0.5){

							float t = u_time * u_rgbGlitchSpeed;

							float glitchPulse = smoothstep(0.7, 1.0, sin(t * 2.0) * 0.5 + 0.5);

							float strength = u_intensity * glitchPulse;

							vec2 offset = vec2(
								sin(t * 6.0),
								cos(t * 4.0)
							) * u_rgbGlitchAmount * strength;

							float r = texture2D(u_texture, uv + offset).r;
							float g = texture2D(u_texture, uv).g;
							float b = texture2D(u_texture, uv - offset).b;
							float a = texture2D(u_texture, uv).a;

							tex = vec4(r,g,b,a);

						}  else {
							tex = texture2D(u_texture, uv);
						}

						vec3 lightDir = normalize(vec3(0.3, 0.8, 1.0));
						float diffuse = max(dot(normalize(vNormal), lightDir), 0.0);

						float lighting =
							u_ambientLevel +
							diffuse * u_lightIntensity;

						vec3 color = tex.rgb * lighting;

						if (tex.a < 0.01) discard;
						gl_FragColor = vec4(color, tex.a);
					}
				`,
				transparent: true,
				depthWrite: true,
    			depthTest: true,
				alphaTest: 0.01
			});

			bricks = new THREE.InstancedMesh(geometry, material, points.length);
			bricks.instanceMatrix.setUsage(THREE.DynamicDrawUsage);
			scene.add(bricks);

			brickCurves = [];

			const minX = -planeWidth * 0.8;

			points.forEach((p, i) => {

				const curve = generateBrickCurve(p.x, p.y);
				brickCurves.push(curve);

				alignOnCurve(dummy, curve, 0.5);
				dummy.updateMatrix();
				bricks.setMatrixAt(i, dummy.matrix);
			});

			mesh = bricks;

			setupAnimation(material);
		}
		
		function updateBricks(time, intensity) {

			const mouseWorld = (settings.mouseEffect && mouseActive)
				? getMouseWorldPosition()
				: null;

			const influenceRadius = settings.holeRadius || 0.4;
			const returnForce = 0.08;
			const damping = 0.85;

			const frequency = 0.6;
			const speed = 0.5 * (settings.vertexSpeed || 1);
			const strength = 0.5 * settings.projectionStrength || 1;

			for (let i = 0; i < brickData.length; i++) {

				const b = brickData[i];

				if (mouseWorld) {

					const dx = b.x - mouseWorld.x;
					const dy = b.y - mouseWorld.y;
					const dist = Math.sqrt(dx * dx + dy * dy);

					if (dist < influenceRadius && dist > 0.0001) {

						const push = (influenceRadius - dist) * 0.15;

						b.vx += (dx / dist) * push;
						b.vy += (dy / dist) * push;
					}
				}

				b.vx += (b.baseX - b.x) * returnForce;
				b.vy += (b.baseY - b.y) * returnForce;

				b.vx *= damping;
				b.vy *= damping;

				b.x += b.vx;
				b.y += b.vy;

				let z = 0;

				if (settings.turbulenceEnabled) {

					const centerDist = Math.sqrt(b.x * b.x + b.y * b.y);

					z =
						dampedSin(
							centerDist,
							settings.turbulenceFalloff || 1.3,
							settings.turbulenceFrequency || 0.8,
							-time * (settings.turbulenceSpeed || 1.3)
						) *
						(settings.turbulenceAmplitude || 0.25) *
						intensity *
						(settings.projectionStrength || 1);

				} else {

					z =
						Math.sin(b.x * 0.6 - time * speed) *
						Math.cos(b.y * 0.6 + time * speed * 0.7) *
						0.6 *
						intensity *
						strength;
				}

				alignOnCurve(dummy, brickCurves[i], 0.5);

				dummy.position.x = b.x;
				dummy.position.y = b.y;
				dummy.position.z += z;

				dummy.updateMatrix();
				bricks.setMatrixAt(i, dummy.matrix);
			}

			bricks.instanceMatrix.needsUpdate = true;
		}
	
		function setupWaveXProjection(texture, imageAspect) {

			const distance = camera.position.z;
			const vFOV = THREE.MathUtils.degToRad(camera.fov);
			const visibleHeight = 2 * Math.tan(vFOV / 2) * distance;

			const scaleMultiplier = settings.scaleMultiplier || 1;

			const planeHeight = visibleHeight * scaleMultiplier;
			const planeWidth = planeHeight * imageAspect;

			const geometry = new THREE.PlaneGeometry(
				planeWidth,
				planeHeight,
				180,
				180
			);

			const vertexShader = `

				uniform float u_time;
				uniform float u_intensity;
				uniform float u_vertexSpeed;
				uniform float u_strength;

				uniform vec2 u_mouse;
				uniform float u_mouseIntensity;
				uniform float u_mouseRadius;

				varying vec2 vUv;
				varying float wave;


				// ---------------- SIMPLEX NOISE ----------------
				// Ashima / Ian McEwan implementation

				vec3 mod289(vec3 x){return x - floor(x*(1.0/289.0))*289.0;}
				vec4 mod289(vec4 x){return x - floor(x*(1.0/289.0))*289.0;}
				vec4 permute(vec4 x){return mod289(((x*34.0)+1.0)*x);}
				vec4 taylorInvSqrt(vec4 r){return 1.79284291400159 - 0.85373472095314*r;}

				float snoise(vec3 v){

				const vec2 C = vec2(1.0/6.0,1.0/3.0);
				const vec4 D = vec4(0.0,0.5,1.0,2.0);

				vec3 i=floor(v+dot(v,C.yyy));
				vec3 x0=v-i+dot(i,C.xxx);

				vec3 g=step(x0.yzx,x0.xyz);
				vec3 l=1.0-g;
				vec3 i1=min(g.xyz,l.zxy);
				vec3 i2=max(g.xyz,l.zxy);

				vec3 x1=x0-i1+C.xxx;
				vec3 x2=x0-i2+C.yyy;
				vec3 x3=x0-D.yyy;

				i=mod289(i);

				vec4 p=permute(permute(permute(
				i.z+vec4(0.0,i1.z,i2.z,1.0))
				+i.y+vec4(0.0,i1.y,i2.y,1.0))
				+i.x+vec4(0.0,i1.x,i2.x,1.0));

				float n_=0.142857142857;
				vec3 ns=n_*D.wyz-D.xzx;

				vec4 j=p-49.0*floor(p*ns.z*ns.z);

				vec4 x_=floor(j*ns.z);
				vec4 y_=floor(j-7.0*x_);

				vec4 x=x_*ns.x+ns.yyyy;
				vec4 y=y_*ns.x+ns.yyyy;
				vec4 h=1.0-abs(x)-abs(y);

				vec4 b0=vec4(x.xy,y.xy);
				vec4 b1=vec4(x.zw,y.zw);

				vec4 s0=floor(b0)*2.0+1.0;
				vec4 s1=floor(b1)*2.0+1.0;
				vec4 sh=-step(h,vec4(0.0));

				vec4 a0=b0.xzyw+s0.xzyw*sh.xxyy;
				vec4 a1=b1.xzyw+s1.xzyw*sh.zzww;

				vec3 p0=vec3(a0.xy,h.x);
				vec3 p1=vec3(a0.zw,h.y);
				vec3 p2=vec3(a1.xy,h.z);
				vec3 p3=vec3(a1.zw,h.w);

				vec4 norm=taylorInvSqrt(vec4(dot(p0,p0),dot(p1,p1),dot(p2,p2),dot(p3,p3)));
				p0*=norm.x;
				p1*=norm.y;
				p2*=norm.z;
				p3*=norm.w;

				vec4 m=max(0.6-vec4(dot(x0,x0),dot(x1,x1),dot(x2,x2),dot(x3,x3)),0.0);
				m=m*m;

				return 42.0*dot(m*m,vec4(dot(p0,x0),dot(p1,x1),dot(p2,x2),dot(p3,x3)));
				}

				// ------------------------------------------------


				void main(){

					vUv = uv;

					vec3 pos = position;

					float t = u_time * u_vertexSpeed;

					// simplex noise hullÃ¡m
					float n = snoise(vec3(
						pos.x * 1.8,
						pos.y * 1.8,
						t * 0.6
					));

					float w = n * 0.6;

					// widget control
					w *= u_strength * u_intensity;

					// cloth displacement
					pos.z += w;

					// side drift
					pos.x += w * 0.15;

					// mouse ripple
					vec2 diff = uv - u_mouse;
					float dist = length(diff);

					float mouseWave =
						1.0 - smoothstep(0.0, u_mouseRadius, dist);

					mouseWave *= u_mouseIntensity;

					pos.z += mouseWave * 0.35;

					wave = w;

					gl_Position =
						projectionMatrix *
						modelViewMatrix *
						vec4(pos,1.0);
				}
			`;

			const fragmentShader = `

				uniform sampler2D u_texture;
				uniform float u_time;
				uniform float u_intensity;
				uniform float u_rgbGlitch;
				uniform float u_rgbGlitchSpeed;
				uniform float u_rgbGlitchAmount;
				uniform float u_vertexSpeed;

				varying vec2 vUv;
				varying float wave;

				void main(){

					vec2 uv = vUv;

					uv.x += wave * 0.05;
					uv.y += wave * 0.03;

					vec4 tex;

					if(u_rgbGlitch > 0.5){

						float t = u_time * u_rgbGlitchSpeed;

						float glitchPulse = smoothstep(0.7, 1.0, sin(t * 2.0) * 0.5 + 0.5);

						float strength = u_intensity * glitchPulse;

						vec2 offset = vec2(
							sin(t * 6.0),
							cos(t * 4.0)
						) * u_rgbGlitchAmount * strength;

						float r = texture2D(u_texture, uv + offset).r;
						float g = texture2D(u_texture, uv).g;
						float b = texture2D(u_texture, uv - offset).b;
						float a = texture2D(u_texture, uv).a;

						tex = vec4(r,g,b,a);

					} else {

						tex = texture2D(u_texture, uv);

					}

					float light = 0.7 + wave * 0.8;

					vec3 color = tex.rgb * light;

					gl_FragColor = vec4(color, tex.a);
				}
			`;

			const material = new THREE.ShaderMaterial({
				uniforms: {
					u_time: { value: 0 },
					u_texture: { value: texture },
					u_strength: { value: settings.projectionStrength || 1 },
					u_intensity: { value: 0 },
					u_vertexSpeed: { value: settings.vertexSpeed || 1 },
					u_rgbGlitch: { value: settings.rgbGlitch ? 1.0 : 0.0 },
					u_rgbGlitchSpeed: { value: settings.rgbGlitchSpeed || 1 },
					u_rgbGlitchAmount: { value: settings.rgbGlitchAmount || 0.02 },
					u_mouse: { value: mouse },
					u_mouseIntensity: { value: 0 },
					u_mouseRadius: { value: settings.holeRadius || 0.3 },
				},
				vertexShader,
				fragmentShader,
				transparent: true
			});

			mesh = new THREE.Mesh(geometry, material);
			scene.add(mesh);

			setupAnimation(material,'projection');
		}
	
		function setupRGBMouse(texture, imageAspect) {
			
			const distance = camera.position.z;
			const vFOV = THREE.MathUtils.degToRad(camera.fov);
			const visibleHeight = 2 * Math.tan(vFOV / 2) * distance;

			const scaleMultiplier = settings.scaleMultiplier || 1;

			const planeHeight = visibleHeight * scaleMultiplier;
			const planeWidth  = planeHeight * imageAspect;

			const geometry = new THREE.PlaneGeometry(
				planeWidth,
				planeHeight,
				1,
				1
			);

			const material = new THREE.ShaderMaterial({
				uniforms: {
					u_texture: { value: texture },
					u_mouse: { value: mouse },
					u_mouseIntensity: { value: 0 },
					u_resolution: { value: new THREE.Vector2(1, planeHeight / planeWidth) },
					u_time: { value: 0 },

					u_strength: { value: settings.projectionStrength || 1 },
					u_radius: { value: settings.vertexSpeed || 1 },
					u_rgbGlitch: { value: settings.rgbGlitch ? 1.0 : 0.0 },
					u_rgbGlitchSpeed: { value: settings.rgbGlitchSpeed || 1 },
					u_rgbGlitchAmount: { value: settings.rgbGlitchAmount || 0.02 }
				},

				vertexShader: `
					varying vec2 vUv;

					void main(){
						vUv = uv;
						gl_Position = projectionMatrix * modelViewMatrix * vec4(position,1.0);
					}
				`,

				fragmentShader: `
					uniform sampler2D u_texture;
					uniform vec2 u_mouse;
					uniform float u_mouseIntensity;
					uniform vec2 u_resolution;
					uniform float u_time;
					uniform float u_strength;
					uniform float u_radius;
					uniform float u_rgbGlitch;
					uniform float u_rgbGlitchSpeed;
					uniform float u_rgbGlitchAmount;

					varying vec2 vUv;

					float circle(vec2 uv, vec2 center, float radius, float blur){
						vec2 diff = (uv - center) * u_resolution * u_strength;
						float dist = length(diff);
						return smoothstep(radius + blur, radius - blur, dist);
					}

					void main(){

						vec2 uv = vUv;

						float c = circle(uv, u_mouse, 0.0, 0.75 * u_radius);
						c = pow(c, 1.0);
						c *= u_mouseIntensity;

						vec2 newUV = uv;

						float r = texture2D(u_texture, newUV += vec2(sin(c * 2.0)) * 0.07 * u_strength).r;
						float g = texture2D(u_texture, newUV += vec2(sin(c * 2.0)) * 0.075 * u_strength).g;
						float b = texture2D(u_texture, newUV += vec2(sin(c * 2.0)) * 0.08 * u_strength).b;

						vec3 color = vec3(r, g, b);

						if(u_rgbGlitch > 0.5){
							float t = u_time * u_rgbGlitchSpeed;

							vec2 glitch = vec2(
								sin(t * 5.0),
								cos(t * 3.0)
							) * u_rgbGlitchAmount;

							color.r = texture2D(u_texture, uv + glitch).r;
							color.b = texture2D(u_texture, uv - glitch).b;
						}

						gl_FragColor = vec4(color, 1.0);
					}
				`,

				transparent: true
			});

			mesh = new THREE.Mesh(geometry, material);
			scene.add(mesh);

			settings.animationMode = 'hover';
			settings.mouseEffect = true;

			setupAnimation(material, 'projection');
		}
		
		function setupProjectionEngine(texture, imageAspect) {

			const distance = camera.position.z;
			const vFOV = THREE.MathUtils.degToRad(camera.fov);
			const visibleHeight = 2 * Math.tan(vFOV / 2) * distance;
			const visibleWidth = visibleHeight * camera.aspect;

			const scaleMultiplier = settings.scaleMultiplier || 1;

			const planeHeight = visibleHeight * scaleMultiplier;
			const planeWidth = planeHeight * imageAspect;

			const geometry = new THREE.PlaneGeometry(
				planeWidth,
				planeHeight,
				200,
				200
			);

			const vertexShader = `
				uniform float u_time;
				uniform float u_strength;
				uniform float u_intensity;
				uniform float u_vertexSpeed;
				uniform float u_turbulenceEnabled;
				uniform float u_turbulenceSpeed;
				uniform float u_turbulenceFrequency;
				uniform float u_turbulenceAmplitude;
				uniform float u_turbulenceFalloff;
				uniform vec2 u_mouse;
				uniform float u_mouseIntensity;
				uniform float u_mouseRadius;

				varying vec2 vUv;
				varying float vDepth;

				void main() {

					vUv = uv;

					vec3 pos = position;

					float t = u_time * u_vertexSpeed;

					float wave = sin(pos.x * 2.0 + t) * 0.1;
					float noise = sin(pos.y * 4.0 + t * 1.5) * 0.1;

					float displacement = (wave + noise) * u_strength;
					displacement *= u_intensity;

					vec2 diff = uv - u_mouse;
					float dist = length(diff);

					float influence = 1.0 - smoothstep(0.0, u_mouseRadius * 0.35, dist);
					influence *= u_mouseIntensity;

					displacement -= influence * 0.8;

					float ripple = sin(dist * 35.0 - t * 2.0);
					ripple *= influence;
					displacement += ripple * 0.12;

					pos.x += diff.x * influence * 0.15;
					pos.y += diff.y * influence * 0.15;

					float centerDist = length(pos.xy);

					float turbulence = 0.0;

					if (u_turbulenceEnabled > 0.5) {
						turbulence =
							sin(centerDist * u_turbulenceFrequency - u_time * u_turbulenceSpeed) *
							exp(-centerDist * u_turbulenceFalloff) *
							u_turbulenceAmplitude;
					}

					pos.z += turbulence * u_intensity;

					vDepth = displacement;

					gl_Position = projectionMatrix * modelViewMatrix * vec4(pos, 1.0);
				}
			`;

			const fragmentShader = `
				uniform sampler2D u_texture;
				uniform float u_time;
				uniform float u_intensity;
				uniform float u_strength;
				uniform float u_vertexSpeed;
				uniform float u_rgbGlitch;
				uniform float u_rgbGlitchSpeed;
				uniform float u_rgbGlitchAmount;
				uniform float u_mouseIntensity;
				uniform float u_mouseMode;
				uniform float u_lightIntensity;
				uniform float u_ambientLevel;
				uniform vec2 u_mouse;
				uniform float u_mouseRadius;
				uniform float u_mouseEffect;

				varying vec2 vUv;
				varying float vDepth;

				void main() {

					vec2 uv = vUv;

					float holeMask = 0.0;

					if(u_mouseIntensity > 0.001){

						bool inside =
							u_mouse.x >= 0.0 && u_mouse.x <= 1.0 &&
							u_mouse.y >= 0.0 && u_mouse.y <= 1.0;

						if(inside){

							float radius = u_mouseRadius * 0.28;
							float dist = distance(uv, u_mouse);

							float influence = 1.0 - smoothstep(0.0, radius, dist);
							influence *= u_mouseIntensity;

							if(u_mouseMode < 0.5){
								// ðŸ•³ HOLE MODE (original)
								float hole = 1.0 - smoothstep(radius * 0.3, radius, dist);
								holeMask = hole * u_mouseIntensity;

								float ripple =
									sin(dist * 55.0 - u_time * 4.0) *
									smoothstep(radius * 2.0, radius, dist) *
									0.009;

								uv += normalize(uv - u_mouse) * ripple;
								uv += (uv - u_mouse) * 0.015 * influence;

							}else{

								float angle = influence * 2.5;
								vec2 dir = uv - u_mouse;

								float s = sin(angle);
								float c = cos(angle);

								dir = mat2(c, -s, s, c) * dir;

								uv = u_mouse + dir;

								uv += dir * 0.15 * influence;

								float ripple =
									sin(dist * 40.0 - u_time * 3.0) *
									0.01 *
									influence;

								uv += normalize(dir) * ripple;
							}
						}
					}

					float t = u_time * u_vertexSpeed;

					float distortion = sin(uv.y * 4.0 + t * 1.5) * 0.03;
					distortion += sin(uv.x * 3.0 + t) * 0.02;

					float noise = sin(uv.x * 20.0 + t * 2.0) * 0.002;
					noise += sin(uv.y * 18.0 - t * 1.7) * 0.002;

					uv.x += distortion * u_strength * u_intensity;
					uv.y += distortion * 0.5 * u_strength * u_intensity;

					uv += noise * u_intensity;

					vec4 tex;

					if(u_rgbGlitch > 0.5){

						float t = u_time * u_rgbGlitchSpeed;

						float glitchPulse = smoothstep(0.7, 1.0, sin(t * 2.0) * 0.5 + 0.5);

						float strength = u_intensity * glitchPulse;

						vec2 offset = vec2(
							sin(t * 6.0),
							cos(t * 4.0)
						) * u_rgbGlitchAmount * strength;

						float r = texture2D(u_texture, uv + offset).r;
						float g = texture2D(u_texture, uv).g;
						float b = texture2D(u_texture, uv - offset).b;
						float a = texture2D(u_texture, uv).a;

						tex = vec4(r,g,b,a);

					} else {

						tex = texture2D(u_texture, uv);
					}

					float depth = vDepth;

					float depthLight = smoothstep(-0.3, 0.3, depth);

					depthLight = mix(0.5, depthLight, 0.7);

					float lighting =
						u_ambientLevel +
						depthLight * u_lightIntensity * 0.5;

					vec3 shaded = tex.rgb * lighting;

					float alpha = tex.a * (1.0 - holeMask);

					gl_FragColor = vec4(shaded, alpha);
				}
			`;

			const material = new THREE.ShaderMaterial({
				uniforms: {
					u_time: { value: 0 },
					u_mouse: { value: mouse },
					u_mouseIntensity: { value: 0 },
					u_texture: { value: texture },
					u_strength: { value: settings.projectionStrength || 1 },
					u_intensity: { value: 0 },
					u_vertexSpeed: { value: settings.vertexSpeed },
					u_rgbGlitch: { value: settings.rgbGlitch ? 1.0 : 0.0 },
					u_rgbGlitchSpeed: { value: settings.rgbGlitchSpeed || 1 },
					u_rgbGlitchAmount: { value: settings.rgbGlitchAmount || 0.02 },
					u_mouseEffect: { value: settings.mouseEffect ? 1.0 : 0.0 },
					u_mouseMode: { value: settings.mouseMode === 'distort' ? 1.0 : 0.0 },
					u_mouseRadius: { value: settings.holeRadius || 0.3 },
					u_lightIntensity: { value: settings.lightIntensity ?? 0.8 },
					u_ambientLevel: { value: settings.ambientLevel ?? 0.5 },
					u_turbulenceEnabled: { value: settings.turbulenceEnabled ? 1.0 : 0.0 },
					u_turbulenceSpeed: { value: settings.turbulenceSpeed },
					u_turbulenceFrequency: { value: settings.turbulenceFrequency },
					u_turbulenceAmplitude: { value: settings.turbulenceAmplitude },
					u_turbulenceFalloff: { value: settings.turbulenceFalloff },
				},
				vertexShader,
				fragmentShader,
				transparent: true
			});

			mesh = new THREE.Mesh(geometry, material);
			scene.add(mesh);

			setupAnimation(material, 'projection');
		}
		
		function updateBubbles(time, intensity) {

			const mouseWorld = mouseActive ? getMouseWorldPosition() : null;

			const influenceRadius = (settings.holeRadius || 0.3) * 1.2;
			const forceStrength = 0.015;
			const returnForce = 0.08;
			const damping = 0.85;

			for (let i = 0; i < bubbleData.length; i++) {

				const b = bubbleData[i];

				if (mouseWorld) {

					const dx = b.x - mouseWorld.x;
					const dy = b.y - mouseWorld.y;

					const dist = Math.sqrt(dx * dx + dy * dy);

					if (dist < influenceRadius) {

						const force = (1 - dist / influenceRadius) * forceStrength;

						b.vx += (dx / dist) * force;
						b.vy += (dy / dist) * force;
					}
				}

				b.vx += (b.baseX - b.x) * returnForce;
				b.vy += (b.baseY - b.y) * returnForce;

				b.vx *= damping;
				b.vy *= damping;

				b.x += b.vx;
				b.y += b.vy;

				const distance = Math.sqrt(b.baseX * b.baseX + b.baseY * b.baseY);

				const strength = settings.projectionStrength || 1;

				let wave = 0;

				if (settings.turbulenceEnabled) {

					const freq  = settings.turbulenceFrequency || 0.8;
					const speed = settings.turbulenceSpeed || 1.3;
					const amp   = settings.turbulenceAmplitude || 0.25;
					const fall  = settings.turbulenceFalloff || 1.3;

					wave =
						dampedSin(distance, fall, freq, -time * speed) *
						amp *
						intensity *
						(settings.projectionStrength || 1);

				} else {

					const strength = settings.projectionStrength || 1;
					const speed    = settings.vertexSpeed || 1;

					wave =
						Math.sin(distance * 1.8 - time * speed) *
						0.35 *
						intensity *
						0.5 * strength;
				}

				dummy.position.set(b.x, b.y, wave);

				const breathe = 1.0 + Math.sin(time * 1.8 + i * 0.15) * 0.07;
				dummy.scale.set(breathe, breathe, breathe);

				dummy.updateMatrix();
				bubbles.setMatrixAt(i, dummy.matrix);
			}

			bubbles.instanceMatrix.needsUpdate = true;
		}

        function setupAnimation(material, engineType = 'standard') {
	
			initEvents();

			if (currentIsStaticMobile) {

				if (material && material.uniforms) {

					if (material.uniforms.u_intensity)
						material.uniforms.u_intensity.value = 0;

					if (material.uniforms.u_mouseIntensity)
						material.uniforms.u_mouseIntensity.value = 0;

					if (material.uniforms.u_time)
						material.uniforms.u_time.value = 0;
				}

				renderer.render(scene, camera);
				return;
			}
	
			let time = 0;
			let intensity = 0;

			const intensitySpeed = 0.08;
			const timeSpeed = 0.02;
			
			if (!currentIsStaticMobile && settings.animationMode === 'scroll') {

				scrollHandler = () => {
					scrollActive = true;
					clearTimeout(scrollTimeout);

					scrollTimeout = setTimeout(() => {
						scrollActive = false;
					}, 120);
				};

				window.addEventListener('scroll', scrollHandler);
			}

			function animate(now) {
				
				animationId = requestAnimationFrame(animate);

				if (now - lastFrame < 1000 / FPS_LIMIT) return;
				lastFrame = now;
				
				if(!visible) return;

				time += timeSpeed;

				let targetIntensity = 0;

				if (settings.animationMode === 'autoplay') targetIntensity = 1;
				if (settings.animationMode === 'hover' && hoverActive) targetIntensity = 1;
				if (settings.animationMode === 'scroll' && scrollActive) targetIntensity = 1;

				intensity += (targetIntensity - intensity) * intensitySpeed;

				let mouseTarget = settings.mouseEffect && mouseActive ? 1 : 0;

				if (material && material.uniforms) {

					if (material.uniforms.u_time)
						material.uniforms.u_time.value = time;

					if (material.uniforms.u_intensity)
						material.uniforms.u_intensity.value = intensity;

					if (material.uniforms.u_mouseIntensity)
						material.uniforms.u_mouseIntensity.value +=
							(mouseTarget - material.uniforms.u_mouseIntensity.value) * intensitySpeed;

					if (material.uniforms.u_mouse)
						material.uniforms.u_mouse.value.copy(mouse);
				}

				if (settings.projectionMode === 'bubbles' && bubbles) {
					updateBubbles(time, intensity);
				}
				
				if (settings.projectionMode === 'brick' && bricks) {
					updateBricks(time, intensity);
				}

				renderer.render(scene, camera);
			}

			animate(performance.now());
		}

        function setupCanvasSize(imageAspect) {

            const wrapperWidth = $wrapper[0].clientWidth;
            const calculatedHeight = wrapperWidth / imageAspect;

            container.style.height = calculatedHeight + "px";

            renderer.setSize(wrapperWidth, calculatedHeight);

            camera.aspect = wrapperWidth / calculatedHeight;
            camera.updateProjectionMatrix();
        }
		
		function destroy() {

			cancelAnimationFrame(animationId);

			if (scrollHandler) {
				window.removeEventListener('scroll', scrollHandler);
			}
			
			if(observer){
			 observer.disconnect();
			}
			
			window.removeEventListener('mousemove', updateMousePosition);

			if (mesh) {
				mesh.geometry.dispose();
				mesh.material.dispose();
				scene.remove(mesh);
			}

			if (renderer) {
				renderer.dispose();
				renderer.forceContextLoss();
				renderer.domElement.remove();
			}
			
			if (resizeObserver) {
				resizeObserver.disconnect();
			}
		}
			
		return {
			destroy
		};

    }

    $(window).on('elementor/frontend/init', function () {

        elementorFrontend.hooks.addAction(
			'frontend/element_ready/wpkoi-distorted-image.default',
			   function ($scope) {

				  const instance = initDistortedImage($scope);

				  $scope.on('remove', function () {
					 if (instance && instance.destroy) {
						instance.destroy();
					 }
				  });
			   }
		);

    });

})(jQuery);