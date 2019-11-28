<template>
    <main>
        <div class="debug alert alert-danger d-none">
            CachedPlaylists: {{ cachedPlaylists.length }}<br>
            Playlist: {{ playlist.name }}<br>
            Items: {{ items.length }}<br>
            CurrentItem: {{ currentItem }}<br>
            <button @click="deleteStorage">Empty cache</button>
            <vue-audio style="display: none;" id="jingle-player" :file="jingle"/>
        </div>

        <template v-if="currentItem != null && current != undefined">
            <img v-if="current.type == 'image' && current.cached_html_final == undefined"
                 :src="current.file.file_original" class="img-fluid slide current">
            <div v-if="current.type == 'image' && current.cached_html_final != ''"
                 v-html="current.cached_html_final" class="slidemeister-instance slide current"
                 :style="{'zoom': zoom}"></div>
            <video v-if="current.type == 'video'" id="video-current" class="slide current">
                <source :src="current.file.file_original" type="video/mp4">
            </video>
        </template>
        <template v-if="previousItem != null && previous != undefined">
            <img v-if="previous.type == 'image' && previous.cached_html_final === undefined"
                 :src="previous.file.file_original" class="img-fluid slide previous">
            <div v-if="previous.type == 'image' && previous.cached_html_final != ''"
                 v-html="previous.cached_html_final" class="slidemeister-instance slide previous"
                 :style="{'zoom': zoom}"></div>
            <video v-if="previous.type == 'video'" id="video-previous" class="slide previous">
                <source :src="previous.file.file_original" type="video/mp4">
            </video>
        </template>
        <template class="next-item" v-if="nextItem != null && next != undefined">
            <img v-if="next.type == 'image' && next.cached_html_final === undefined"
                 :src="next.file.file_original" class="img-fluid slide next">
            <div v-if="next.type == 'image' && next.cached_html_final != ''"
                 v-html="next.cached_html_final" class="slidemeister-instance slide next" :style="{'zoom': zoom}"></div>
            <video v-if="next.type == 'video'" id="video-next" class="slide next">
                <source :src="next.file.file_original" type="video/mp4">
            </video>
        </template>
    </main>
</template>

