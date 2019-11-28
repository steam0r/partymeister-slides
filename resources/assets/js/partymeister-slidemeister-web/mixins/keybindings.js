export default {
    created() {
        window.addEventListener('keydown', (e) => {

            switch (e.key) {
                case 'F1':
                    console.log("F1 pressed");
                    this.playJingle('jingle_1');
                    e.preventDefault();
                    break;
                case 'F2':
                    console.log("F2 pressed");
                    this.playJingle('jingle_2');
                    e.preventDefault();
                    break;
                case 'F3':
                    console.log("F3 pressed");
                    this.playJingle('jingle_3');
                    e.preventDefault();
                    break;
                case 'F4':
                    console.log("F4 pressed");
                    this.playJingle('jingle_4');
                    e.preventDefault();
                    break;
            }

            if (e.key === 'd') {
                e.preventDefault();
                let debugWindow = document.querySelector('.alert.alert-danger');
                if (debugWindow.className.match(/\bd-none\b/)) {
                    debugWindow.classList.remove('d-none');
                } else {
                    debugWindow.classList.add('d-none');
                }
            }

            if (this.playlist.id !== undefined) {
                if (e.code === 'Space' && this.items[this.currentItem].slide_type === 'siegmeister_bars') {
                    e.preventDefault();
                    console.log('space pressed - rendering bars!');
                    this.renderPrizegivingBars();
                }
                if (e.key === 'ArrowRight' || e.key === 'ArrowLeft') {
                    e.preventDefault();
                    if (this.playnow && this.items.length > 0) {
                        console.log("Playnow is active - reverting to previous playlist");
                        this.playnow = false;
                    } else if (this.playnow) {
                        // Do nothing if there is ONLY a playnow slide and nothing else
                        return;
                    }
                }
                if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    if (e.shiftKey) {
                        // Hard transition
                        this.seekToNextItem(true)
                    } else {
                        // Soft transition
                        this.seekToNextItem(false)
                    }
                }
                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    if (e.shiftKey) {
                        // Hard transition
                        this.seekToPreviousItem(true)
                    } else {
                        // Soft transition
                        this.seekToPreviousItem(false)
                    }
                }
            }
        });
    },
    methods: {
    }
}
