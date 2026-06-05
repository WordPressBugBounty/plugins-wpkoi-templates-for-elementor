(function(window, $, undefined) {
    'use strict';
    const THREE_LOCAL = window.THREE;

    function createTextTexture(text, style, config = {}) {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        const fontSize = style.size || 100;
        const paddingTop = style.paddingTop || 0;
        const paddingRight = style.paddingRight || 0;
        const paddingBottom = style.paddingBottom || 0;
        const paddingLeft = style.paddingLeft || 0;

        ctx.font = `${style.weight} ${fontSize}px ${style.family}`;

        const metrics = ctx.measureText(text);

        const textWidth = metrics.width;
        const lineHeight = style.leading || 1.2;

        const textHeight = fontSize * lineHeight;
        const extra = fontSize * 0.2;

        const width = Math.ceil(textWidth + paddingLeft + paddingRight);

        const height = Math.ceil(textHeight + paddingTop + paddingBottom);

        const dpr = Math.min(window.devicePixelRatio || 1, 2);

        canvas.width = width * dpr;
        canvas.height = height * dpr;

        ctx.scale(dpr, dpr);

        ctx.font = `${style.weight} ${fontSize}px ${style.family}`;
        ctx.fillStyle = style.fill || '#000';

        ctx.textBaseline = 'middle';

        const x = paddingLeft;
        const y = height / 2;

        ctx.fillText(text, x, y);

        const texture = new THREE_LOCAL.CanvasTexture(canvas);
        texture.needsUpdate = true;

        return {
            texture,
            width,
            height
        };
    }

    function buildFragmentShader(mainImageSrc, type = 'rolling') {

        const noiseBlock = (type === 'liquid') ?
            NOISE3D_SHADER :
            NOISE_SHADER;

        return `
        uniform sampler2D uTexture;
        uniform vec2 uResolution;
        uniform float uGlobalTime;
		uniform vec4 uBlendColor;

        uniform float uSineDistortAmplitude;
        uniform float uSineDistortSpread;
        uniform float uSineDistortCycleCount;
        uniform float uNoiseDistortVolatility;
        uniform float uNoiseDistortAmplitude;
        uniform vec2 uDistortPosition;
        uniform float uOffset;
		uniform float uRotation;
		uniform float uApplyBlur;
		uniform float uAnimateNoise;
        uniform float uSpeed;
		uniform float uPointCellWidth;
		uniform float uPointRadius;
		uniform float uDodge;
		uniform vec2 uDodgePosition;
		uniform float uDodgeSpread;

        uniform float uVolatility;
        uniform float uSeed;

        varying vec2 vUv;

        vec4 textTexture(vec2 uv) {
            return texture2D(uTexture, uv);
        }

        ${PI_SHADER}
        ${LINEMATH_SHADER}
        ${noiseBlock}

        ${mainImageSrc}

        void main() {
            vec4 color;
            vec2 fragCoord = vUv * uResolution;

            mainImage(color, fragCoord);

            gl_FragColor = color;
        }
    `;
    }

    class SimpleThreeText {

        constructor(el, text, style, mainImageSrc, config = {}) {
            const {
                texture,
                width,
                height
            } = createTextTexture(text, style, config);

            const scene = new THREE_LOCAL.Scene();

            const camera = new THREE_LOCAL.OrthographicCamera(
                width / -2, width / 2,
                height / 2, height / -2,
                1, 1000
            );
            camera.position.z = 1;

            const renderer = new THREE_LOCAL.WebGLRenderer({
                alpha: true
            });
            const dpr = Math.min(window.devicePixelRatio || 1, 2);

            renderer.setPixelRatio(dpr);
            renderer.setSize(width, height, false);

            renderer.domElement.style.width = width + 'px';
            renderer.domElement.style.height = height + 'px';
            renderer.domElement.style.display = 'inline';

            el.appendChild(renderer.domElement);
            const baseUniforms = {
                uTexture: {
                    value: texture
                },
                uResolution: {
                    value: new THREE_LOCAL.Vector2(width, height)
                },
                uGlobalTime: {
                    value: 0
                },
                uBlendColor: {
                    value: new THREE_LOCAL.Vector4(1, 1, 1, 1)
                }
            };

            let uniforms = {
                ...baseUniforms
            };

            if (config.type === 'rolling') {
                uniforms = {
                    ...uniforms,
                    uSineDistortAmplitude: {
                        value: 0
                    },
                    uSineDistortSpread: {
                        value: 0.354
                    },
                    uSineDistortCycleCount: {
                        value: 5
                    },
                    uNoiseDistortVolatility: {
                        value: 0
                    },
                    uNoiseDistortAmplitude: {
                        value: 0.168
                    },
                    uDistortPosition: {
                        value: new THREE_LOCAL.Vector2(0.38, 0.68)
                    },
                    uRotation: {
                        value: 48
                    },
                    uSpeed: {
                        value: 0.421
                    }
                };
            }

            if (config.type === 'liquid') {
                uniforms = {
                    ...uniforms,
                    uSpeed: {
                        value: 1.0
                    },
                    uVolatility: {
                        value: 0.15
                    },
                    uSeed: {
                        value: 0.4
                    }
                };
            }

            if (config.type === 'channelsplit') {
                uniforms = {
                    ...uniforms,
                    uOffset: {
                        value: 0.5
                    },
                    uRotation: {
                        value: 90
                    },
                    uApplyBlur: {
                        value: 1
                    },
                    uAnimateNoise: {
                        value: 0
                    }
                };
            }

            if (config.type === 'flies') {
                uniforms = {
                    ...uniforms,
                    uPointCellWidth: {
                        value: 0.025
                    },
                    uPointRadius: {
                        value: 0.5
                    },
                    uDodge: {
                        value: 0.0
                    },
                    uDodgePosition: {
                        value: new THREE_LOCAL.Vector2(0.2, 0.2)
                    },
                    uDodgeSpread: {
                        value: 0.5
                    },
                    uSpeed: {
                        value: 1.2
                    }
                };
            }

            const material = new THREE_LOCAL.ShaderMaterial({
                uniforms,
                vertexShader: `
                varying vec2 vUv;
                void main() {
                    vUv = uv;
                    gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
                }
            `,
                fragmentShader: buildFragmentShader(
                    mainImageSrc,
                    config.type
                ),
                transparent: true
            });

            const geometry = new THREE_LOCAL.PlaneGeometry(width, height);
            const mesh = new THREE_LOCAL.Mesh(geometry, material);

            scene.add(mesh);

            const MATERIAL_DEFAULTS = {
                rolling: {
                    animatable: [{
                        prop: 'uSineDistortAmplitude',
                        from: 0,
                        to: 0.5
                    }],
                    easeFactor: 0.05
                },

                liquid: {
                    animatable: [{
                        prop: 'uVolatility',
                        from: 0,
                        to: 0.9
                    }],
                    easeFactor: 0.05
                },

                channelsplit: {
                    animatable: [{
                            prop: 'uOffset',
                            from: 0.02,
                            to: 0.35
                        },
                        {
                            prop: 'uRotation',
                            from: 140,
                            to: 40
                        }
                    ],
                    easeFactor: 0.05
                },

                flies: {
                    animatable: [{
                            prop: 'uPointCellWidth',
                            from: 0.025,
                            to: 0.028
                        },
                        {
                            prop: 'uPointRadius',
                            from: 0.5,
                            to: 6
                        }
                    ],
                    easeFactor: 0.08
                }
            };

            const defaults = MATERIAL_DEFAULTS[config.type] || {};

            let lastTime = 0;

			this.render = (engine) => {
				const now = performance.now();

				const frameLimit = engine?.scrollDelta > 0.5 ? 50 : 32;

				if (now - lastTime < frameLimit) return;

				lastTime = now;

				uniforms.uGlobalTime.value += engine?.delta || 0.016;
				renderer.render(scene, camera);
			};

            const rendererController = new Renderer({
                animatable: config.animatable || defaults.animatable || [],
                uniforms: config.uniforms || [],
                easeFactor: config.easeFactor || defaults.easeFactor || 0.1,
                effecttrigger: config.effecttrigger || 'onscroll',
                intensity: config.intensity || 5
            }, material, this.render);

            WPKoiEngine.add(rendererController);

            this._renderer = renderer;
            this._geometry = geometry;
            this._material = material;
            this._texture = texture;
            this._mesh = mesh;
            this._scene = scene;
            this._controller = rendererController;

            this.destroy = () => {

                if (this._controller) {
                    WPKoiEngine.instances.delete(this._controller);
                }

                if (this._geometry) this._geometry.dispose();
                if (this._material) this._material.dispose();
                if (this._texture) this._texture.dispose();

                if (this._renderer) this._renderer.dispose();

                if (this._renderer?.domElement?.parentNode) {
                    this._renderer.domElement.parentNode.removeChild(this._renderer.domElement);
                }

            };
        }
    }
    const PI_SHADER = `
#define PI 3.141592653589793
`;

    const LINEMATH_SHADER = `
float slopeForDegrees(float deg) {
    deg = mod(deg, 360.0);

    float radians = deg * (PI / 180.0);

    return tan(radians);
}

float yForXOnSlope(float x, float slope, vec2 p2) {
    return -1.0 * ((slope * (p2.x - x)) - p2.y);
}

float xForYOnSlope(float y, float slope, vec2 p2) {
    return ((y - p2.y) + (slope * p2.x)) / slope;
}

float normalizedSlope(float slope, vec2 resolution) {
    vec2 p = vec2(1.0) / resolution;
    return ((slope * 100.0) / p.x) / (100.0 / p.x);
}

vec2 offsetsForCoordAtDistanceOnSlope(float d, float slope) {
    return vec2(
        (d * cos(atan(slope))),
        (d * sin(atan(slope)))
    );
}

bool lineLineIntersection(out vec2 intersect, in vec2 p1, in float m1, in vec2 p2, in float m2) {
    float dx = 1.0;
    float dy = m1;

    float dxx = 1.0;
    float dyy = m2;

    float denominator = ((dxx * dy) - (dyy * dx));
    if (denominator == 0.0) {
        return false;
    }

    float u = ((dx * (p2.y - p1.y)) + (dy * (p1.x - p2.x))) / denominator;

    intersect = p2 + (u * vec2(dxx, dyy));
    return true;
}

bool lineLineSegmentIntersection(out vec2 intersect, vec2 p, float m, vec2 a, vec2 b) {

    vec2 dir1 = vec2(1.0, m);
    vec2 dir2 = b - a;

    float denom = dir1.x * dir2.y - dir1.y * dir2.x;

    if (abs(denom) < 0.00001) return false;

    vec2 diff = a - p;

    float t = (diff.x * dir2.y - diff.y * dir2.x) / denom;
    float u = (diff.x * dir1.y - diff.y * dir1.x) / denom;

    if (u < 0.0 || u > 1.0) return false;

    intersect = p + t * dir1;
    return true;
}

void intersectsOnRectForLine(out vec2 iA, out vec2 iB, in vec2 rMinXY, in vec2 rMaxXY, in vec2 point, in float slope) {
    bool firstIntersectFound = false;

    vec2 intersectLeft = vec2(0.0);
    vec2 intersectTop = vec2(0.0);
    vec2 intersectRight = vec2(0.0);
    vec2 intersectBottom = vec2(0.0);

    bool intersectsLeft = lineLineSegmentIntersection(intersectLeft, point, slope, rMinXY, vec2(rMinXY.x, rMaxXY.y));
    bool intersectsTop = lineLineSegmentIntersection(intersectTop, point, slope, vec2(rMinXY.x, rMaxXY.y), rMaxXY);
    bool intersectsRight = lineLineSegmentIntersection(intersectRight, point, slope, rMaxXY, vec2(rMaxXY.x, rMinXY.y));
    bool intersectsBottom = lineLineSegmentIntersection(intersectBottom, point, slope, rMinXY, vec2(rMaxXY.x, rMinXY.y));

    if (intersectsLeft) {
        if (firstIntersectFound) iB = intersectLeft;
        else { iA = intersectLeft; firstIntersectFound = true; }
    }

    if (intersectsTop) {
        if (firstIntersectFound) iB = intersectTop;
        else { iA = intersectTop; firstIntersectFound = true; }
    }

    if (intersectsRight) {
        if (firstIntersectFound) iB = intersectRight;
        else { iA = intersectRight; firstIntersectFound = true; }
    }

    if (intersectsBottom) {
        if (firstIntersectFound) iB = intersectBottom;
        else iA = intersectBottom;
    }
}

`;

    const NOISE_SHADER = `
float random(vec2 st) {
    return fract(sin(dot(st.xy, vec2(12.9898,78.233))) * 43758.5453123);
}

float noise(vec2 st) {
    vec2 i = floor(st);
    vec2 f = fract(st);

    float a = random(i);
    float b = random(i + vec2(1.0, 0.0));
    float c = random(i + vec2(0.0, 1.0));
    float d = random(i + vec2(1.0, 1.0));

    vec2 u = f * f * (3.0 - 2.0 * f);

    return mix(a, b, u.x) +
           (c - a) * u.y * (1.0 - u.x) +
           (d - b) * u.x * u.y;
}

float noise(float x) {
    float i = floor(x);
    float f = fract(x);
    float u = f * f * (3.0 - 2.0 * f);
    return mix(random(vec2(i)), random(vec2(i + 1.0)), u);
}
`;

    const ROLLING_SHADER = `
		float toFixedTwo(float f) {
            return float(int(f * 100.0)) / 100.0;
        }

        float impulse(float k, float x) {
            float h = k * x;
            return h * exp(1.0 - h);
        }

        vec2 waveOffset(vec2 fragCoord, float sineDistortSpread, float sineDistortCycleCount, float sineDistortAmplitude, float noiseDistortVolatility, float noiseDistortAmplitude, vec2 distortPosition, float deg, float speed) {

            deg = toFixedTwo(deg);

            float centerDistance = 0.5;
            vec2 centerUv = vec2(centerDistance);
            vec2 centerCoord = uResolution.xy * centerUv;

            float changeOverTime = uGlobalTime * speed;

            float slope = normalizedSlope(slopeForDegrees(deg), uResolution.xy);
            float perpendicularDeg = mod(deg + 90.0, 360.0); 
            float perpendicularSlope = normalizedSlope(slopeForDegrees(perpendicularDeg), uResolution.xy);


            vec2 edgeIntersectA = vec2(0.0);
            vec2 edgeIntersectB = vec2(0.0);
            intersectsOnRectForLine(edgeIntersectA, edgeIntersectB, vec2(0.0), uResolution.xy, centerCoord, slope);
            float crossSectionLength = distance(edgeIntersectA, edgeIntersectB);
			if (distance(edgeIntersectA, edgeIntersectB) < 0.001) {
				return vec2(0.0);
			}
			if (crossSectionLength < 0.0001) return vec2(0.0);

            float thresholdDegA = atan(centerCoord.y / centerCoord.x) * (180.0 / PI);
            float thresholdDegB = mod(thresholdDegA + 180.0, 360.0);

            vec2 edgeIntersect = vec2(0.0);
            if (deg < thresholdDegA || deg > thresholdDegB) {
                edgeIntersect = edgeIntersectA;
            } else {
                edgeIntersect = edgeIntersectB;
            }

            vec2 perpendicularIntersectA = vec2(0.0);
            vec2 perpendicularIntersectB = vec2(0.0);
            intersectsOnRectForLine(perpendicularIntersectA, perpendicularIntersectB, vec2(0.0), uResolution.xy, centerCoord, perpendicularSlope); 
            float perpendicularLength = distance(perpendicularIntersectA, perpendicularIntersectA);

            vec2 coordLineIntersect = vec2(0.0);
            lineLineIntersection(coordLineIntersect, centerCoord, slope, fragCoord, perpendicularSlope);


            vec2 distortPositionIntersect = vec2(0.0);
            lineLineIntersection(distortPositionIntersect, distortPosition * uResolution.xy, perpendicularSlope, edgeIntersect, slope);
            float distortDistanceFromEdge = (distance(edgeIntersect, distortPositionIntersect) / crossSectionLength);

            float uvDistanceFromDistort = distance(edgeIntersect, coordLineIntersect) / crossSectionLength;
            float noiseDistortVarianceAdjuster = uvDistanceFromDistort + changeOverTime;
            uvDistanceFromDistort += -centerDistance + distortDistanceFromEdge + changeOverTime;
            uvDistanceFromDistort = mod(uvDistanceFromDistort, 1.0); 


            float minThreshold = centerDistance - sineDistortSpread;
            float maxThreshold = centerDistance + sineDistortSpread;

            uvDistanceFromDistort = clamp(((uvDistanceFromDistort - minThreshold)/(maxThreshold - minThreshold)), 0.0, 1.0);
            if (sineDistortSpread < 0.5) {
                uvDistanceFromDistort = impulse(uvDistanceFromDistort, uvDistanceFromDistort);
            }

            float sineDistortion = sin(uvDistanceFromDistort * PI * sineDistortCycleCount) * sineDistortAmplitude;

            float noiseDistortion = noise(noiseDistortVolatility * noiseDistortVarianceAdjuster) * noiseDistortAmplitude;
            if (noiseDistortVolatility > 0.0) {
                noiseDistortion -= noiseDistortAmplitude / 2.0; 
            }
            noiseDistortion *= (sineDistortion > 0.0 ? 1.0 : -1.0);

            vec2 kV = offsetsForCoordAtDistanceOnSlope(sineDistortion + noiseDistortion, perpendicularSlope);
            if (deg <= 0.0 || deg >= 180.0) {
                kV *= -1.0;
            }

            return kV;
        }

        void mainImage( out vec4 mainImage, in vec2 fragCoord )
        {
            vec2 uv = fragCoord.xy / uResolution.xy;

            float rotation = mod(uRotation + 270.0, 360.0);
			vec2 distortPosition = uDistortPosition;
			distortPosition.y = 1.0 - distortPosition.y;

            vec2 offset = waveOffset(fragCoord, uSineDistortSpread, uSineDistortCycleCount, uSineDistortAmplitude, uNoiseDistortVolatility, uNoiseDistortAmplitude, distortPosition, rotation, uSpeed);

            mainImage = textTexture(uv + offset);
        }
`;

    const NOISE3D_SHADER = `

vec3 mod289(vec3 x) {
  return x - floor(x * (1.0 / 289.0)) * 289.0;
}

vec4 mod289(vec4 x) {
  return x - floor(x * (1.0 / 289.0)) * 289.0;
}

vec4 permute(vec4 x) {
  return mod289(((x*34.0)+1.0)*x);
}

vec4 taylorInvSqrt(vec4 r) {
  return 1.79284291400159 - 0.85373472095314 * r;
}

float snoise(vec3 v) {
  const vec2 C = vec2(1.0/6.0, 1.0/3.0);
  const vec4 D = vec4(0.0, 0.5, 1.0, 2.0);

  vec3 i  = floor(v + dot(v, C.yyy));
  vec3 x0 = v - i + dot(i, C.xxx);

  vec3 g = step(x0.yzx, x0.xyz);
  vec3 l = 1.0 - g;
  vec3 i1 = min(g.xyz, l.zxy);
  vec3 i2 = max(g.xyz, l.zxy);

  vec3 x1 = x0 - i1 + C.xxx;
  vec3 x2 = x0 - i2 + C.yyy;
  vec3 x3 = x0 - D.yyy;

  i = mod289(i);
  vec4 p = permute(
      permute(
        permute(i.z + vec4(0.0, i1.z, i2.z, 1.0))
      + i.y + vec4(0.0, i1.y, i2.y, 1.0))
    + i.x + vec4(0.0, i1.x, i2.x, 1.0)
  );

  float n_ = 0.142857142857;
  vec3 ns = n_ * D.wyz - D.xzx;

  vec4 j = p - 49.0 * floor(p * ns.z * ns.z);

  vec4 x_ = floor(j * ns.z);
  vec4 y_ = floor(j - 7.0 * x_);

  vec4 x = x_ * ns.x + ns.y;
  vec4 y = y_ * ns.x + ns.y;
  vec4 h = 1.0 - abs(x) - abs(y);

  vec4 b0 = vec4(x.xy, y.xy);
  vec4 b1 = vec4(x.zw, y.zw);

  vec4 s0 = floor(b0)*2.0 + 1.0;
  vec4 s1 = floor(b1)*2.0 + 1.0;
  vec4 sh = -step(h, vec4(0.0));

  vec4 a0 = b0.xzyw + s0.xzyw*sh.xxyy;
  vec4 a1 = b1.xzyw + s1.xzyw*sh.zzww;

  vec3 p0 = vec3(a0.xy, h.x);
  vec3 p1 = vec3(a0.zw, h.y);
  vec3 p2 = vec3(a1.xy, h.z);
  vec3 p3 = vec3(a1.zw, h.w);

  vec4 norm = taylorInvSqrt(vec4(
    dot(p0,p0), dot(p1,p1),
    dot(p2,p2), dot(p3,p3)
  ));

  p0 *= norm.x;
  p1 *= norm.y;
  p2 *= norm.z;
  p3 *= norm.w;

  vec4 m = max(0.6 - vec4(
    dot(x0,x0), dot(x1,x1),
    dot(x2,x2), dot(x3,x3)
  ), 0.0);

  m = m * m;

  return 42.0 * dot(m*m, vec4(
    dot(p0,x0), dot(p1,x1),
    dot(p2,x2), dot(p3,x3)
  ));
}

`;

    const LIQUID_SHADER = `

    void mainImage( out vec4 mainImage, in vec2 fragCoord )
    {
        vec2 uv = fragCoord.xy / uResolution.xy;

        float z = uSeed + uGlobalTime * uSpeed;

        uv += snoise(vec3(uv, z)) * uVolatility;

        mainImage = textTexture(uv);
    }

`;

    const CHANNEL_SPLIT_SHADER = `
highp vec4 normalBlend(highp vec4 topColor, highp vec4 baseColor) {
      highp vec4 blend = vec4(0.0);

      // HACK
      // Cant divide by 0 (see the 'else' alpha) and after a lot of attempts
      // this simply seems like the only solution Im going to be able to come up with to get alpha back.
      if (baseColor.a == 1.0) {
        baseColor.a = 0.9999999;
      };

      if (topColor.a >= 1.0) {
        blend.a = topColor.a;
        blend.r = topColor.r;
        blend.g = topColor.g;
        blend.b = topColor.b;
      } else if (topColor.a == 0.0) {
        blend.a = baseColor.a;
        blend.r = baseColor.r;
        blend.g = baseColor.g;
        blend.b = baseColor.b;
      } else {
        blend.a = 1.0 - (1.0 - topColor.a) * (1.0 - baseColor.a); 
        blend.r = (topColor.r * topColor.a / blend.a) + (baseColor.r * baseColor.a * (1.0 - topColor.a) / blend.a);
        blend.g = (topColor.g * topColor.a / blend.a) + (baseColor.g * baseColor.a * (1.0 - topColor.a) / blend.a);
        blend.b = (topColor.b * topColor.a / blend.a) + (baseColor.b * baseColor.a * (1.0 - topColor.a) / blend.a);
      }

      return blend;
    }
const int MAX_STEPS = 200;


float toFixedTwo(float f) {
    return float(int(f * 100.0)) / 100.0;
}

vec2 motionBlurOffsets(vec2 fragCoord, float deg, float spread) {


	vec2 centerUv = vec2(0.5);
	vec2 centerCoord = uResolution.xy * centerUv;

	deg = toFixedTwo(deg);
	float slope = normalizedSlope(slopeForDegrees(deg), uResolution.xy);


	vec2 k = offsetsForCoordAtDistanceOnSlope(spread, slope);
	if (deg <= 90.0 || deg >= 270.0) {
		k *= -1.0;
	}

	return k;
}

float noiseWithWidthAtUv(float width, vec2 uv) {
	float noiseModifier = 1.0;
	if (uAnimateNoise > 0.0) {
		noiseModifier = sin(uGlobalTime);
	}

	vec2 noiseRowCol = floor((uv * uResolution.xy) / width);
	vec2 noiseFragCoord = ((noiseRowCol * width) + (width / 2.0));
	vec2 noiseUv = noiseFragCoord / uResolution.xy;

	return random(noiseUv * noiseModifier) * 0.125;
}

vec4 motionBlur(vec2 uv, vec2 blurOffset, float maxOffset) {
	float noiseWidth = 3.0;
	float randNoise = noiseWithWidthAtUv(noiseWidth, uv);

	vec4 result = textTexture(uv);

	float maxStepsReached = 0.0;

	for (int i = 1; i <= MAX_STEPS; i += 2) {
		if (abs(float(i)) > maxOffset) { break; }
		maxStepsReached += 1.0;

		vec2 offset = (blurOffset / 2.0) - (blurOffset * (float(i) / maxOffset));
		vec4 stepSample = textTexture(uv + (offset / uResolution.xy));

		result += stepSample;
	}

	if (maxOffset >= 1.0) {
		result /= maxStepsReached;
		result.a -= (randNoise * (1.0 - result.a)); 
	}


	return result;
}


void mainImage( out vec4 mainImage, in vec2 fragCoord ) {

    vec2 uv = fragCoord.xy / uResolution.xy;

	float offset = min(float(MAX_STEPS), uResolution.y * uOffset);

	float slope = normalizedSlope(slopeForDegrees(uRotation), uResolution);

	float adjustedOffset = offset;

	vec2 blurOffset = motionBlurOffsets(fragCoord, uRotation, adjustedOffset);

	vec2 rUv = uv;
	vec2 gUv = uv;
	vec2 bUv = uv;

	vec2 k = offsetsForCoordAtDistanceOnSlope(offset, slope) / uResolution;

	if (uRotation <= 90.0 || uRotation >= 270.0) {
		rUv += k;
		bUv -= k;
	}
	else {
		rUv -= k;
		bUv += k;
	}

	vec4 resultR = vec4(0.0);
	vec4 resultG = vec4(0.0);
	vec4 resultB = vec4(0.0);

	if (uApplyBlur > 0.0) {
		resultR = motionBlur(rUv, blurOffset, adjustedOffset);
		resultG = motionBlur(gUv, blurOffset, adjustedOffset);
		resultB = motionBlur(bUv, blurOffset, adjustedOffset);
	} else {
		resultR = textTexture(rUv);
		resultG = textTexture(gUv);
		resultB = textTexture(bUv);
	}
	float a = resultR.a + resultG.a + resultB.a;
	resultR = normalBlend(resultR, uBlendColor);
	resultG = normalBlend(resultG, uBlendColor);
	resultB = normalBlend(resultB, uBlendColor);

	mainImage = vec4(resultR.r, resultG.g, resultB.b, a);

}
`;

    const FLIES_SHADER = `

vec2 random2(vec2 p) {
    return fract(sin(vec2(
        dot(p, vec2(127.1, 311.7)),
        dot(p, vec2(269.5, 183.3))
    )) * 43758.5453);
}

float isParticle(out vec3 particleColor, vec2 fragCoord) {

    float pointCellWidth = floor(max(0.0, min(1.0, uPointCellWidth) * uResolution.y));

    if (pointCellWidth <= 0.0) return 0.0;

    float pointRadius = uPointRadius * 0.8;
    pointRadius = min(pointRadius * pointCellWidth, pointCellWidth);

    float pointRadiusOfCell = pointRadius / pointCellWidth;

    vec2 uv = fragCoord.xy / uResolution.xy;

    vec2 totalCellCount = uResolution.xy / pointCellWidth;
    vec2 cellUv = uv * totalCellCount;

    vec2 iUv = floor(cellUv);
    vec2 fUv = fract(cellUv);

    float minDist = 1.0;
    float maxWeight = 0.0;

    vec4 baseSample = textTexture(cellUv / totalCellCount);
    particleColor = baseSample.rgb;

    for (int y = -1; y <= 1; y++) {
        for (int x = -1; x <= 1; x++) {

            vec2 neighbor = vec2(float(x), float(y));

            vec2 point = random2(iUv + neighbor);

            vec2 cellRowCol = floor(fragCoord / pointCellWidth) + neighbor;
            vec2 cellFragCoord = (cellRowCol * pointCellWidth) + (pointCellWidth * 0.5);

            vec4 cellSample = textTexture(cellFragCoord / uResolution.xy);
            float cellWeight = cellSample.a;

            if (cellWeight < 1.0) continue;

            maxWeight = max(maxWeight, cellWeight);
            if (cellWeight == maxWeight) {
                particleColor = cellSample.rgb;
            }

            float distanceFromDodge = distance(
                uDodgePosition * uResolution.xy,
                cellFragCoord
            ) / uResolution.y;

            distanceFromDodge = 1.0 - smoothstep(0.0, uDodgeSpread, distanceFromDodge);

            cellWeight = step(cellWeight, random(cellRowCol)) + (distanceFromDodge * uDodge);

            point = 0.5 + 0.75 * sin(
                (uGlobalTime * uSpeed) + 6.2831 * point
            );

            vec2 diff = neighbor + point - fUv;

            float dist = length(diff);
            dist += cellWeight;

            minDist = min(minDist, dist);
        }
    }

    float pointEasing = pointRadiusOfCell - (1.0 / pointCellWidth);

    float particle = 1.0 - smoothstep(pointEasing, pointRadiusOfCell, minDist);

    return particle;
}

void mainImage(out vec4 mainImage, in vec2 fragCoord) {

    vec3 color = vec3(0.0);

    float particle = isParticle(color, fragCoord);

    mainImage = vec4(color, particle);
}
`;

    const WPKoiEngine = window.WPKoiEngine || {
        instances: new Set(),
        running: false,

        scrollY: window.pageYOffset,
        lastScrollY: window.pageYOffset,
        scrollDelta: 0,

        mouseSpeed: 0,
        lastMouse: {
            x: 0,
            y: 0
        },

        _loop: null,

        add(instance) {
            this.instances.add(instance);
            this.start();
        },

        start() {
            if (this.running) return;
            this.running = true;

            if (!this._loop) {
                this._loop = this.loop.bind(this);
            }

            if (!this._eventsBound) {
                this._eventsBound = true;

                window.addEventListener('scroll', () => {
					this.scrollY = window.pageYOffset;
				}, { passive: true });

                window.addEventListener('mousemove', (e) => {
                    const dx = e.clientX - this.lastMouse.x;
                    const dy = e.clientY - this.lastMouse.y;

                    const dt = 1 / 60;

                    this.mouseSpeed = Math.sqrt(dx * dx + dy * dy) / dt;

                    this.mouseSpeed = MathUtils.lerp(
                        this.smoothedMouse || 0,
                        this.mouseSpeed,
                        0.15
                    );

                    this.lastMouse.x = e.clientX;
                    this.lastMouse.y = e.clientY;
                });
            }

            requestAnimationFrame(this._loop);
        },

        loop() {
            if (!this.running) return;

            if (this.instances.size === 0) {
                this.running = false;
                return;
            }

            const rawDelta = Math.abs(this.scrollY - this.lastScrollY);

            this.scrollDelta = Math.max(rawDelta, this.scrollDelta * 0.9);

            if (this.scrollDelta < 0.01) {
                this.scrollDelta = 0;
            }
            this.lastScrollY = this.scrollY;

            const isScrolling = this.scrollDelta > 0.5;

			for (const instance of this.instances) {

				if (isScrolling) {
					instance.render(this, true);
				} else {
					instance.render(this, false);
				}

			}

            this.mouseSpeed *= 0.9;

            requestAnimationFrame(this._loop);
        }
    };
    window.WPKoiEngine = WPKoiEngine;

    const MathUtils = {
        lineEq: (y2, y1, x2, x1, currentVal) => {
            if (x2 === x1) return y1;
            const m = (y2 - y1) / (x2 - x1);
            const b = y1 - m * x1;
            return m * currentVal + b;
        },
        lerp: (a, b, n) => (1 - n) * a + n * b
    };

    class Renderer {
        constructor(options, material, webglRender) {
            this.options = options;
            this.material = material;
            this.webglRender = webglRender;

            for (let i = 0; i < this.options.uniforms.length; ++i) {
                const u = this.options.uniforms[i].uniform;

                if (!this.material.uniforms[u]) {
                    console.warn('Missing uniform:', u);
                    continue;
                }

                this.material.uniforms[u].value = this.options.uniforms[i].value;
            }

            for (let i = 0; i < this.options.animatable.length; ++i) {
                const prop = this.options.animatable[i].prop;

                this[prop] = this.options.animatable[i].from;

                if (!this.material.uniforms[prop]) {
                    console.warn('Missing animatable uniform:', prop);
                    continue;
                }

                this.material.uniforms[prop].value = this[prop];
            }

            this.maxScrollSpeed = 80;

            this.time = 0;

            const intensityLevel = Math.min(Math.max(this.options.intensity ?? 5, 0.1), 10);
            this.intensityMultiplier = 0.2 + ((intensityLevel - 1) / 9) * (3.0 - 0.2);
        }

        render(engine, isScrolling = false) {
            const ease = this.options.easeFactor || 0.1;

            if (this.options.effecttrigger === 'onscroll') {
                const scrolled = engine.scrollDelta;

                this.smoothedScroll = MathUtils.lerp(
                    this.smoothedScroll || 0,
                    scrolled,
                    ease
                );

                const input = this.smoothedScroll * this.intensityMultiplier;

                for (let i = 0; i < this.options.animatable.length; ++i) {
                    const anim = this.options.animatable[i];
                    const target = Math.min(
                        MathUtils.lineEq(anim.to, anim.from, this.maxScrollSpeed, 0, input),
                        anim.to
                    );

                    this[anim.prop] = MathUtils.lerp(
                        this[anim.prop],
                        target,
                        ease
                    );
                    if (!this.material.uniforms[anim.prop]) continue;
                    this.material.uniforms[anim.prop].value = this[anim.prop];

                }

            } else if (this.options.effecttrigger === 'mousemove') {

                const speed = Math.min(engine.mouseSpeed, 5) * this.intensityMultiplier;

                const input = speed < 0.01 ? 0 : speed;

                for (let i = 0; i < this.options.animatable.length; ++i) {
                    const anim = this.options.animatable[i];
                    const target = MathUtils.lineEq(anim.to, anim.from, 50, 0, input);

                    this[anim.prop] = MathUtils.lerp(
                        this[anim.prop],
                        target,
                        ease
                    );
                    if (!this.material.uniforms[anim.prop]) continue;
                    this.material.uniforms[anim.prop].value = this[anim.prop];
                }

            } else {
				
                const speedFactor = isScrolling ? 0.3 : 1.0;

				this.time += 0.01 * speedFactor;

				if (!isScrolling && Math.abs(Math.sin(this.time)) < 0.001) return;

                for (let i = 0; i < this.options.animatable.length; ++i) {
                    const anim = this.options.animatable[i];
                    const range = anim.to - anim.from;
                    const easedSin = Math.pow(Math.sin(this.time) * 0.5 + 0.5, 1.5);
                    const value = anim.from + easedSin * range * 0.18 * this.intensityMultiplier;

                    this[anim.prop] = MathUtils.lerp(
                        this[anim.prop],
                        value,
                        ease
                    );
                    if (!this.material.uniforms[anim.prop]) continue;
                    this.material.uniforms[anim.prop].value = this[anim.prop];
                }
            }

            if (this.webglRender) {
                this.webglRender(engine);
            }
        }
    }

    function getConfig(style, data) {
        if (!style) return null;

        const base = {
            effecttrigger: data.trigger || 'onscroll',
            intensity: data.intensity || 5
        };

        switch (style) {

            case '1':
                return {
                    ...base,
                    type: "LiquidDistortMaterial", uniforms: [{
                        uniform: "uSpeed",
                        value: 0.6

                    }, {
                        uniform: "uVolatility",
                        value: 0
                    }, {
                        uniform: "uSeed",
                        value: 0.4
                    }], animatable: [{
                        prop: "uVolatility",
                        from: 0,
                        to: 0.4
                    }], easeFactor: 0.05
                };

            case '2':
                return {
                    ...base,
                    type: "LiquidDistortMaterial", uniforms: [{
                        uniform: "uSpeed",
                        value: 0.9
                    }, {
                        uniform: "uVolatility",
                        value: 0
                    }, {
                        uniform: "uSeed",
                        value: 0.1
                    }], animatable: [{
                        prop: "uVolatility",
                        from: 0,
                        to: 2
                    }], easeFactor: 0.1
                };

            case '3':
                return {
                    ...base,
                    type: "RollingDistortMaterial", uniforms: [{
                        uniform: "uSineDistortSpread",
                        value: 0.354
                    }, {
                        uniform: "uSineDistortCycleCount",
                        value: 5
                    }, {
                        uniform: "uSineDistortAmplitude",
                        value: 0
                    }, {
                        uniform: "uNoiseDistortVolatility",
                        value: 0
                    }, {
                        uniform: "uNoiseDistortAmplitude",
                        value: 0.168
                    }, {
                        uniform: "uDistortPosition",
                        value: [0.38, 0.68]
                    }, {
                        uniform: "uRotation",
                        value: 48
                    }, {
                        uniform: "uSpeed",
                        value: 0.421
                    }], animatable: [{
                        prop: "uSineDistortAmplitude",
                        from: 0,
                        to: 0.5
                    }], easeFactor: 0.15
                };

            case '4':
                return {
                    ...base,
                    type: "RollingDistortMaterial", uniforms: [{
                        uniform: "uSineDistortSpread",
                        value: 0.54
                    }, {
                        uniform: "uSineDistortCycleCount",
                        value: 2
                    }, {
                        uniform: "uSineDistortAmplitude",
                        value: 0
                    }, {
                        uniform: "uNoiseDistortVolatility",
                        value: 0
                    }, {
                        uniform: "uNoiseDistortAmplitude",
                        value: 0.15
                    }, {
                        uniform: "uDistortPosition",
                        value: [0.18, 0.98]
                    }, {
                        uniform: "uRotation",
                        value: 89.95
                    }, {
                        uniform: "uSpeed",
                        value: 0.3
                    }], animatable: [{
                        prop: "uSineDistortAmplitude",
                        from: 0,
                        to: 0.2
                    }], easeFactor: 0.05
                };

            case '5':
                return {
                    ...base,
                    type: "RollingDistortMaterial", uniforms: [{
                        uniform: "uSineDistortSpread",
                        value: 0.44
                    }, {
                        uniform: "uSineDistortCycleCount",
                        value: 5
                    }, {
                        uniform: "uSineDistortAmplitude",
                        value: 0
                    }, {
                        uniform: "uNoiseDistortVolatility",
                        value: 0
                    }, {
                        uniform: "uNoiseDistortAmplitude",
                        value: 0.85
                    }, {
                        uniform: "uDistortPosition",
                        value: [0, 0]
                    }, {
                        uniform: "uRotation",
                        value: 0.01
                    }, {
                        uniform: "uSpeed",
                        value: .1
                    }], animatable: [{
                        prop: "uSineDistortAmplitude",
                        from: 0,
                        to: 0.2
                    }], easeFactor: 0.35
                };

            case '6':
                return {
                    ...base,
                    type: "RollingDistortMaterial", uniforms: [{
                        uniform: "uSineDistortSpread",
                        value: 0.74
                    }, {
                        uniform: "uSineDistortCycleCount",
                        value: 7
                    }, {
                        uniform: "uSineDistortAmplitude",
                        value: 0
                    }, {
                        uniform: "uNoiseDistortVolatility",
                        value: 0
                    }, {
                        uniform: "uNoiseDistortAmplitude",
                        value: 0.15
                    }, {
                        uniform: "uDistortPosition",
                        value: [0.1, 0.5]
                    }, {
                        uniform: "uRotation",
                        value: 20
                    }, {
                        uniform: "uSpeed",
                        value: 0.7
                    }], animatable: [{
                        prop: "uSineDistortAmplitude",
                        from: 0,
                        to: 0.2
                    }], easeFactor: 0.1
                };

            case '7':
                return {
                    ...base,
                    type: "RollingDistortMaterial", uniforms: [{
                        uniform: "uSineDistortSpread",
                        value: 0.084
                    }, {
                        uniform: "uSineDistortCycleCount",
                        value: 2.2
                    }, {
                        uniform: "uSineDistortAmplitude",
                        value: 0
                    }, {
                        uniform: "uNoiseDistortVolatility",
                        value: 0
                    }, {
                        uniform: "uNoiseDistortAmplitude",
                        value: 0
                    }, {
                        uniform: "uDistortPosition",
                        value: [0.35, 0.37]
                    }, {
                        uniform: "uRotation",
                        value: 179.9
                    }, {
                        uniform: "uSpeed",
                        value: 0.94
                    }], animatable: [{
                        prop: "uSineDistortAmplitude",
                        from: 0,
                        to: 0.13
                    }], easeFactor: 0.15
                };

            case '8':
                return {
                    ...base,
                    type: "RollingDistortMaterial", uniforms: [{
                        uniform: "uSineDistortSpread",
                        value: 0
                    }, {
                        uniform: "uSineDistortCycleCount",
                        value: 0
                    }, {
                        uniform: "uSineDistortAmplitude",
                        value: 0
                    }, {
                        uniform: "uNoiseDistortVolatility",
                        value: 0.01
                    }, {
                        uniform: "uNoiseDistortAmplitude",
                        value: 0.126
                    }, {
                        uniform: "uDistortPosition",
                        value: [0.3, 0.3]
                    }, {
                        uniform: "uRotation",
                        value: 179.9
                    }, {
                        uniform: "uSpeed",
                        value: 0.13
                    }], animatable: [{
                        prop: "uNoiseDistortVolatility",
                        from: 0.01,
                        to: 200
                    }, {
                        prop: "uRotation",
                        from: 179.9,
                        to: 270
                    }], easeFactor: 0.25
                };

            case '9':
                return {
                    ...base,
                    type: "RollingDistortMaterial", uniforms: [{
                        uniform: "uSineDistortSpread",
                        value: 0.1
                    }, {
                        uniform: "uSineDistortCycleCount",
                        value: 0
                    }, {
                        uniform: "uSineDistortAmplitude",
                        value: 0
                    }, {
                        uniform: "uNoiseDistortVolatility",
                        value: 0
                    }, {
                        uniform: "uNoiseDistortAmplitude",
                        value: 0
                    }, {
                        uniform: "uDistortPosition",
                        value: [0, 0]
                    }, {
                        uniform: "uRotation",
                        value: 89.95
                    }, {
                        uniform: "uSpeed",
                        value: 2
                    }], animatable: [{
                        prop: "uSineDistortAmplitude",
                        from: 0,
                        to: 0.3
                    }, {
                        prop: "uSineDistortCycleCount",
                        from: 0,
                        to: 1.5
                    }], easeFactor: 0.35
                };

            case '10':
                return {
                    ...base,
                    type: "RollingDistortMaterial", uniforms: [{
                        uniform: "uSineDistortSpread",
                        value: 0.28
                    }, {
                        uniform: "uSineDistortCycleCount",
                        value: 7
                    }, {
                        uniform: "uSineDistortAmplitude",
                        value: 0
                    }, {
                        uniform: "uNoiseDistortVolatility",
                        value: 0
                    }, {
                        uniform: "uNoiseDistortAmplitude",
                        value: 0
                    }, {
                        uniform: "uDistortPosition",
                        value: [0, 0]
                    }, {
                        uniform: "uRotation",
                        value: 89.95
                    }, {
                        uniform: "uSpeed",
                        value: 0.3
                    }], animatable: [{
                        prop: "uSineDistortAmplitude",
                        from: 0,
                        to: 0.2
                    }], easeFactor: 0.65
                };

            case '11':
                return {
                    ...base,
                    type: "ChannelSplitMaterial",
                        animatable: [{
                            prop: "uOffset",
                            from: 0.02,
                            to: 0.35
                        }, {
                            prop: "uRotation",
                            from: 140,
                            to: 40
                        }],
                        easeFactor: 0.05
                };

            case '12':
                return {
                    ...base,
                    type: "FliesMaterial", easeFactor: 0.08
                };
        }
    }

    function initDistortedHeading($scope) {

        const el = $scope.find('[data-wpkoi-distort]')[0];
        if (!el) return;

        if (el._wpkoiInstance && el.querySelector('canvas')) {
            return;
        }

        let data = {};

        try {
            data = JSON.parse(el.dataset.wpkoiDistort || '{}');
        } catch (e) {
            return;
        }

        if (!data.style) return;

        const config = getConfig(data.style, data);
        if (!config) return;

        const textContent = el.textContent.trim();
        if (!textContent) return;

        el.innerHTML = '';

        const style = {
            family: data.font_family || 'Roboto',
            weight: data.font_weight || 500,
            size: data.size || 100,
            leading: data.line_height || 1.8,
            paddingTop: data.padding?.[0] || 0,
            paddingRight: data.padding?.[1] || 0,
            paddingBottom: data.padding?.[2] || 0,
            paddingLeft: data.padding?.[3] || 0,
            fill: data.color || '#000'
        };

        let shader;
        let shaderType;

        switch (config.type) {
            case 'LiquidDistortMaterial':
                shader = LIQUID_SHADER;
                shaderType = 'liquid';
                break;

            case 'RollingDistortMaterial':
                shader = ROLLING_SHADER;
                shaderType = 'rolling';
                break;

            case 'FliesMaterial':
                shader = FLIES_SHADER;
                shaderType = 'flies';
                break;

            case 'ChannelSplitMaterial':
                shader = CHANNEL_SPLIT_SHADER;
                shaderType = 'channelsplit';
                break;

            default:
                shader = ROLLING_SHADER;
                shaderType = 'rolling';
        }

        if (el._wpkoiInstance && typeof el._wpkoiInstance.destroy === 'function') {
            el._wpkoiInstance.destroy();
        }

        el._wpkoiInstance = new SimpleThreeText(
            el,
            textContent,
            style,
            shader, {
                ...config,
                type: shaderType
            }
        );
    }

    if (window.elementorFrontend) {

        let wpkoiInitDone = false;

        $(window).on('elementor/frontend/init', function() {

            if (wpkoiInitDone) return;
            wpkoiInitDone = true;

            elementorFrontend.hooks.addAction(
                'frontend/element_ready/wpkoi-distorted-heading.default',
                function($scope) {
                    document.fonts.ready.then(() => {
                        initDistortedHeading($scope);
                    });
                }
            );

        });

    }

    function observeEditor() {

        let scheduled = false;

        const observer = new MutationObserver((mutations) => {

            if (scheduled) return;

            let shouldRun = false;
            let target = null;

            for (const mutation of mutations) {

                const el = mutation.target;

                if (!el) continue;

                if (el.classList?.contains('wpkoi-distorted-main-inner')) {
                    target = el;
                    shouldRun = true;
                    break;
                }

                if (el.closest?.('.wpkoi-distorted-main-inner')) {
                    target = el.closest('.wpkoi-distorted-main-inner');
                    shouldRun = true;
                    break;
                }
            }

            if (!shouldRun || !target) return;

            scheduled = true;

            requestAnimationFrame(() => {

                scheduled = false;

                if (target.querySelector('canvas')) return;

                const $scope = $(target).closest('.elementor-element');

                if (!$scope.length) return;

                const text = target.textContent.trim();

                if (!text) return;

                target.innerHTML = text;

                initDistortedHeading($scope);

            });

        });

        const editorRoot = document.querySelector('.elementor-editor-active') || document.body;

        observer.observe(editorRoot, {
            childList: true,
            subtree: true
        });
    }

    $(window).on('elementor/frontend/init', function() {
        if (elementorFrontend.isEditMode()) {
            observeEditor();
        }
    });

})(window, jQuery);