<script>

    import keybindings from "../mixins/keybindings";
    import jingles from "../mixins/jingles";
    import siegmeister from "../mixins/siegmeister";

    export default {
        name: 'partymeister-slidemeister-web',
        props: ['configuration', 'jingles', 'route'],
        mixins: [
            keybindings,
            jingles,
            siegmeister
        ],
        data: function () {
            return {
                zoom: 2,
                cachedPlaylists: [],
                playnow: false,
                playnowItem: {},
                currentItemSaved: null,
                playlistSaved: {},
                playlist: {},
                items: [],
                currentItem: null,
                previousItem: null,
                nextItem: null,
                callbackTimeout: null,
                slideTimeout: null,
                currentBackground: null,
                transitionGroups: [
                    ['fadeIn', 'fadeOut'],
                    ['rotateInUpLeft', 'rotateOutDownRight'],
                    ['lightSpeedIn', 'lightSpeedOut'],
                    ['bounceIn', 'fadeOut'],
                    ['flipInX', 'fadeOut'],
                    ['bounce', 'fadeOut'],
                    ['pulse', 'fadeOut'],
                    ['tada', 'fadeOut'],
                    ['jello', 'fadeOut'],
                    ['zoomIn', 'zoomOut'],
                    ['zoomInDown', 'zoomOutUp'],
                    ['rollIn', 'rollOut'],
                ],
            };
        },
        computed: {
            // a computed getter
            current: function () {
                if (this.currentItem === 'playnow') {
                    return this.playnowItem;
                }
                return this.items[this.currentItem];
            },
            next: function () {
                console.log("Change in NEXT detected");
                if (this.nextItem === 'playnow') {
                    return this.playnowItem;
                }
                return this.items[this.nextItem];
            },
            previous: function () {
                return this.items[this.previousItem];
            },
        },
        methods: {
            insertPlayNow() {
                this.nextItem = 'playnow';
                setTimeout(() => {
                    this.playTransition();
                }, 0)
            },
            afterSeek() {
                localStorage.setItem('currentItem', this.currentItem);

                if (!this.playnow) {
                    this.checkVideo();
                    this.animateBackground();
                    this.setCallbackDelay();
                    this.setSlideTimeout();
                }
                this.updateStatus();

            },
            seekToIndex(index, hard) {
                console.log('Seek to index ' + index);
                this.clearTimeouts();

                if (this.items[index] !== undefined) {
                    this.currentItem = index;
                } else {
                    this.currentItem = 0;
                }
                if (!hard) {
                    setTimeout(() => {
                        this.playTransition(this.items[this.currentItem].transition_slidemeister_identifier, this.items[this.currentItem].transition_duration);
                    }, 10);
                } else {
                    this.previousItem = null;
                    this.afterSeek();
                }

            },
            prepareTransition(currentItem, hard) {
                if (!hard) {
                    setTimeout(() => {
                        this.playTransition(this.items[currentItem].transition_slidemeister_identifier, this.items[currentItem].transition_duration);
                    }, 0);
                } else {
                    this.currentItem = this.nextItem;
                    this.afterSeek();
                }
            },
            seekToNextItem(hard) {
                console.log('Seek to next item');
                this.clearTimeouts();

                let currentItem = this.currentItem;
                if ( this.currentItem === 'playnow') {
                    currentItem = this.currentItemSaved;
                }

                if (this.items[currentItem + 1] !== undefined) {
                    this.nextItem = currentItem + 1;
                } else {
                    this.nextItem = 0;
                }
                if (this.items[currentItem - 1] !== undefined) {
                    this.previousItem = currentItem - 1;
                } else {
                    this.previousItem = this.items.length - 1;
                }
                this.prepareTransition(currentItem, hard);
            },
            seekToPreviousItem(hard) {
                console.log('Seek to previous item');
                this.clearTimeouts();

                let currentItem = this.currentItem;
                if ( this.currentItem === 'playnow') {
                    currentItem = this.currentItemSaved;
                }

                if (this.items[currentItem - 1] !== undefined) {
                    this.nextItem = currentItem - 1;
                } else {
                    this.nextItem = this.items.length - 1;
                }
                if (this.items[currentItem + 1] !== undefined) {
                    this.previousItem = currentItem + 1;
                } else {
                    this.previousItem = 0;
                }
                this.prepareTransition(currentItem, hard);
            },
            checkVideo() {
                if (this.items[this.currentItem].type === 'video') {
                    setTimeout(() => {
                        let currentVideo = document.getElementById("video-current");
                        if (currentVideo != null) {
                            currentVideo.currentTime = 0;
                            currentVideo.play();
                        }
                        let previousVideo = document.getElementById("video-previous");
                        if (previousVideo != null) {
                            previousVideo.pause();
                        }
                    }, 10);
                }
            },
            playTransition(transition, duration) {
                console.log('Play transition');

                this.clearSiegmeisterBars();

                let transitionGroup = this.transitionGroups[Math.floor(Math.random() * this.transitionGroups.length)];
                if (transition !== 255 && transition !== '') {
                    transitionGroup = this.transitionGroups[parseInt(transition)];
                }
                if (transitionGroup === undefined || transitionGroup.length !== 2) {
                    transitionGroup = this.transitionGroups[Math.floor(Math.random() * this.transitionGroups.length)];
                }

                this.animateCSS('.next', transitionGroup[0], () => {
                    console.log('Transition done - swapping items');
                    document.querySelector('.next').style.zIndex = 1001;
                    this.currentItem = this.nextItem;

                    setTimeout(() => {
                        document.querySelector('.next').style.zIndex = 999;
                        this.afterSeek();
                    }, 0);
                });

                this.animateCSS('.current', transitionGroup[1], () => {
                    console.log('Transition done');
                });
            },
            setSlideTimeout() {
                if (!this.items[this.currentItem].is_advanced_manually) {
                    console.log('Setting timeout to ' + this.items[this.currentItem].duration);
                    this.slideTimeout = setTimeout(() => {
                        this.seekToNextItem();
                    }, this.items[this.currentItem].duration * 1000)
                }
            },
            setCallbackDelay() {
                if (this.playlist.callbacks !== undefined && this.playlist.callbacks) {
                    console.log('Setting callback timeout to ' + this.items[this.currentItem].callback_delay);
                    if (this.items[this.currentItem].callback_hash !== '') {
                        this.callbackTimeout = setTimeout(() => {
                            console.log('Excuting callback ' + this.items[this.currentItem].callback_hash);
                            axios.get(this.playlist.callback_url + this.items[this.currentItem].callback_hash).then(result => {
                                console.log('Callback successfully executed');
                            }).catch(e => {
                                console.log('Error executing callback');
                            });
                        }, this.items[this.currentItem].callback_delay * 1000)
                    }
                }
            },
            clearTimeouts() {
                console.log('Clearing timeouts');
                window.clearTimeout(this.callbackTimeout);
                window.clearTimeout(this.slideTimeout);
            },
            animateBackground() {
                if (this.currentBackground === this.items[this.currentItem].slide_type) {
                    console.log('Correct background is already playing, skipping');
                    this.currentBackground = this.items[this.currentItem].slide_type;
                    this.clearSiegmeisterBars();
                    return;
                }
                if (this.currentBackground !== this.items[this.currentItem].slide_type) {
                    console.log('New background needed, stopping all playing backgrounds');
                    stopEnd();
                    stopComingup();
                    stopStarfield();
                }
                this.currentBackground = this.items[this.currentItem].slide_type;
                this.clearSiegmeisterBars();

                switch (this.currentBackground) {
                    case 'comingup':
                        // startComingup();
                        break;
                    case 'end':
                        // startEnd();
                        break;
                    case 'default':
                    case 'announce':
                        // startStarfield();
                        break;
                    case 'compo':
                    case 'siegmeister_bars':
                    case 'siegmeister_winners':
                        // startStarfield();
                        break;
                }
            },
            updateStatus() {
                console.log('Update status');

                let currentItem = this.items[this.currentItem];
                let currentItemId = null;

                if (currentItem !== undefined) {
                    currentItemId = currentItem.id;
                }

                let data = {
                    playlists: this.cachedPlaylists.map(playlist => {
                        return {id: playlist.id, updated_at: new Date(playlist.updated_at.date).getTime() / 1000}
                    }),
                    currentPlaylist: this.playlist.id,
                    currentItem: currentItemId,
                };
                axios.post(this.route, data).then(response => {
                    console.log('Updated status');
                });
            },
            resizeWindow() {
                let scaleX = window.innerWidth / 960;
                let scaleY = window.innerHeight / 540;

                this.zoom = Math.min(scaleX, scaleY);
            },
            animateCSS(element, animationName, callback) {
                const node = document.querySelector(element);
                if (node === null) {
                    console.error('Node ' + element + ' not found - skipping');
                    return;
                }
                node.classList.add('animated', animationName);

                function handleAnimationEnd() {
                    node.classList.remove('animated', animationName);
                    node.removeEventListener('animationend', handleAnimationEnd);

                    if (typeof callback === 'function') callback()
                }

                node.addEventListener('animationend', handleAnimationEnd);
            },
            deleteStorage() {
                this.cachedPlaylists = [];
                this.playlist = {};
                this.items = [];
                this.currentItem = null;

                // stopEnd();
                // stopComingup();
                // stopStarfield();
                document.querySelectorAll('canvas').forEach((element) => {
                    element.style.zIndex = 0;
                });

                localStorage.clear();
                this.updateStatus();
            },
        },
        mounted() {
            window.onresize = () => {
                this.resizeWindow();
            };

            setTimeout(() => {
                this.resizeWindow();
            }, 0);

            // Check if we have playlists in local storage
            let cachedPlaylists = localStorage.getItem('cachedPlaylists');
            if (cachedPlaylists !== undefined && cachedPlaylists != null) {
                this.cachedPlaylists = JSON.parse(cachedPlaylists);
            }
            let playlist = localStorage.getItem('playlist');
            if (playlist !== undefined && playlist != null) {
                this.playlist = JSON.parse(playlist);
                this.items = this.playlist.items;
            }
            let currentItem = localStorage.getItem('currentItem');
            if (currentItem !== undefined && currentItem != null) {
                this.seekToIndex(parseInt(currentItem), true);
            }

        }
    }

