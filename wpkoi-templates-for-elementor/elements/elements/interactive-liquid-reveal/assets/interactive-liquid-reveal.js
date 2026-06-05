(function($) {

    function initLiquid($scope) {

        const wrapper = $scope[0].querySelector('.wpkoi-liquid-reveal-wrapper');
        if (!wrapper) return;

        let settings = {};
		try {
			settings = JSON.parse(wrapper.dataset.settings || '{}');
		} catch (e) {
			console.warn('WPKoi Liquid: invalid settings JSON', e);
			return;
		}
		
		if (wrapper.dataset.initialized) return;
		wrapper.dataset.initialized = "true";
		
		const listeners = [];

		function addListener(target, type, fn, options) {
			target.addEventListener(type, fn, options);
			listeners.push(() => target.removeEventListener(type, fn, options));
		}
		
		let isVisible = true;

		const observer = new IntersectionObserver((entries) => {
			isVisible = entries[0].isIntersecting;
		}, {
			threshold: 0.1
		});

		observer.observe(wrapper);

        function hexToRgb(hex) {
            hex = hex.replace('#', '');
            if (hex.length === 3) {
                hex = hex.split('').map(x => x + x).join('');
            }
            const num = parseInt(hex, 16);
            return {
                r: ((num >> 16) & 255) / 255,
                g: ((num >> 8) & 255) / 255,
                b: (num & 255) / 255
            };
        }

        const bgColor = hexToRgb(settings.bgColor || '#ffffff');
        const liquidBase = hexToRgb(settings.liquidColor || '#cc7f33');
        const autoDemo = settings.autoDemo ?? true;
		const fluidDetail = settings.fluidDetail || 'medium';
		const renderQuality = settings.renderQuality || 'high';
		
		let simResolution;
		switch (fluidDetail) {
			case 'low':
				simResolution = 64;
				break;
			case 'high':
				simResolution = 256;
				break;
			default:
				simResolution = 128;
		}
	
		let dyeResolution;
		switch (renderQuality) {
			case 'low':
				dyeResolution = 512;
				break;
			case 'ultra':
				dyeResolution = 2048;
				break;
			default:
				dyeResolution = 1024;
		}
	
		let pressureIterations;
		switch (fluidDetail) {
			case 'low':
				pressureIterations = 6;
				break;
			case 'high':
				pressureIterations = 12;
				break;
			default:
				pressureIterations = 8;
		}
	
		const mobileMode = settings.mobileMode || 'optimized';
		const isMobile = window.innerWidth < 768;
	
		if (isMobile && mobileMode === 'optimized') {
			simResolution = 64;
			dyeResolution = 512;
			pressureIterations = 5;
		}
	
		if (isMobile && mobileMode === 'disabled') {
			wrapper.style.display = 'none';
			return;
		}

		if (isMobile && mobileMode === 'image') {
			wrapper.classList.add('is-ready');
			return;
		}

        const WPKOI_SHADERS = {

            vert: `
precision highp float;

    attribute vec2 aPosition;
    varying vec2 vUv;
    varying vec2 vL;
    varying vec2 vR;
    varying vec2 vT;
    varying vec2 vB;
    uniform vec2 u_vertex_texel;

    void main () {
        vUv = aPosition * .5 + .5;
        vL = vUv - vec2(u_vertex_texel.x, 0.);
        vR = vUv + vec2(u_vertex_texel.x, 0.);
        vT = vUv + vec2(0., u_vertex_texel.y);
        vB = vUv - vec2(0., u_vertex_texel.y);
        gl_Position = vec4(aPosition, 0., 1.);
    }
`,

            fragAdvection: `
precision highp float;
    precision highp sampler2D;

    varying vec2 vUv;
    uniform sampler2D u_velocity_txr;
    uniform sampler2D u_input_txr;
    uniform vec2 u_vertex_texel;
    uniform vec2 u_output_textel;
    uniform float u_dt;
    uniform float u_dissipation;

    vec4 bilerp (sampler2D sam, vec2 uv, vec2 tsize) {
        vec2 st = uv / tsize - 0.5;

        vec2 iuv = floor(st);
        vec2 fuv = fract(st);

        vec4 a = texture2D(sam, (iuv + vec2(0.5, 0.5)) * tsize);
        vec4 b = texture2D(sam, (iuv + vec2(1.5, 0.5)) * tsize);
        vec4 c = texture2D(sam, (iuv + vec2(0.5, 1.5)) * tsize);
        vec4 d = texture2D(sam, (iuv + vec2(1.5, 1.5)) * tsize);

        return mix(mix(a, b, fuv.x), mix(c, d, fuv.x), fuv.y);
    }

    void main () {
        vec2 coord = vUv - u_dt * bilerp(u_velocity_txr, vUv, u_vertex_texel).xy * u_vertex_texel;
        gl_FragColor = u_dissipation * bilerp(u_input_txr, coord, u_output_textel);
        gl_FragColor.a = 1.;
    }
`,
            fragDivergence: `
precision highp float;
    precision highp sampler2D;

    varying highp vec2 vUv;
    varying highp vec2 vL;
    varying highp vec2 vR;
    varying highp vec2 vT;
    varying highp vec2 vB;
    uniform sampler2D u_velocity_txr;

    void main () {
        float L = texture2D(u_velocity_txr, vL).x;
        float R = texture2D(u_velocity_txr, vR).x;
        float T = texture2D(u_velocity_txr, vT).y;
        float B = texture2D(u_velocity_txr, vB).y;

        float div = .5 * (R - L + T - B);
        gl_FragColor = vec4(div, 0., 0., 1.);
    }
`,
            fragPressure: `
precision highp float;
    precision highp sampler2D;

    varying highp vec2 vUv;
    varying highp vec2 vL;
    varying highp vec2 vR;
    varying highp vec2 vT;
    varying highp vec2 vB;
    uniform sampler2D u_pressure_txr;
    uniform sampler2D u_divergence_txr;

    void main () {
        float L = texture2D(u_pressure_txr, vL).x;
        float R = texture2D(u_pressure_txr, vR).x;
        float T = texture2D(u_pressure_txr, vT).x;
        float B = texture2D(u_pressure_txr, vB).x;
        float C = texture2D(u_pressure_txr, vUv).x;
        float divergence = texture2D(u_divergence_txr, vUv).x;
        float pressure = (L + R + B + T - divergence) * 0.25;
        gl_FragColor = vec4(pressure, 0., 0., 1.);
    }
`,
            fragGradientSubtract: `
precision highp float;
    precision highp sampler2D;

    varying highp vec2 vUv;
    varying highp vec2 vL;
    varying highp vec2 vR;
    varying highp vec2 vT;
    varying highp vec2 vB;
    uniform sampler2D u_pressure_txr;
    uniform sampler2D u_velocity_txr;

    void main () {
        float L = texture2D(u_pressure_txr, vL).x;
        float R = texture2D(u_pressure_txr, vR).x;
        float T = texture2D(u_pressure_txr, vT).x;
        float B = texture2D(u_pressure_txr, vB).x;
        vec2 velocity = texture2D(u_velocity_txr, vUv).xy;
        velocity.xy -= vec2(R - L, T - B);
        gl_FragColor = vec4(velocity, 0., 1.);
    }
`,
            fragPoint: `
precision highp float;
    precision highp sampler2D;

    varying vec2 vUv;
    uniform sampler2D u_input_txr;
    uniform float u_ratio;
    uniform vec3 u_point_value;
    uniform vec2 u_point;
    uniform float u_point_size;

    void main () {
        vec2 p = vUv - u_point.xy;
        p.x *= u_ratio;
        vec3 splat = pow(2., -dot(p, p) / u_point_size) * u_point_value;
        vec3 base = texture2D(u_input_txr, vUv).xyz;
        gl_FragColor = vec4(base + splat, 1.);
    }
`,

            fragDisplay: `
precision highp float;
precision highp sampler2D;

varying vec2 vUv;

uniform sampler2D u_output_texture;
uniform vec3 u_bg_color; 

void main () {
    vec3 C = texture2D(u_output_texture, vUv).rgb;

    float a = dot(C, vec3(0.333));
    a = pow(.1 * a, .1);
    a = clamp(a, 0., 1.);

    
gl_FragColor = vec4(max(u_bg_color - C, 0.0), 1. - a);
}
`,
        };
	
        const canvas = wrapper.querySelector('canvas');
		if (!canvas) return;

        const imageEl = new Image();
        imageEl.crossOrigin = "anonymous";
        imageEl.src = wrapper.dataset.image;

        canvas.width = canvas.clientWidth;
        canvas.height = canvas.clientHeight;

        const radiusInput = settings.radius || 30;

        const radiusMultiplier = radiusInput / 30;
        const dissipationInput = settings.dissipation ?? 80;

        const dissipationMapped = 0.95 + (dissipationInput / 99) * 0.049;

        const params = {
            SIM_RESOLUTION: simResolution,
    		DYE_RESOLUTION: dyeResolution,
            DENSITY_DISSIPATION: dissipationMapped,
            VELOCITY_DISSIPATION: .9,
            PRESSURE_ITERATIONS: pressureIterations,
            SPLAT_RADIUS: (3 * radiusMultiplier) / window.innerHeight,
            color: {
                r: liquidBase.r,
                g: liquidBase.g,
                b: liquidBase.b
            }
        };

        const pointer = {
            x: .65 * window.innerWidth,
            y: .5 * window.innerHeight,
            dx: 0,
            dy: 0,
            moved: false,
            firstMove: !autoDemo
        };
        if (autoDemo && !isMobile) {
			setTimeout(() => {
				pointer.firstMove = true;
			}, 3000);
		}

        let prevTimestamp = Date.now();

        const gl = canvas.getContext("webgl", {
			alpha: true,
			depth: false,
			stencil: false,
			antialias: false,
			premultipliedAlpha: true,
			preserveDrawingBuffer: false
		});

		if (!gl) {
			console.warn('WebGL not supported');
			wrapper.classList.add('is-ready');
			return;
		}
        const floatExt = gl.getExtension("OES_texture_float") 
			|| gl.getExtension("OES_texture_half_float");

		if (!floatExt) {
			console.warn('No float texture support');
			wrapper.classList.add('is-ready');
			return;
		}

        let outputColor, velocity, divergence, pressure;

        const vertexShader = createShader(
            WPKOI_SHADERS.vert,
            gl.VERTEX_SHADER
        );

        const splatProgram = createProgram("fragPoint");
        const divergenceProgram = createProgram("fragDivergence");
        const pressureProgram = createProgram("fragPressure");
        const gradientSubtractProgram = createProgram("fragGradientSubtract");
        const advectionProgram = createProgram("fragAdvection");
        const displayProgram = createProgram("fragDisplay");

        imageEl.onerror = () => {
			console.warn('Image failed to load');
			wrapper.classList.add('is-ready');
		};
		imageEl.onload = () => {
            initFBOs();
            render();
        };

		let resizeTimeout;

        addListener(window, "resize", () => {
			clearTimeout(resizeTimeout);
			resizeTimeout = setTimeout(() => {
				params.SPLAT_RADIUS = (3 * radiusMultiplier) / window.innerHeight;
				canvas.width = canvas.clientWidth;
				canvas.height = canvas.clientHeight;
			}, 150);
        });

        addListener(window, "click", (e) => {
			const rect = wrapper.getBoundingClientRect();

			pointer.dx = 10;
			pointer.dy = 10;

			pointer.x = e.clientX - rect.left;
			pointer.y = e.clientY - rect.top;

			pointer.firstMove = true;
		});

        addListener(window, "mousemove", (e) => {
			const rect = wrapper.getBoundingClientRect();

			const x = e.clientX - rect.left;
			const y = e.clientY - rect.top;

			pointer.moved = true;

			const mouseMultiplier = isMobile ? 3 : 5;
			pointer.dx = mouseMultiplier * (x - pointer.x);
			pointer.dy = mouseMultiplier * (y - pointer.y);

			pointer.x = x;
			pointer.y = y;

			pointer.firstMove = true;
		});

        addListener(window, "touchmove", (e) => {
			e.preventDefault();

			const rect = wrapper.getBoundingClientRect();

			const x = e.targetTouches[0].clientX - rect.left;
			const y = e.targetTouches[0].clientY - rect.top;

			pointer.moved = true;

			const touchMultiplier = isMobile ? 3 : 8;
			pointer.dx = touchMultiplier * (x - pointer.x);
			pointer.dy = touchMultiplier * (y - pointer.y);

			pointer.x = x;
			pointer.y = y;

			pointer.firstMove = true;
		});

        function createProgram(shaderKey) {
            const shader = createShader(
                WPKOI_SHADERS[shaderKey],
                gl.FRAGMENT_SHADER
            );

            const program = createShaderProgram(vertexShader, shader);
            const uniforms = getUniforms(program);

            return {
                program,
                uniforms
            };
        }

        function createShaderProgram(vertexShader, fragmentShader) {
            const program = gl.createProgram();
            gl.attachShader(program, vertexShader);
            gl.attachShader(program, fragmentShader);
            gl.linkProgram(program);

            if (!gl.getProgramParameter(program, gl.LINK_STATUS)) {
                console.error("Unable to initialize the shader program: " + gl.getProgramInfoLog(program));
                return null;
            }

            return program;
        }

        function getUniforms(program) {
            let uniforms = [];
            let uniformCount = gl.getProgramParameter(program, gl.ACTIVE_UNIFORMS);
            for (let i = 0; i < uniformCount; i++) {
                let uniformName = gl.getActiveUniform(program, i).name;
                uniforms[uniformName] = gl.getUniformLocation(program, uniformName);
            }
            return uniforms;
        }

        function createShader(sourceCode, type) {
            const shader = gl.createShader(type);
            gl.shaderSource(shader, sourceCode);
            gl.compileShader(shader);

            if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
                console.error("An error occurred compiling the shaders: " + gl.getShaderInfoLog(shader));
                gl.deleteShader(shader);
                return null;
            }

            return shader;
        }

		const quad = (() => {
			const vertexBuffer = gl.createBuffer();
			gl.bindBuffer(gl.ARRAY_BUFFER, vertexBuffer);
			gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([
				-1, -1,
				-1, 1,
				1, 1,
				1, -1
			]), gl.STATIC_DRAW);

			const indexBuffer = gl.createBuffer();
			gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, indexBuffer);
			gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array([
				0, 1, 2, 0, 2, 3
			]), gl.STATIC_DRAW);

			return { vertexBuffer, indexBuffer };
		})();

        function blit(target) {
			gl.bindBuffer(gl.ARRAY_BUFFER, quad.vertexBuffer);
			gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, quad.indexBuffer);

			gl.vertexAttribPointer(0, 2, gl.FLOAT, false, 0, 0);
			gl.enableVertexAttribArray(0);

            if (target == null) {
                gl.viewport(0, 0, gl.drawingBufferWidth, gl.drawingBufferHeight);
                gl.bindFramebuffer(gl.FRAMEBUFFER, null);
            } else {
                gl.viewport(0, 0, target.width, target.height);
                gl.bindFramebuffer(gl.FRAMEBUFFER, target.fbo);
            }
            gl.drawElements(gl.TRIANGLES, 6, gl.UNSIGNED_SHORT, 0);
        }

        function initFBOs() {
            const simRes = getResolution(params.SIM_RESOLUTION);
            const dyeRes = getResolution(params.DYE_RESOLUTION);

            outputColor = createDoubleFBO(dyeRes.width, dyeRes.height);
            velocity = createDoubleFBO(simRes.width, simRes.height);
            divergence = createFBO(simRes.width, simRes.height, gl.RGB);
            pressure = createDoubleFBO(simRes.width, simRes.height, gl.RGB);
        }

        function getResolution(resolution) {
            let aspectRatio = gl.drawingBufferWidth / gl.drawingBufferHeight;
            if (aspectRatio < 1)
                aspectRatio = 1. / aspectRatio;

            let min = Math.round(resolution);
            let max = Math.round(resolution * aspectRatio);

            if (gl.drawingBufferWidth > gl.drawingBufferHeight)
                return {
                    width: max,
                    height: min
                };
            else
                return {
                    width: min,
                    height: max
                };
        }

        function createFBO(w, h, type = gl.RGBA) {
            gl.activeTexture(gl.TEXTURE0);

            const texture = gl.createTexture();
            gl.bindTexture(gl.TEXTURE_2D, texture);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.NEAREST);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.NEAREST);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
            gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
            gl.texImage2D(gl.TEXTURE_2D, 0, type, w, h, 0, type, gl.FLOAT, null);

            const fbo = gl.createFramebuffer();
            gl.bindFramebuffer(gl.FRAMEBUFFER, fbo);
            gl.framebufferTexture2D(gl.FRAMEBUFFER, gl.COLOR_ATTACHMENT0, gl.TEXTURE_2D, texture, 0);
            gl.viewport(0, 0, w, h);
            gl.clear(gl.COLOR_BUFFER_BIT);

            return {
                fbo,
                width: w,
                height: h,
                attach(id) {
                    gl.activeTexture(gl.TEXTURE0 + id);
                    gl.bindTexture(gl.TEXTURE_2D, texture);
                    return id;
                }
            };
        }

        function createDoubleFBO(w, h, type) {
            let fbo1 = createFBO(w, h, type);
            let fbo2 = createFBO(w, h, type);

            return {
                width: w,
                height: h,
                texelSizeX: 1. / w,
                texelSizeY: 1. / h,
                read: () => {
                    return fbo1;
                },
                write: () => {
                    return fbo2;
                },
                swap() {
                    let temp = fbo1;
                    fbo1 = fbo2;
                    fbo2 = temp;
                }
            }
        }

        let isReady = false;
		let rafId;

        function render() {

			if (!isVisible) {
				rafId = requestAnimationFrame(render);
				return;
			}

            let dt = (Date.now() - prevTimestamp) / 1000;
			dt = Math.min(dt, 0.016);
            prevTimestamp = Date.now();

            if (!pointer.firstMove) {
                pointer.moved = true;
                const newX = (.65 + .2 * Math.cos(.006 * prevTimestamp) * Math.sin(.008 * prevTimestamp)) * window.innerWidth;
                const newY = (.5 + .12 * Math.sin(.01 * prevTimestamp)) * window.innerHeight;
                pointer.dx = 10 * (newX - pointer.x);
                pointer.dy = 10 * (newY - pointer.y);
                pointer.x = newX;
                pointer.y = newY;
            }

            if (pointer.moved) {
                pointer.moved = false;
                const minInfluence = 0.02;

                gl.useProgram(splatProgram.program);
                gl.uniform1i(splatProgram.uniforms.u_input_txr, velocity.read().attach(0));
                gl.uniform1f(splatProgram.uniforms.u_ratio, canvas.width / canvas.height);
                gl.uniform2f(splatProgram.uniforms.u_point, pointer.x / canvas.width, 1 - pointer.y / canvas.height);
                gl.uniform3f(splatProgram.uniforms.u_point_value, pointer.dx, -pointer.dy, 1);
                gl.uniform1f(splatProgram.uniforms.u_point_size, params.SPLAT_RADIUS);

                blit(velocity.write());
                velocity.swap();

                gl.uniform1i(splatProgram.uniforms.u_input_txr, outputColor.read().attach(0));
				gl.uniform3f(splatProgram.uniforms.u_point_value, Math.max(1 - params.color.r, minInfluence), Math.max(1 - params.color.g, minInfluence), Math.max(1 - params.color.b, minInfluence) );
                blit(outputColor.write());
                outputColor.swap();
            }

            gl.useProgram(divergenceProgram.program);
            gl.uniform2f(divergenceProgram.uniforms.u_vertex_texel, velocity.texelSizeX, velocity.texelSizeY);
            gl.uniform1i(divergenceProgram.uniforms.u_velocity_txr, velocity.read().attach(0));
            blit(divergence);

            gl.useProgram(pressureProgram.program);
            gl.uniform2f(pressureProgram.uniforms.u_vertex_texel, velocity.texelSizeX, velocity.texelSizeY);
            gl.uniform1i(pressureProgram.uniforms.u_divergence_txr, divergence.attach(0));
            for (let i = 0; i < params.PRESSURE_ITERATIONS; i++) {
                gl.uniform1i(pressureProgram.uniforms.u_pressure_txr, pressure.read().attach(1));
                blit(pressure.write());
                pressure.swap();
            }

            gl.useProgram(gradientSubtractProgram.program);
            gl.uniform2f(gradientSubtractProgram.uniforms.u_vertex_texel, velocity.texelSizeX, velocity.texelSizeY);
            gl.uniform1i(gradientSubtractProgram.uniforms.u_pressure_txr, pressure.read().attach(0));
            gl.uniform1i(gradientSubtractProgram.uniforms.u_velocity_txr, velocity.read().attach(1));
            blit(velocity.write());
            velocity.swap();

            gl.useProgram(advectionProgram.program);
            gl.uniform2f(advectionProgram.uniforms.u_vertex_texel, velocity.texelSizeX, velocity.texelSizeY);
            gl.uniform2f(advectionProgram.uniforms.u_output_textel, velocity.texelSizeX, velocity.texelSizeY);

            gl.uniform1i(advectionProgram.uniforms.u_velocity_txr, velocity.read().attach(0));
            gl.uniform1i(advectionProgram.uniforms.u_input_txr, velocity.read().attach(0));
            gl.uniform1f(advectionProgram.uniforms.u_dt, dt);
            gl.uniform1f(advectionProgram.uniforms.u_dissipation, params.VELOCITY_DISSIPATION);
            blit(velocity.write());
            velocity.swap();

            gl.uniform2f(advectionProgram.uniforms.u_output_textel, outputColor.texelSizeX, outputColor.texelSizeY);
            gl.uniform1i(advectionProgram.uniforms.u_velocity_txr, velocity.read().attach(0));
            gl.uniform1i(advectionProgram.uniforms.u_input_txr, outputColor.read().attach(1));
            gl.uniform1f(advectionProgram.uniforms.u_dissipation, params.DENSITY_DISSIPATION);
            blit(outputColor.write());
            outputColor.swap();

            if (!isReady) {
                wrapper.classList.add('is-ready');
                isReady = true;
            }

            const isIdle = !pointer.moved && pointer.firstMove && isReady;

			gl.useProgram(displayProgram.program);

			gl.uniform3f(
				displayProgram.uniforms.u_bg_color,
				bgColor.r,
				bgColor.g,
				bgColor.b
			);

			gl.uniform1i(
				displayProgram.uniforms.u_output_texture,
				outputColor.read().attach(0)
			);

			blit();

			rafId = requestAnimationFrame(render);

        }

		$scope.on('elementor:element:before:destroy', () => {
			listeners.forEach(off => off());
			if (rafId) cancelAnimationFrame(rafId);
		});
    }

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction(
            'frontend/element_ready/wpkoi-interactive-liquid-reveal.default',
            initLiquid
        );
    });

})(jQuery);