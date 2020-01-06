export default {
    methods: {
        renderTimetable(replacements) {
            let baseTimeElement, baseTypeElement, baseNameElement = false;

            Object.entries(this.elements).forEach(([key, element]) => {
                if (element.properties.prettyname === 'timetable_time') {
                    baseTimeElement = element;
                }
                if (element.properties.prettyname === 'timetable_event_type') {
                    baseTypeElement = element;
                }
                if (element.properties.prettyname === 'timetable_event_name') {
                    baseNameElement = element;
                }
            });

            // Replace headline
            this.replaceContentGlobal('headline', replacements.headline, true);


            // Duplicate elements and replace placeholders
            let previousTime = null;
            replacements.rows.forEach((row, index) => {
                let timeElement;
                let typeElement;
                let nameElement;
                if (index === 0) {
                    timeElement = baseTimeElement;
                    typeElement = baseTypeElement;
                    nameElement = baseNameElement;
                } else {
                    timeElement = this.cloneElement(baseTimeElement, index, 40 * index);
                    typeElement = this.cloneElement(baseTypeElement, index, 40 * index);
                    nameElement = this.cloneElement(baseNameElement, index, 40 * index);
                }

                this.replaceColor(typeElement, row.color);

                if (row.time == previousTime) {
                    this.replaceContent(timeElement, 'time', '', true);
                } else {
                    this.replaceContent(timeElement, 'time', row.time, true);
                }

                this.replaceContent(typeElement, 'type', row.type, true);
                this.replaceContent(nameElement, 'name', row.name, true);

                previousTime = row.time;
            });
        },
        replaceColor(element, color) {
            element.properties.color = color;
        }
    }
};