</script>
<style lang="scss">
    body {
        cursor: none;
    }

    canvas {
        position: absolute;
        background-color: #000000;
        overflow: hidden
    }

    body {
        background-color: black;
        overflow: hidden;
    }

    main {
        position: absolute;
        width: 100%;
        height: 100%;
    }

    main .slide {
        position: absolute;
        width: 100%;
        height: auto;
    }

    main .debug {
        position: absolute;
        z-index: 10000;
        opacity: 0.8;
    }

    main .previous {
        z-index: 9000;
    }

    main .current {
        z-index: 9100;
    }

    .slidemeister-instance {
        width: 960px;
        height: 540px;
        zoom: 2;
    }

    .slidemeister-bars {
        position: absolute;
        opacity: 0.5;
        background-color: black;
    }

    .slidemeister-bars.active {
        z-index: 10000;
    }

    .blink {
        animation: blinker 1s linear infinite;
    }

    @keyframes blinker {
        50% {
            opacity: 0.1;
        }
    }

    .current {
        z-index: 1000;
        width: 960px;
        height: 540px;
    }

    .previous {
        visibility: hidden;
        z-index: 998;
        width: 960px;
        height: 540px;
    }

    .next {
        /*visibility: hidden;*/
        z-index: 999;
        width: 960px;
        height: 540px;
    }
</style>
