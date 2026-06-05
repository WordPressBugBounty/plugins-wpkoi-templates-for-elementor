const WPKoiCreateCircleText = async (
    text,
    options = {}
) => {

    if (
        document.fonts &&
        document.fonts.ready
    ) {

        try {

            await Promise.race([
                document.fonts.ready,
                new Promise(resolve =>
                    setTimeout(resolve, 1000)
                )
            ]);

        } catch (e) {}
    }

    const wrapper =
        document.createElement('div');

    wrapper.classList.add(
        'wpkoi-mi-circle-text'
    );

    const radius =
        parseFloat(options.radius || 120);

    const radiusUnit =
        options.radiusUnit || 'px';

    wrapper.style.width =
        `calc(${radius}${radiusUnit} * 2)`;

    wrapper.style.height =
        `calc(${radius}${radiusUnit} * 2)`;

    const startAngle =
        parseFloat(options.startAngle || -90);

    const reverse =
        options.reverse === true;

    const gap =
        parseFloat(options.gap || 0);

    const characters =
        (text || '').split('');

    if (!characters.length) {
        return wrapper;
    }

    let radiusPx = radius;

    if (radiusUnit === 'vw') {

        radiusPx =
            (window.innerWidth * radius) / 100;

    } else if (radiusUnit === 'vh') {

        radiusPx =
            (window.innerHeight * radius) / 100;
    }

    const circumference =
        2 * Math.PI * radiusPx;

    const measure =
        document.createElement('span');

    measure.style.position = 'absolute';
    measure.style.visibility = 'hidden';
    measure.style.whiteSpace = 'pre';
    measure.style.left = '-9999px';
    measure.style.top = '-9999px';
    measure.style.pointerEvents = 'none';

    measure.style.fontFamily =
        options.fontFamily || '';

    measure.style.fontSize =
        `${options.fontSize || ''}${options.fontSizeUnit || 'px'}`;

    measure.style.fontWeight =
        options.fontWeight || '';

    measure.style.letterSpacing =
        `${options.letterSpacing || 0}${options.letterSpacingUnit || 'px'}`;

    measure.style.textTransform =
        options.textTransform || '';

    measure.style.fontStyle =
        options.fontStyle || '';

    measure.style.lineHeight =
        `${options.lineHeight || ''}${options.lineHeightUnit || 'em'}`;

    document.body.appendChild(measure);

    let currentAngle =
        startAngle;

    characters.forEach((character) => {

        const span =
            document.createElement('span');

        span.classList.add(
            'wpkoi-mi-circle-char'
        );

        const safeCharacter =
            character === ' ' ?
            '\u00A0' :
            character;

        span.textContent =
            safeCharacter;

        measure.textContent =
            safeCharacter;

        const charWidth =
            measure.getBoundingClientRect().width;

        const angleStep =
            (
                (charWidth / circumference) * 360 *
                1.05
            ) +
            gap;

        currentAngle +=
            angleStep / 2;

        const finalAngle =
            reverse ?
            -currentAngle :
            currentAngle;

        const radians =
            (finalAngle - 90) *
            (Math.PI / 180);

        const x =
            Math.cos(radians) * radiusPx;

        const y =
            Math.sin(radians) * radiusPx;

        span.style.left =
            `calc(50% + ${x}px)`;

        span.style.top =
            `calc(50% + ${y}px)`;

        span.style.transform =
            `
            translate(-50%, -50%)
            rotate(${finalAngle}deg)
            `;

        wrapper.appendChild(span);

        currentAngle +=
            angleStep / 2;

        currentAngle += gap;
    });

    measure.remove();

    return wrapper;
};

