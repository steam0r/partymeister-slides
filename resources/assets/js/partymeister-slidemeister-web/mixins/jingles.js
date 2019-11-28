export default {
    data: function () {
        return {
            jingle: null,
        };
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
