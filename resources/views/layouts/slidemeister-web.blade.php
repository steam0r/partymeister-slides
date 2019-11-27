<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Slidemeister-Web</title>

    <link href="{{ mix('/css/partymeister-slidemeister-web.css') }}" rel="stylesheet" type="text/css"/>
    <style type="text/css">
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

        .slidemeister-element {
            position: absolute;
            width: 200px;
            height: 100px;
            left: 50px;
            top: 50px;
            background-color: transparent;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            border: 2px solid transparent;
            /*border-top: 1px solid #999;*/
            /*border-left: 1px solid #999;*/
            /*border-bottom: 1px solid #ccc;*/
            /*border-right: 1px solid #ccc;*/
            padding: 0;
            margin: 0;
            /*overflow: hidden;*/
        }

        .slidemeister-element div span p {
            margin-bottom: 0 !important;
        }

        .slidemeister-element {
            display: flex;
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
        }

        .previous {
            visibility: hidden;
            z-index: 998;
        }

        .next {
            /*visibility: hidden;*/
            z-index: 999;
        }
    </style>
    @include('partymeister-slides::layouts.partials.slide_fonts')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
<main id="slidemeisterVue">
    <div class="debug alert alert-danger d-none">
        CachedPlaylists: @{{ cachedPlaylists.length }}<br>
        Playlist: @{{ playlist.name }}<br>
        Items: @{{ items.length }}<br>
        CurrentItem: @{{ currentItem }}<br>
        <button class="delete-storage">Empty cache</button>
        <vue-audio style="display: none;" id="jingle-player" :file="jingle"/>
    </div>

    <template v-if="currentItem != null && items[currentItem] != undefined">
        <div v-if="items[currentItem].type == 'image' && items[currentItem].cached_html_final != ''"
             v-html="items[currentItem].cached_html_final" class="slidemeister-instance slide current"></div>
        <img v-if="items[currentItem].type == 'image' && items[currentItem].cached_html_final == undefined"
             :src="items[currentItem].file.file_original" class="img-fluid slide current">
        <video v-if="items[currentItem].type == 'video'" id="video-current" class="slide current">
            <source :src="items[currentItem].file.file_original" type="video/mp4">
        </video>
    </template>
    <template v-if="previousItem != null && items[previousItem] != undefined">
        <div v-if="items[previousItem].type == 'image' && items[previousItem].cached_html_final != ''"
             v-html="items[previousItem].cached_html_final" class="slidemeister-instance slide previous"></div>
        <img v-if="items[previousItem].type == 'image' && items[previousItem].cached_html_final == ''"
             :src="items[previousItem].file.file_original" class="img-fluid slide previous">
        <video v-if="items[previousItem].type == 'video'" id="video-previous" class="slide previous">
            <source :src="items[previousItem].file.file_original" type="video/mp4">
        </video>
    </template>
    <template class="next-item" v-if="nextItem != null && items[nextItem] != undefined">
        <div v-if="items[nextItem].type == 'image' && items[nextItem].cached_html_final != ''"
             v-html="items[nextItem].cached_html_final" class="slidemeister-instance slide next"></div>
        <img v-if="items[nextItem].type == 'image' && items[nextItem].cached_html_final == ''"
             :src="items[nextItem].file.file_original" class="img-fluid slide next">
        <video v-if="items[nextItem].type == 'video'" id="video-next" class="slide next">
            <source :src="items[nextItem].file.file_original" type="video/mp4">
        </video>
    </template>
</main>
@include('partymeister-slides::slidemeister-web.effects.starfield')
@include('partymeister-slides::slidemeister-web.effects.comingup')
@include('partymeister-slides::slidemeister-web.effects.end')