document.addEventListener('DOMContentLoaded', () => {

    const isTouchDevice =
        (
            'ontouchstart' in window ||
            navigator.maxTouchPoints > 0
        );

    let globalMouse = {
        clientX: window.innerWidth / 2,
        clientY: window.innerHeight / 2,
    };

    document.addEventListener(
        'mousemove',
        (event) => {

            globalMouse = {
                clientX: event.clientX,
                clientY: event.clientY,
            };
        }
    );

    const elements = document.querySelectorAll(
        '.wpkoi-interactive-cursor-element'
    );

    elements.forEach((element) => {

        const disableTablet =
            element.dataset.miDisableTablet === 'yes';

        const disableMobile =
            element.dataset.miDisableMobile === 'yes';

        const viewportWidth =
            window.innerWidth;

        /*  Disable tablet */

        if (
            disableTablet &&
            viewportWidth <= 1024 &&
            viewportWidth > 767
        ) {
            return;
        }

        /*  Disable mobile */

        if (
            disableMobile &&
            viewportWidth <= 767
        ) {
            return;
        }

        let box = null;
        let boxRect = null;
        let lastMouseEvent = null;

        let currentX = 0;
        let currentY = 0;

        let targetX = 0;
        let targetY = 0;

        let magneticRAF = null;

        const magnetic =
            element.dataset.miMagnetic === 'yes';

        const parentInteractions = [];

        let parent = element.parentElement;

        while (parent) {

            if (
                parent.classList &&
                parent.classList.contains(
                    'wpkoi-interactive-cursor-element'
                )
            ) {
                parentInteractions.push(parent);
            }

            parent = parent.parentElement;
        }

        const waitForFont = async () => {

            if (!document.fonts) {
                return;
            }

            const fontFamily =
                element.dataset.miFontFamily || 'inherit';

            const fontSize =
                (
                    element.dataset.miFontSize || 16
                ) +
                (
                    element.dataset.miFontSizeUnit || 'px'
                );

            const fontWeight =
                element.dataset.miFontWeight || '400';

            try {

                await document.fonts.load(
                    `${fontWeight} ${fontSize} "${fontFamily}"`
                );

            } catch (e) {

                console.warn(
                    'Font load failed:',
                    fontFamily
                );
            }
        };

        const createBox = async (event) => {

            await waitForFont();

            if (
                !document.body.contains(element) ||
                box
            ) {
                return;
            }

            const hideCursor =
                element.dataset.miHideCursor === 'yes';

            const contentMode =
                element.dataset.miContentMode || 'normal';

            const circleRadius =
                parseFloat(
                    element.dataset.miCircleRadius || 120
                );

            const circleReverse =
                element.dataset.miCircleReverse === 'yes';

            const circleCenterMode =
                element.dataset.miCircleCenterMode || 'none';

            if (hideCursor) {

                element.classList.add(
                    'wpkoi-mi-hide-cursor'
                );
            }

            if (
                !event.target ||
                !event.target.closest
            ) {
                return;
            }

            if (
                event.target.closest(
                    '.wpkoi-interactive-cursor-element'
                ) !== element
            ) {
                return;
            }

            if (box) {
                return;
            }

            /* Parent interactions hide */

            parentInteractions.forEach((parent) => {

                const parentBox =
                    parent._wpkoiMouseBox;

                if (parentBox) {
                    parentBox.classList.add(
                        'wpkoi-mi-hidden'
                    );
                }
            });

            /* Create box */

            box = document.createElement('div');

            element._wpkoiMouseBox = box;

            box.classList.add('wpkoi-mouse-box');

            /* Animation wrapper */

            const animationWrapper =
                document.createElement('div');

            animationWrapper.classList.add(
                'wpkoi-mouse-anim'
            );

            /* FX wrapper */

            const fxWrapper =
                document.createElement('div');

            fxWrapper.classList.add(
                'wpkoi-mouse-fx'
            );

            /* Pulse */

            const pulse =
                element.dataset.miPulse === 'yes';

            if (pulse) {

                fxWrapper.classList.add(
                    'wpkoi-mi-effect-pulse'
                );

                fxWrapper.style.setProperty(
                    '--mi-pulse-min-scale',
                    element.dataset.miPulseMinScale || 1
                );

                fxWrapper.style.setProperty(
                    '--mi-pulse-max-scale',
                    element.dataset.miPulseMaxScale || 1.05
                );

                fxWrapper.style.setProperty(
                    '--mi-pulse-speed',
                    (element.dataset.miPulseSpeed || 2) + 's'
                );
            }

            /* Rotation */

            const rotation =
                element.dataset.miRotation === 'yes';

            if (rotation) {

                fxWrapper.classList.add(
                    'wpkoi-mi-effect-rotation'
                );

                fxWrapper.style.setProperty(
                    '--mi-rotation-speed',
                    (element.dataset.miRotationSpeed || 15) + 's'
                );

                if (
                    element.dataset.miRotationReverse === 'yes'
                ) {

                    fxWrapper.classList.add(
                        'wpkoi-mi-rotation-reverse'
                    );
                }
            }

            /* Blend mode */

            const blendMode =
                element.dataset.miBlendMode === 'yes';

            if (blendMode) {

                box.style.mixBlendMode =
                    element.dataset.miBlendModeType ||
                    'difference';
            }

            const animation =
                element.dataset.miAnimation || 'default';

            animationWrapper.classList.add(
                'wpkoi-mi-animation-' + animation
            );

            const duration =
                element.dataset.miDuration || 300;

            animationWrapper.style.setProperty(
                '--mi-duration',
                duration + 'ms'
            );

            /* Inner wrapper */

            const inner =
                document.createElement('div');

            inner.classList.add(
                'wpkoi-mouse-box-inner'
            );

            /* Icon */

            const iconEnabled =
                element.dataset.miIconEnabled === 'yes';

            const iconHtml =
                element.dataset.miIconHtml || '';

            let iconWrapper = null;

            if (
                iconEnabled &&
                iconHtml !== ''
            ) {

                iconWrapper =
                    document.createElement('div');

                iconWrapper.classList.add(
                    'wpkoi-mi-icon-wrapper'
                );

                const icon =
                    document.createElement('div');

                icon.classList.add(
                    'wpkoi-mi-icon'
                );

                icon.innerHTML = iconHtml;

                iconWrapper.appendChild(icon);
            }

            /* Content wrapper */

            const contentWrapper =
                document.createElement('div');

            contentWrapper.classList.add(
                'wpkoi-mouse-content-wrapper'
            );

            const iconPosition =
                element.dataset.miIconPosition || 'top';

            /* Main content */

            const mainEnabled =
                element.dataset.miMainEnabled === 'yes';

            const mainContent =
                element.dataset.miMainContent || '';

            if (
                mainEnabled &&
                mainContent !== ''
            ) {

                if (
                    contentMode === 'circle'
                ) {

                    const circle =
                        await WPKoiCreateCircleText(
                            mainContent, {
                                radius: circleRadius,
                                radiusUnit: element.dataset.miCircleRadiusUnit,

                                startAngle: element.dataset.miCircleStartAngle,

                                reverse: circleReverse,

                                gap: element.dataset.miCircleGap,

                                fontFamily: element.dataset.miFontFamily,

                                fontSize: element.dataset.miFontSize,

                                fontSizeUnit: element.dataset.miFontSizeUnit,

                                fontWeight: element.dataset.miFontWeight,

                                letterSpacing: element.dataset.miLetterSpacing,

                                letterSpacingUnit: element.dataset.miLetterSpacingUnit,

                                textTransform: element.dataset.miTextTransform,

                                fontStyle: element.dataset.miFontStyle,

                                lineHeight: element.dataset.miLineHeight,

                                lineHeightUnit: element.dataset.miLineHeightUnit,
                            }
                        );
                    if (
                        !box ||
                        !document.body.contains(element)
                    ) {
                        return;
                    }

                    contentWrapper.appendChild(
                        circle
                    );

                } else {

                    const main =
                        document.createElement('div');

                    main.classList.add(
                        'wpkoi-mi-main-content'
                    );

                    main.textContent = mainContent;

                    contentWrapper.appendChild(
                        main
                    );
                }
            }

            /* Sub content */

            const subEnabled =
                element.dataset.miSubEnabled === 'yes';

            const subContent =
                element.dataset.miSubContent || '';

            if (
                subEnabled &&
                subContent !== '' &&
                contentMode !== 'circle'
            ) {

                const sub =
                    document.createElement('div');

                sub.classList.add(
                    'wpkoi-mi-sub-content'
                );

                sub.textContent = subContent;

                contentWrapper.appendChild(sub);
            }

            if (
                contentMode === 'circle'
            ) {

                const center =
                    document.createElement('div');

                center.classList.add(
                    'wpkoi-mi-circle-center'
                );

                if (
                    circleCenterMode === 'icon' &&
                    iconWrapper
                ) {

                    center.appendChild(
                        iconWrapper
                    );

                } else if (
                    circleCenterMode === 'sub'
                ) {

                    const subEnabled =
                        element.dataset.miSubEnabled === 'yes';

                    const subContent =
                        element.dataset.miSubContent || '';

                    if (
                        subEnabled &&
                        subContent !== ''
                    ) {

                        const sub =
                            document.createElement('div');

                        sub.classList.add(
                            'wpkoi-mi-sub-content'
                        );

                        sub.textContent =
                            subContent;

                        center.appendChild(
                            sub
                        );
                    }
                }

                inner.appendChild(center);
            }

            /* Append content wrapper */

            if (
                contentMode !== 'circle'
            ) {

                if (
                    iconEnabled &&
                    iconHtml !== ''
                ) {

                    if (
                        iconPosition === 'top'
                    ) {

                        inner.appendChild(iconWrapper);

                        if (
                            contentWrapper.children.length > 0
                        ) {
                            inner.appendChild(contentWrapper);
                        }
                    } else if (
                        iconPosition === 'bottom'
                    ) {

                        if (
                            contentWrapper.children.length > 0
                        ) {
                            inner.appendChild(contentWrapper);
                        }

                        inner.appendChild(iconWrapper);
                    } else if (
                        iconPosition === 'left' ||
                        iconPosition === 'right'
                    ) {

                        inner.classList.add(
                            'wpkoi-mi-layout-row'
                        );

                        if (
                            iconPosition === 'left'
                        ) {

                            inner.appendChild(iconWrapper);

                            if (
                                contentWrapper.children.length > 0
                            ) {
                                inner.appendChild(contentWrapper);
                            }
                        } else {

                            if (
                                contentWrapper.children.length > 0
                            ) {
                                inner.appendChild(contentWrapper);
                            }

                            inner.appendChild(iconWrapper);
                        }
                    }
                } else {

                    if (
                        contentWrapper.children.length > 0
                    ) {
                        inner.appendChild(contentWrapper);
                    }
                }

            } else {

                if (
                    contentWrapper.children.length > 0
                ) {
                    inner.appendChild(
                        contentWrapper
                    );
                }
            }

            /* Append inner */

            animationWrapper.appendChild(
                inner
            );

            fxWrapper.appendChild(
                animationWrapper
            );

            box.appendChild(
                fxWrapper
            );

            /* Append box */

            element.appendChild(box);

            requestAnimationFrame(() => {

				if (
					!box ||
					!document.body.contains(box)
				) {
					return;
				}

				boxRect =
					box.getBoundingClientRect();

				updatePosition(globalMouse);
			});

            if (magnetic) {

                const magneticSpeed =
                    parseFloat(
                        element.dataset.miMagneticSpeed || 0.12
                    );

                currentX = targetX;
                currentY = targetY;

                const animateMagnetic = () => {

                    if (
                        !box ||
                        !document.body.contains(box)
                    ) {
                        cancelAnimationFrame(magneticRAF);
                        magneticRAF = null;
                        return;
                    }

                    currentX +=
                        (targetX - currentX) *
                        magneticSpeed;

                    currentY +=
                        (targetY - currentY) *
                        magneticSpeed;

                    box.style.left =
                        currentX + 'px';

                    box.style.top =
                        currentY + 'px';

                    magneticRAF =
                        requestAnimationFrame(
                            animateMagnetic
                        );
                };

                animateMagnetic();
            }

            requestAnimationFrame(() => {

                animationWrapper.classList.add(
                    'wpkoi-mi-visible'
                );
            });
        };

        const removeBox = () => {

            if (!box) {
                return;
            }

            const animationWrapper =
                box.querySelector(
                    '.wpkoi-mouse-anim'
                );

            if (animationWrapper) {

                animationWrapper.classList.remove(
                    'wpkoi-mi-visible'
                );
            }

            const currentBox = box;

            const duration =
                parseInt(
                    element.dataset.miDuration || 300,
                    10
                );

            setTimeout(() => {
                if (currentBox) {
                    currentBox.remove();
                }
            }, duration);

            box = null;
            boxRect = null;

            /* Show parent interactions again */

            parentInteractions.forEach((parent) => {

                const parentBox =
                    parent._wpkoiMouseBox;

                if (parentBox) {

                    parentBox.classList.remove(
                        'wpkoi-mi-hidden'
                    );

                    if (lastMouseEvent) {

                        const moveEvent =
                            new MouseEvent(
                                'mousemove',
                                lastMouseEvent
                            );

                        parent.dispatchEvent(
                            moveEvent
                        );
                    }
                }
            });

            element.classList.remove(
                'wpkoi-mi-hide-cursor'
            );

            if (magneticRAF) {

                cancelAnimationFrame(
                    magneticRAF
                );

                magneticRAF = null;
            }

        };

        const updatePosition = (event) => {

            lastMouseEvent = event;

            if (
                !event.target ||
                !event.target.closest
            ) {
                return;
            }

            if (
                event.target &&
                event.target.closest(
                    '.wpkoi-interactive-cursor-element'
                ) !== element
            ) {
                return;
            }

            if (
				!box ||
				!boxRect ||
				!document.body.contains(box)
			) {
				return;
			}

            const position =
                element.dataset.miPosition ||
                'top-center';

            const offset = 0;

            const parentRect =
                element.getBoundingClientRect();

            let x =
                event.clientX - parentRect.left;

            let y =
                event.clientY - parentRect.top;

            const offsetStrength =
                parseFloat(
                    element.dataset.miMagneticOffset || 0.15
                );

            const velocityX =
                event.movementX || 0;

            const velocityY =
                event.movementY || 0;

            x += velocityX * offsetStrength;
            y += velocityY * offsetStrength;

            const rect = boxRect;

            switch (position) {

                case 'top-left':
                    x -= rect.width + offset;
                    y -= rect.height + offset;
                    break;

                case 'top-center':
                    x -= rect.width / 2;
                    y -= rect.height + offset;
                    break;

                case 'top-right':
                    x += offset;
                    y -= rect.height + offset;
                    break;

                case 'center-left':
                    x -= rect.width + offset;
                    y -= rect.height / 2;
                    break;

                case 'center-center':
                    x -= rect.width / 2;
                    y -= rect.height / 2;
                    break;

                case 'center-right':
                    x += offset;
                    y -= rect.height / 2;
                    break;

                case 'bottom-left':
                    x -= rect.width + offset;
                    y += offset;
                    break;

                case 'bottom-center':
                    x -= rect.width / 2;
                    y += offset;
                    break;

                case 'bottom-right':
                default:
                    x += offset;
                    y += offset;
                    break;
            }

            if (magnetic) {

                targetX = x;
                targetY = y;
            } else {

                box.style.left = x + 'px';
                box.style.top = y + 'px';
            }
        };

        if (!isTouchDevice) {

            element.addEventListener(
                'mouseenter',
                createBox
            );

            element.addEventListener(
                'mouseleave',
                removeBox
            );

            element.addEventListener(
                'mousemove',
                updatePosition
            );
        }

        /*  Touch devices */
        else {

            element.addEventListener(
                'touchstart',
                (event) => {

                    const touch =
                        event.touches[0];

                    const fakeEvent = {
                        clientX: touch.clientX,
                        clientY: touch.clientY,
                        target: event.target,
                    };

                    /*
                    |------------------------------------------
                    | Toggle
                    |------------------------------------------
                    */

                    if (box) {

                        removeBox();
                    } else {

                        createBox(fakeEvent);

                        updatePosition(fakeEvent);
                    }
                }, {
                    passive: true
                }
            );
        }
    });

});

