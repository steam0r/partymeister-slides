export default {
    data: function () {
        return {
            jingles: [],
            jingle: null,
        };
    },
    created() {
        this.$eventHub.$on('jingles-loaded', (jingles) => {
            this.jingles = jingles;
        });
    },
    methods: {
        playJingle(index) {
            if (this.jingles[index] !== undefined) {
                this.jingle = this.jingles[index];
                let player = document.querySelector('#jingle-player > audio');
                setTimeout(() => {
                    player.play();
                }, 10);
            }
        },
    }
}
