
(function (window, document) {
    'use strict';

    // Global Dynamic Viewport Height Manager
    const DynamicVH = {
        // Configuration
        config: {
            debounceDelay: 100,
            orientationDelay: 500,
            iosScrollDelay: 150,
            enableDebug: false // Set to true for console logs
        },

        // State
        state: {
            isInitialized: false,
            currentVH: null,
            deviceInfo: {},
            resizeTimer: null,
            scrollTimer: null
        },

        // Device detection
        detectDevice() {
            const userAgent = navigator.userAgent.toLowerCase();
            const platform = navigator.platform.toLowerCase();

            const deviceInfo = {
                isIOS: /iphone|ipad|ipod/.test(userAgent) || /iphone|ipad|ipod/.test(platform),
                isAndroid: /android/.test(userAgent),
                isSafari: /safari/.test(userAgent) && !/chrome/.test(userAgent),
                isChrome: /chrome/.test(userAgent) && !/safari/.test(userAgent),
                isMobile: /mobile|tablet|android|iphone|ipad|ipod/.test(userAgent) || window
                    .innerWidth <= 768,
                isTablet: /tablet|ipad/.test(userAgent) || (window.innerWidth >= 768 && window
                    .innerWidth <= 1024),
                screenWidth: window.innerWidth,
                screenHeight: window.innerHeight,
                orientation: window.innerWidth > window.innerHeight ? 'landscape' : 'portrait'
            };

            // Add device classes to body
            this.updateBodyClasses(deviceInfo);

            this.state.deviceInfo = deviceInfo;

            if (this.config.enableDebug) {
                console.log('Device detected:', deviceInfo);
            }

            return deviceInfo;
        },

        // Update body classes based on device
        updateBodyClasses(deviceInfo) {
            const body = document.body;

            // Remove old classes
            const oldClasses = ['ios-device', 'android-device', 'mobile-device', 'tablet-device',
                'desktop-device', 'safari-browser', 'chrome-browser', 'landscape', 'portrait'
            ];
            body.classList.remove(...oldClasses);

            // Add new classes
            if (deviceInfo.isIOS) body.classList.add('ios-device');
            if (deviceInfo.isAndroid) body.classList.add('android-device');
            if (deviceInfo.isMobile) body.classList.add('mobile-device');
            if (deviceInfo.isTablet) body.classList.add('tablet-device');
            if (!deviceInfo.isMobile && !deviceInfo.isTablet) body.classList.add('desktop-device');
            if (deviceInfo.isSafari) body.classList.add('safari-browser');
            if (deviceInfo.isChrome) body.classList.add('chrome-browser');
            body.classList.add(deviceInfo.orientation);
        },

        // Set CSS custom properties for viewport height
        setVH() {
            const vh = window.innerHeight * 0.01;
            const vw = window.innerWidth * 0.01;

            // Only update if changed significantly (avoid unnecessary repaints)
            if (this.state.currentVH === null || Math.abs(this.state.currentVH - vh) > 0.1) {
                document.documentElement.style.setProperty('--vh', `${vh}px`);
                document.documentElement.style.setProperty('--vw', `${vw}px`);
                document.documentElement.style.setProperty('--device-height', `${window.innerHeight}px`);
                document.documentElement.style.setProperty('--device-width', `${window.innerWidth}px`);

                this.state.currentVH = vh;

                if (this.config.enableDebug) {
                    console.log('VH updated:', {
                        vh: `${vh}px`,
                        height: window.innerHeight
                    });
                }

                // Dispatch custom event for other scripts to listen to
                window.dispatchEvent(new CustomEvent('vh-updated', {
                    detail: {
                        vh: vh,
                        height: window.innerHeight,
                        width: window.innerWidth,
                        deviceInfo: this.state.deviceInfo
                    }
                }));
            }
        },

        // Handle window resize
        handleResize() {
            clearTimeout(this.state.resizeTimer);
            this.state.resizeTimer = setTimeout(() => {
                this.detectDevice();
                this.setVH();
            }, this.config.debounceDelay);
        },

        // Handle orientation change
        handleOrientationChange() {
            setTimeout(() => {
                this.detectDevice();
                this.setVH();
            }, this.config.orientationDelay);
        },

        // Handle iOS Safari scroll (address bar show/hide)
        handleIOSScroll() {
            if (!this.state.deviceInfo.isIOS) return;

            clearTimeout(this.state.scrollTimer);
            this.state.scrollTimer = setTimeout(() => {
                this.setVH();
            }, this.config.iosScrollDelay);
        },

        // Handle focus events that might change viewport (iOS keyboards)
        handleFocusEvents() {
            if (!this.state.deviceInfo.isMobile) return;

            setTimeout(() => {
                this.setVH();
            }, 300);
        },

        // Add event listeners
        addEventListeners() {
            // Resize events
            window.addEventListener('resize', this.handleResize.bind(this), {
                passive: true
            });

            // Orientation change
            window.addEventListener('orientationchange', this.handleOrientationChange.bind(this), {
                passive: true
            });

            // iOS Safari specific events
            if (this.state.deviceInfo.isIOS && this.state.deviceInfo.isSafari) {
                window.addEventListener('scroll', this.handleIOSScroll.bind(this), {
                    passive: true
                });
                document.addEventListener('focusin', this.handleFocusEvents.bind(this), {
                    passive: true
                });
                document.addEventListener('focusout', this.handleFocusEvents.bind(this), {
                    passive: true
                });
            }

            // Page visibility change (when switching between apps)
            document.addEventListener('visibilitychange', () => {
                if (!document.hidden) {
                    setTimeout(() => this.setVH(), 100);
                }
            }, {
                passive: true
            });
        },

        // Initialize the system
        init() {
            if (this.state.isInitialized) return;

            this.detectDevice();
            this.setVH();
            this.addEventListeners();

            this.state.isInitialized = true;

            if (this.config.enableDebug) {
                console.log('Dynamic VH initialized');
            }

            // Dispatch initialization event
            window.dispatchEvent(new CustomEvent('dynamic-vh-ready', {
                detail: this.state.deviceInfo
            }));
        },

        // Public API methods
        refresh() {
            this.detectDevice();
            this.setVH();
        },

        getDeviceInfo() {
            return this.state.deviceInfo;
        },

        enableDebug() {
            this.config.enableDebug = true;
        },

        disableDebug() {
            this.config.enableDebug = false;
        },

        // Update configuration
        configure(options) {
            Object.assign(this.config, options);
        }
    };

    // Auto-initialize when DOM is ready
    function autoInit() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => DynamicVH.init());
        } else {
            DynamicVH.init();
        }
    }

    // Expose to global scope
    window.DynamicVH = DynamicVH;

    // Auto-initialize
    autoInit();

    // Helper function for easy integration with frameworks
    window.initDynamicVH = function () {
        return DynamicVH.init();
    };

})(window, document);

// Optional: Alpine.js integration helper
if (typeof Alpine !== 'undefined') {
    document.addEventListener('alpine:init', () => {
        Alpine.data('dynamicVH', () => ({
            deviceInfo: {},
            vh: '1vh',

            init() {
                this.deviceInfo = window.DynamicVH.getDeviceInfo();

                // Listen for VH updates
                window.addEventListener('vh-updated', (event) => {
                    this.vh = event.detail.vh + 'px';
                    this.deviceInfo = event.detail.deviceInfo;
                });
            }
        }));
    });
}

// Optional: Vue.js integration helper
if (typeof Vue !== 'undefined') {
    Vue.mixin({
        data() {
            return {
                $dynamicVH: window.DynamicVH
            };
        }
    });
}