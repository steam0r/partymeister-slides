export default {
    methods: {
        cloneElement(element, nameModifier, yOffset) {
            let clonedElement = JSON.parse(JSON.stringify(element));
            clonedElement.name = clonedElement.name + '_' + nameModifier;
            Vue.set(this.elements, clonedElement.name, clonedElement);
            this.updateElementProperties(this.elements[clonedElement.name]);
            this.$forceNextTick(() => {
                document.querySelector('#' + this.name + ' .' + clonedElement.name).style.top = yOffset + 'px';
            });
            // setTimeout(() => {
            // }, 0);

            return this.elements[clonedElement.name];
        },
        replaceContentGlobal(name, value, update) {
            Object.entries(this.elements).forEach(([key, element]) => {
                this.replaceContent(element, name, value, update);
            });
        },
        replaceContent(element, name, value, update) {
            let content = element.properties.placeholder;
            if (Array.isArray(name)) {
                for (const [i, n] of name.entries()) {
                    content = content.replace('<<' + n + '>>', value[i]);
                }
            } else {
                content = content.replace('<<' + name + '>>', value);
            }

            if (content !== element.properties.placeholder) {
                element.properties.content = content;
                if (update) {
                    this.$forceNextTick(() => {
                        this.updateElementProperties(element);
                    });
                    // setTimeout(() => {
                    // }, 100);
                }
            }
        },
    }
};