!(function($, elementorFrontend) {

    "use strict";

    var WPKoiMouseInteraction = {

        init: function() {

            elementorFrontend.hooks.addAction(
                'frontend/element_ready/widget',
                WPKoiMouseInteraction.elementReady
            );

            elementorFrontend.hooks.addAction(
                'frontend/element_ready/container',
                WPKoiMouseInteraction.elementReady
            );
        },

        elementReady: function($scope) {

            var elementId =
                $scope.data('id');

            var isEdit =
                Boolean(
                    elementorFrontend.isEditMode()
                );

            var settings = {};

            /*
            |------------------------------------------------------------------
            | Editor
            |------------------------------------------------------------------
            */

            if (isEdit) {

                settings =
                    WPKoiMouseInteraction
                    .getEditorSettings(
                        elementId
                    );

                /*  Live Editor Updates */

                const model =
                    WPKoiMouseInteraction
                    .getEditorModel(
                        elementId
                    );

                if (model) {

                    let updateTimer = null;

                    model.attributes.settings.off(
						'change:interactive_cursor_show change:interactive_cursor_editor_preview change:interactive_cursor_main_content_show change:interactive_cursor_main_content change:interactive_cursor_content_mode change:interactive_cursor_circle_radius change:interactive_cursor_circle_gap change:interactive_cursor_circle_start_angle change:interactive_cursor_circle_reverse change:interactive_cursor_circle_center_mode change:interactive_cursor_main_content_alignment change:interactive_cursor_main_content_color change:interactive_cursor_main_content_typography change:interactive_cursor_sub_content_show change:interactive_cursor_sub_content change:interactive_cursor_icon_show change:interactive_cursor_icon change:interactive_cursor_icon_position change:interactive_cursor_disable_tablet change:interactive_cursor_disable_mobile change:interactive_cursor_pulse change:interactive_cursor_pulse_min_scale change:interactive_cursor_pulse_max_scale change:interactive_cursor_pulse_speed change:interactive_cursor_rotation change:interactive_cursor_rotation_speed change:interactive_cursor_rotation_reverse change:interactive_cursor_blend_mode change:interactive_cursor_blend_mode_type',
						model._wpkoiMouseInteractionHandler
					);

                    model._wpkoiMouseInteractionHandler =
                        function() {

                            clearTimeout(updateTimer);

                            updateTimer =
                                setTimeout(
                                    function() {

                                        const newSettings =
                                            model
                                            .attributes
                                            .settings
                                            .attributes;

                                        if (
                                            newSettings
                                            .interactive_cursor_show ===
                                            'yes' &&
                                            newSettings
                                            .interactive_cursor_editor_preview ===
                                            'yes'
                                        ) {

                                            WPKoiMouseInteraction
                                                .createEditorPreview(
                                                    $scope,
                                                    newSettings
                                                );
                                        } else {

                                            $scope.children(
                                                '.wpkoi-mi-editor-preview'
                                            ).remove();
                                        }

                                    },
                                    50
                                );
                        };

                    model.attributes.settings.on(
						'change:interactive_cursor_show change:interactive_cursor_editor_preview change:interactive_cursor_main_content_show change:interactive_cursor_main_content change:interactive_cursor_content_mode change:interactive_cursor_circle_radius change:interactive_cursor_circle_gap change:interactive_cursor_circle_start_angle change:interactive_cursor_circle_reverse change:interactive_cursor_circle_center_mode change:interactive_cursor_main_content_alignment change:interactive_cursor_main_content_color change:interactive_cursor_main_content_typography change:interactive_cursor_sub_content_show change:interactive_cursor_sub_content change:interactive_cursor_icon_show change:interactive_cursor_icon change:interactive_cursor_icon_position change:interactive_cursor_disable_tablet change:interactive_cursor_disable_mobile change:interactive_cursor_pulse change:interactive_cursor_pulse_min_scale change:interactive_cursor_pulse_max_scale change:interactive_cursor_pulse_speed change:interactive_cursor_rotation change:interactive_cursor_rotation_speed change:interactive_cursor_rotation_reverse change:interactive_cursor_blend_mode change:interactive_cursor_blend_mode_type',
						model._wpkoiMouseInteractionHandler
					);
                }
            }

            /*
            |------------------------------------------------------------------
            | Mouse Interaction Enabled?
            |------------------------------------------------------------------
            */

            if (
                settings
                .interactive_cursor_show !==
                'yes'
            ) {
                return;
            }

            if (
                isEdit &&
                settings.interactive_cursor_editor_preview ===
                'yes'
            ) {

                WPKoiMouseInteraction
                    .createEditorPreview(
                        $scope,
                        settings
                    );
            } else {

                $scope.children(
                    '.wpkoi-mi-editor-preview'
                ).remove();
            }

        },

        createEditorPreview: async function(
            $scope,
            settings
        ) {

            /*  Remove old preview */

            $scope.children(
                '.wpkoi-mi-editor-preview'
            ).remove();

            /*  Wrapper */

            const preview =
                jQuery(`
		<div class="
			wpkoi-mi-editor-preview
		">

			<div class="
				wpkoi-mouse-box
			">

				<div class="
					wpkoi-mouse-fx
				">

					<div class="
						wpkoi-mouse-anim
						wpkoi-mi-visible
					">

						<div class="
							wpkoi-mouse-box-inner
						">

						</div>

					</div>

				</div>

			</div>

		</div>
	`);

            /*  Inner */

            const inner =
                preview.find(
                    '.wpkoi-mouse-box-inner'
                );

            /*  Animation / FX Wrappers */

            const fxWrapper =
                preview.find(
                    '.wpkoi-mouse-fx'
                );

            const animationWrapper =
                preview.find(
                    '.wpkoi-mouse-anim'
                );

            /*  Animation */

            const animation =
                settings.interactive_cursor_animation ||
                'default';

            animationWrapper.addClass(
                'wpkoi-mi-animation-' + animation
            );

            animationWrapper.css(
                '--mi-duration',
                (
                    settings.interactive_cursor_duration ||
                    300
                ) + 'ms'
            );

            /*  Pulse */

            if (
                settings.interactive_cursor_pulse ===
                'yes'
            ) {

                fxWrapper.addClass(
                    'wpkoi-mi-effect-pulse'
                );

                fxWrapper.css(
                    '--mi-pulse-min-scale',
                    settings
                    .interactive_cursor_pulse_min_scale
                    ?.size || 1
                );

                fxWrapper.css(
                    '--mi-pulse-max-scale',
                    settings
                    .interactive_cursor_pulse_max_scale
                    ?.size || 1.05
                );

                fxWrapper.css(
                    '--mi-pulse-speed',
                    (
                        settings
                        .interactive_cursor_pulse_speed
                        ?.size || 2
                    ) + 's'
                );
            }

            /*  Rotation */

            if (
                settings.interactive_cursor_rotation ===
                'yes'
            ) {

                fxWrapper.addClass(
                    'wpkoi-mi-effect-rotation'
                );

                fxWrapper.css(
                    '--mi-rotation-speed',
                    (
                        settings
                        .interactive_cursor_rotation_speed
                        ?.size || 15
                    ) + 's'
                );

                if (
                    settings
                    .interactive_cursor_rotation_reverse ===
                    'yes'
                ) {

                    fxWrapper.addClass(
                        'wpkoi-mi-rotation-reverse'
                    );
                }
            }

            /*  Blend Mode */

            if (
                settings.interactive_cursor_blend_mode ===
                'yes'
            ) {

                preview.find(
                    '.wpkoi-mouse-box'
                ).css(
                    'mix-blend-mode',
                    settings
                    .interactive_cursor_blend_mode_type ||
                    'difference'
                );
            }

            /*  Content Wrapper */

            const content =
                jQuery(`
			<div class="
				wpkoi-mouse-content-wrapper
			"></div>
		`);

            const contentMode =
                settings.interactive_cursor_content_mode || 'normal';

            /* Main */

            if (
                settings.interactive_cursor_main_content_show ===
                'yes'
            ) {

                if (
                    contentMode === 'circle'
                ) {

                    const circle =
                        await WPKoiCreateCircleText(
                            settings.interactive_cursor_main_content || '', {
                                radius: settings.interactive_cursor_circle_radius?.size,

                                radiusUnit: settings.interactive_cursor_circle_radius?.unit,

                                startAngle: settings.interactive_cursor_circle_start_angle?.size,

                                reverse: settings.interactive_cursor_circle_reverse === 'yes',

                                gap: settings.interactive_cursor_circle_gap?.size,

                                fontFamily: settings.interactive_cursor_main_content_typography_font_family,

                                fontSize: settings.interactive_cursor_main_content_typography_font_size?.size,

                                fontSizeUnit: settings.interactive_cursor_main_content_typography_font_size?.unit,

                                fontWeight: settings.interactive_cursor_main_content_typography_font_weight,

                                letterSpacing: settings.interactive_cursor_main_content_typography_letter_spacing?.size,

                                letterSpacingUnit: settings.interactive_cursor_main_content_typography_letter_spacing?.unit,

                                textTransform: settings.interactive_cursor_main_content_typography_text_transform,

                                fontStyle: settings.interactive_cursor_main_content_typography_font_style,

                                lineHeight: settings.interactive_cursor_main_content_typography_line_height?.size,

                                lineHeightUnit: settings.interactive_cursor_main_content_typography_line_height?.unit,
                            }
                        );

                    content.append(circle);

                } else {

                    const main =
                        $('<div class="wpkoi-mi-main-content"></div>');

                    main.text(
                        settings.interactive_cursor_main_content || ''
                    );

                    content.append(main);
                }
            }

            /*  Sub */

            if (
                settings.interactive_cursor_sub_content_show ===
                'yes' &&
                contentMode !== 'circle'
            ) {

                const sub =
                    $('<div class="wpkoi-mi-sub-content"></div>');

                sub.text(
                    settings.interactive_cursor_sub_content || ''
                );

                content.append(sub);
            }

            /*  Icon */

            let icon = null;

            const iconEnabled =
                settings.interactive_cursor_icon_show ===
                'yes';

            if (
                iconEnabled &&
                settings.interactive_cursor_icon &&
                settings.interactive_cursor_icon.value
            ) {

                icon = jQuery(`
			<div class="
				wpkoi-mi-icon-wrapper
			">
				<div class="
					wpkoi-mi-icon
				">
					<i class="
						${settings.interactive_cursor_icon.value}
					"></i>
				</div>
			</div>
		`);
            }

            if (
                contentMode === 'circle'
            ) {

                const center =
                    jQuery(`
						<div class="
							wpkoi-mi-circle-center
						"></div>
					`);

                const centerMode =
                    settings.interactive_cursor_circle_center_mode || 'none';

                if (
                    centerMode === 'icon' &&
                    icon
                ) {

                    center.append(icon.clone());

                } else if (
                    centerMode === 'sub' &&
                    settings.interactive_cursor_sub_content_show === 'yes'
                ) {

                    const centerSub =
                        $('<div class="wpkoi-mi-sub-content"></div>');

                    centerSub.text(
                        settings.interactive_cursor_sub_content || ''
                    );

                    center.append(centerSub);

                }

                inner.append(center);
            }

            /*  Icon Position */

            const iconPosition =
                settings.interactive_cursor_icon_position ||
                'top';

            if (
                contentMode !== 'circle'
            ) {

                if (icon) {

                    if (
                        iconPosition === 'left' ||
                        iconPosition === 'right'
                    ) {

                        inner.addClass(
                            'wpkoi-mi-layout-row'
                        );
                    }

                    if (
                        iconPosition === 'top' ||
                        iconPosition === 'left'
                    ) {

                        inner.append(icon);
                        inner.append(content);

                    } else {

                        inner.append(content);
                        inner.append(icon);
                    }

                } else {

                    inner.append(content);
                }

            } else {

                inner.append(content);
            }

            /*  Append */

            $scope.append(preview);
        },

        getEditorModel: function(elementId) {

            let foundModel = null;

            const findModel = function(models) {

                if (!models) {
                    return;
                }

                models.forEach(function(model) {

                    /*  Match */

                    if (
                        model.id == elementId
                    ) {

                        foundModel = model;

                        return;
                    }

                    /*  Children */

                    if (
                        model.attributes &&
                        model.attributes.elements &&
                        model.attributes.elements.models
                    ) {

                        findModel(
                            model.attributes
                            .elements
                            .models
                        );
                    }
                });
            };

            findModel(
                window.elementor
                .elements
                .models
            );

            return foundModel;
        },

        getEditorSettings: function(elementId) {

            let settings = {};

            const findModel = function(models) {

                if (!models) {
                    return;
                }

                models.forEach(function(model) {

                    /*  Match */

                    if (
                        model.id == elementId
                    ) {

                        settings =
                            model
                            .attributes
                            .settings
                            .attributes;

                        return;
                    }

                    /*  Children */

                    if (
                        model.attributes &&
                        model.attributes.elements &&
                        model.attributes.elements.models
                    ) {

                        findModel(
                            model.attributes
                            .elements
                            .models
                        );
                    }
                });
            };

            findModel(
                window.elementor
                .elements
                .models
            );

            return settings;
        },
    };

    $(window).on(
        'elementor/frontend/init',
        WPKoiMouseInteraction.init
    );

})(jQuery, window.elementorFrontend);