export default {
    methods: {
        renderPrizegivingSupport(replacements) {
            Object.entries(this.elements).forEach(([key, element]) => {
                this.replaceContent(element, 'headline', replacements.headline);
                this.replaceContent(element, 'body', replacements.body);
            });
        },
        renderPrizegivingSlide(replacements) {
            this.renderPrizegivingSlideOrWinners(replacements, false);
        },
        renderPrizegivingWinners(replacements) {
            this.renderPrizegivingSlideOrWinners(replacements, true);
        },
        renderPrizegivingSlideOrWinners(replacements, includeRank) {
            // Replace headline
            this.replaceContentGlobal('headline', replacements.headline, true);
            let baseEntryElement, baseRankElement = false;

            Object.entries(this.elements).forEach(([key, element]) => {
                if (element.properties.prettyname === 'entry') {
                    baseEntryElement = element;
                }
                if (element.properties.prettyname === 'rank') {
                    baseRankElement = element;
                }
            });

            // Duplicate elements and replace placeholders
            replacements.rows.forEach((row, index) => {
                let entryElement;
                let rankElement;
                if (index === 0) {
                    entryElement = baseEntryElement;
                    rankElement = baseRankElement;
                } else {
                    entryElement = this.cloneElement(baseEntryElement, index, 60 * index);
                    rankElement = this.cloneElement(baseRankElement, index, 60 * index);
                }

                this.replaceContent(entryElement, ['title', 'author'], [row.title, row.author], true);
                if (includeRank) {
                    this.replaceContent(rankElement, 'rank', '#'+row.rank, true);
                } else {
                    this.replaceContent(rankElement, 'rank', '', true);
                }
            });
        },
    }
};