<script src="{{mix('js/partymeister-slidemeister-web.js')}}"></script>
<script>
    $.fn.extend({
        animateCss: function (animationName, callback) {
            let animationEnd = (function (el) {
                let animations = {
                    animation: 'animationend',
                    OAnimation: 'oAnimationEnd',
                    MozAnimation: 'mozAnimationEnd',
                    WebkitAnimation: 'webkitAnimationEnd',
                };

                for (let t in animations) {
                    if (el.style[t] !== undefined) {
                        return animations[t];
                    }
                }
            })(document.createElement('div'));

            this.addClass('animated ' + animationName).one(animationEnd, function () {
                $(this).removeClass('animated ' + animationName);

                if (typeof callback === 'function') callback();
            });

            return this;
        },
    });

    window.requestAnimFrame = (function () {
        return window.requestAnimationFrame ||
            window.webkitRequestAnimationFrame ||
            window.mozRequestAnimationFrame ||
            window.oRequestAnimationFrame ||
            window.msRequestAnimationFrame ||
            function (/* function */ callback, /* DOMElement */ element) {
                window.setTimeout(callback, 1000 / 60);
            };
    })();

    (function () {
        Math.clamp = function (a, b, c) {
            return Math.max(b, Math.min(c, a));
        }
    })();

    function compare(a, b) {
        if (a.x2 < b.x2)
            return -1;
        if (a.x2 > b.x2)
            return 1;
        return 0;
    }

    let slidemeisterVue = new Vue({
        el: '#slidemeisterVue',
        data: {
            jingle: null,
            jingles: {!! json_encode($jingles) !!},
            prizegivingBarColor: '{{ ($prizegivingBarColor !== '') ? $prizegivingBarColor : '#000000'}}',
            prizegivingBarBlinkColor: '{{ ($prizegivingBarBlinkColor !== '') ? $prizegivingBarBlinkColor : '#FF0000'}}',
            cachedPlaylists: [],
            playnow: false,
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
        },
        mounted() {
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
                this.seekToIndex(parseInt(currentItem));
            }

            window.addEventListener('keydown', (e) => {
                e.preventDefault();

                switch (e.key) {
                    case 'F1':
                        console.log("F1 pressed");
                        this.playJingle('jingle_1');
                        break;
                    case 'F2':
                        console.log("F2 pressed");
                        this.playJingle('jingle_2');
                        break;
                    case 'F3':
                        console.log("F3 pressed");
                        this.playJingle('jingle_3');
                        break;
                    case 'F4':
                        console.log("F4 pressed");
                        this.playJingle('jingle_4');
                        break;
                }

                if (e.key === 'd') {
                    console.log("HIER");
                    if ($('.alert.alert-danger').hasClass('d-none')) {
                        $('.alert.alert-danger').removeClass('d-none');
                    } else {
                        $('.alert.alert-danger').addClass('d-none');
                    }
                }

                if (this.playlist.id !== undefined) {
                    if (e.code === 'Space' && this.items[this.currentItem].slide_type === 'siegmeister_bars') {
                        console.log('space pressed - rendering bars!');
                        this.renderPrizegivingBars();
                    }
                    if (e.key === 'ArrowRight' || e.key === 'ArrowLeft') {
                        if (this.playnow && this.playlistSaved.id !== undefined) {
                            console.log("Playnow is active - reverting to previous playlist");
                            this.playnow = false;
                            this.playlist = this.playlistSaved;
                            this.items = this.playlist.items;
                            this.currentItem = this.currentItemSaved;
                            this.playlistSaved = {};
                            this.currentItemSaved = null;
                        } else if (this.playnow) {
                            // Do nothing if there is ONLY a playnow slide and nothing else
                            return;
                        }
                    }
                    if (e.key === 'ArrowRight') {
                        if (e.shiftKey) {
                            // Hard transition
                            this.seekToNextItem(true)
                        } else {
                            // Soft transition
                            this.seekToNextItem(false)
                        }
                    }
                    if (e.key === 'ArrowLeft') {
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
            playJingle(index) {
                if (this.jingles[index] !== undefined) {
                    this.jingle = this.jingles[index];
                    let player = document.querySelector('#jingle-player > audio');
                    setTimeout(() => {
                        player.play();
                    }, 10);
                }
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
                }

                localStorage.setItem('currentItem', this.currentItem);

                this.checkVideo();
                this.animateBackground();
                this.setCallbackDelay();
                this.setSlideTimeout();
            },
            seekToNextItem(hard) {
                console.log('Seek to next item');
                this.clearTimeouts();

                if (this.items[this.currentItem + 1] !== undefined) {
                    this.nextItem = this.currentItem + 1;
                } else {
                    this.nextItem = 0;
                }
                if (this.items[this.currentItem - 1] !== undefined) {
                    this.previousItem = this.currentItem - 1;
                } else {
                    this.previousItem = this.items.length - 1;
                }
                if (!hard) {
                    setTimeout(() => {
                        this.playTransition(this.items[this.currentItem].transition_slidemeister_identifier, this.items[this.currentItem].transition_duration);
                    }, 0);
                } else {
                    this.currentItem = this.nextItem;
                }

                localStorage.setItem('currentItem', this.currentItem + 1);

                this.checkVideo();
                this.animateBackground();
                this.setCallbackDelay();
                this.setSlideTimeout();
                this.updateStatus();
            },
            seekToPreviousItem(hard) {
                console.log('Seek to previous item');
                this.clearTimeouts();

                if (this.items[this.currentItem - 1] !== undefined) {
                    this.nextItem = this.currentItem - 1;
                } else {
                    this.nextItem = this.items.length - 1;
                }
                if (this.items[this.currentItem + 1] !== undefined) {
                    this.previousItem = this.currentItem + 1;
                } else {
                    this.previousItem = 0;
                }
                if (!hard) {
                    setTimeout(() => {
                        this.playTransition(this.items[this.currentItem].transition_slidemeister_identifier, this.items[this.currentItem].transition_duration);
                    }, 10);
                } else {
                    this.currentItem = this.nextItem;
                }

                localStorage.setItem('currentItem', this.currentItem);

                this.checkVideo();
                this.animateBackground();
                this.setCallbackDelay();
                this.setSlideTimeout();
                this.updateStatus();
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

                let transitionGroup = this.transitionGroups[Math.floor(Math.random() * this.transitionGroups.length)];
                if (transition !== 255 && transition !== '') {
                    transitionGroup = this.transitionGroups[parseInt(transition)];
                }

                $('.next').animateCss(transitionGroup[0], () => {
                    $('.next').css('z-index', 1001);
                    this.currentItem = this.nextItem;
                    setTimeout(() => {
                        $('.next').css('z-index', 999);
                        // this.nextItem = null;
                        // this.previousItem = null;
                    }, 100);
                });
                $('.current').animateCss(transitionGroup[1], () => {
                    // slidemeisterVue.previousItem = null;
                });
            },
            setSlideTimeout() {
                if (!this.items[this.currentItem].is_advanced_manually) {
                    console.log('Setting timeout to ' + this.items[this.currentItem].duration);
                    this.slideTimeout = setTimeout(() => {
                        slidemeisterVue.seekToNextItem();
                    }, this.items[this.currentItem].duration * 1000)
                }
            },
            setCallbackDelay() {
                if (this.playlist.callbacks !== undefined && this.playlist.callbacks) {
                    console.log('Setting callback timeout to ' + this.items[this.currentItem].callback_delay);
                    if (this.items[this.currentItem].callback_hash !== '') {
                        this.callbackTimeout = setTimeout(() => {
                            console.log('Excuting callback ' + slidemeisterVue.items[slidemeisterVue.currentItem].callback_hash);
                            axios.get(slidemeisterVue.playlist.callback_url + slidemeisterVue.items[slidemeisterVue.currentItem].callback_hash).then(result => {
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
            renderPrizegivingBars() {
                // remove potentially existing bar elements and remove them from the dom
                $('#slidemeister-bar-wrapper').remove();

                // Create bars element and attach it to the dom
                let bars = document.createElement("div");
                bars.className = 'slidemeister-bar-wrapper';
                bars.id = 'slidemeister-bar-wrapper';

                metadata = JSON.parse(this.items[this.currentItem].metadata);

                for (const [index, e] of metadata.entries()) {
                    let bar = document.createElement("div");
                    let left = Number((e.x1 * 1920).toFixed(2));
                    let width = Number((e.x2 * 1920 - left).toFixed(2));
                    let top = Number((e.y1 * 1080).toFixed(2));
                    let height = Number((e.y2 * 1080 - top).toFixed(2));
                    bar.id = 'bar-' + index;
                    bar.style.left = left + 'px';
                    bar.style.top = top + 'px';
                    bar.style.width = 0;
                    bar.style.height = height + 'px';
                    bar.className = 'slidemeister-bars active';
                    bar.style.backgroundColor = this.prizegivingBarColor;
                    bars.appendChild(bar);
                    metadata[index].id = 'bar-' + index;
                }
                document.body.appendChild(bars);

                // Animate
                this.animatePrizegivingBars(metadata, 0);
            },
            animatePrizegivingBars(bars, frame) {
                if (frame === 240) {
                    window.clearTimeout(barTimeout);

                    bars.sort(function (a, b) {
                        return (a.x2 < b.x2) ? 1 : ((b.x2 < a.x2) ? -1 : 0);
                    });

                    let blinkingBars = [];
                    let barValues = [];
                    for (let [index, bar] of bars.entries()) {
                        if (index === 0) {
                            blinkingBars.push(index);
                            barValues.push(bar.x2);
                        } else {
                            if (barValues.includes(bar.x2)) {
                                blinkingBars.push(index);
                                barValues.push(bar.x2);
                            } else if (barValues.length < 3) {
                                blinkingBars.push(index);
                                barValues.push(bar.x2);
                            }
                        }
                    }

                    for (const index of blinkingBars) {
                        if (bars[index] !== undefined) {
                            $('#' + bars[index].id).css('background-color', this.prizegivingBarBlinkColor).addClass('blink');
                        }
                    }

                    this.seekToNextItem(true);
                    return;
                }

                frame++;
                barTimeout = setTimeout(() => {
                    this.animatePrizegivingBars(bars, frame)
                }, 1000 / 60);
                for (const [index, e] of bars.entries()) {

                    let time = frame / 240;

                    let t = time + 0.25 * 0.5 * Math.sin(e.x2 * 2000) * (4 * (-time * time + time));
                    let w = Math.clamp(t, 0, e.x2);

                    width = Number(((w - e.x1) * 1920).toFixed(2));
                    if (width > e) {
                        width = e;
                    }

                    $('#bar-' + index).css('width', width + 'px');
                }
            },
            animateBackground() {
                if (this.currentBackground === this.items[this.currentItem].slide_type) {
                    console.log('Correct background is already playing, skipping');
                    return;
                }
                if (this.currentBackground !== this.items[this.currentItem].slide_type) {
                    console.log('New background needed, stopping all playing backgrounds');
                    stopEnd();
                    stopComingup();
                    stopStarfield();
                }
                this.currentBackground = this.items[this.currentItem].slide_type;

                if (this.currentBackground !== 'siegmeister_winners') {
                    $('#slidemeister-bar-wrapper').remove();
                }
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
                // console.log(this.items);
                // console.log(this.currentItem);
                let data = {
                    playlists: this.cachedPlaylists.map(playlist => {
                        return {id: playlist.id, updated_at: new Date(playlist.updated_at.date).getTime() / 1000}
                    }),
                    currentPlaylist: this.playlist.id,
                    currentItem: this.items[this.currentItem].id,
                };
                axios.post('{{route('ajax.slidemeister-web.status.update', ['slide_client' => $slideClientId])}}', data).then(response => {
                    console.log('Updated status');
                });
            }
        }
    });

    $('.delete-storage').on('click', () => {
        slidemeisterVue.cachedPlaylists = [];
        slidemeisterVue.playlist = {};
        slidemeisterVue.items = [];
        slidemeisterVue.currentItem = null;

        stopEnd();
        stopComingup();
        stopStarfield();
        $('canvas').css('z-index', 0);

        localStorage.clear();
        slidemeisterVue.updateStatus();
    });
</script>
@yield('view_scripts')
</body>
</html>
