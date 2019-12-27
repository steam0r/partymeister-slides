let WebFont = require('webfontloader');

export default {
    created() {
    },
    methods: {
        loadFontEvent($event) {
            let font = $event.srcElement.value;
            this.loadFont(font);
            this.fonts.push(font);
        },
        loadFont(font) {
            WebFont.load({
                google: {
                    families: [font]
                },
                active: () => {
                    console.log("Font "+font+" loaded");
                },
                inactive: () => {
                    console.log("Font " + font + " failed to load");
                }
            });
        }
    }
};
