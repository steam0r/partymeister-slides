export default {
    methods: {
        resizeText(target, width, height) {
            this.$forceNextTick(() => {
                if (!width) {
                    width = target.offsetWidth;
                }
                if (!height) {
                    height = target.offsetHeight;
                }

                let textElement = target.querySelector('div');
                while ((textElement.offsetWidth < width && textElement.offsetHeight < height) && parseInt(textElement.style.fontSize.replace('px', '')) <= parseInt(textElement.dataset.fontSize)) {
                    textElement.style.fontSize = (parseInt(textElement.style.fontSize.replace('px', '')) + 1) + 'px';
                    target.style.fontSize = textElement.style.fontSize;
                }

                while ((textElement.offsetWidth > width || textElement.offsetHeight > height) && parseInt(textElement.style.fontSize.replace('px', '')) > 5) {
                    textElement.style.fontSize = (parseInt(textElement.style.fontSize.replace('px', '')) - 1) + 'px';
                    target.style.fontSize = textElement.style.fontSize;
                }

                let element = this.elements[target.classList[1]];
                element.properties.calculatedFontSize = textElement.style.fontSize;
            });
        }
    }
};
