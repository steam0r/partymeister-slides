export default {
    methods: {
        focusEditor(event) {
            let target = undefined;
            let clickTarget = event.target;
            if (!clickTarget) {
                return;
            }

            // we need to go up a maximum of two levels
            if (clickTarget.className === '') {
                clickTarget = clickTarget.parentElement;
            }

            if (clickTarget.className === '') {
                clickTarget = clickTarget.parentElement;
            }

            if (clickTarget.className === 'medium-editor-element') {
                target = clickTarget;
            } else if (clickTarget.className.search('moveable') !== -1) {
                target = event.target.querySelector('.medium-editor-element');
            }

            let elementTarget = target.parentElement;
            let element = this.elements[elementTarget.classList[1]];

            if (clickTarget && element.properties.editable) {
                target.setAttribute('contentEditable', 'true');
                target.focus();
                window.getSelection()
                    .selectAllChildren(
                        target
                    );
            } else if (!element.properties.editable) {
                target.setAttribute('contentEditable', 'false');
            }
        },
        processEditOperation: function (operation) {
            let target = operation.api.origElements.parentElement;
            this.text = operation.api.origElements.innerHTML;
            if (target.style.width == '') {
                target.style.width = '300px';
            }
            if (target.style.height == '') {
                target.style.height = '200px';
            }

            let element = this.elements[target.classList[1]];
            element.properties.content = operation.api.origElements.innerHTML;
            this.resizeText(operation.api.origElements.parentElement, parseInt(operation.api.origElements.parentElement.style.width.replace('px', '')), parseInt(operation.api.origElements.parentElement.style.height.replace('px', '')));
            this.emitAllElements();
        },
    }
};
