<canvas id="starfield" style="background-color:#000000"></canvas>
<script type="text/javascript">
    function $i(id) {
        return document.getElementById(id);
    }

    function get_screen_size() {
        let w = document.documentElement.clientWidth;
        let h = document.documentElement.clientHeight;
        return Array(w, h);
    }

    let url = document.location.href;

    let test = true;
    let n = 512;
    let w = 0;
    let h = 0;
    let x = 0;
    let y = 0;
    let z = 0;
    let star_color_ratio = 0;
    let star_x_save, star_y_save;
    let star_ratio = 256;
    let star_speed = 4;
    let star = new Array(n);
    let opacity = 0.1;

    let cursor_x = 0;
    let cursor_y = 0;
    let mouse_x = 0;
    let mouse_y = 0;

    let context;

    let timeout;
    let fps = 0;

    function init() {
        for (let i = 0; i < n; i++) {
            star[i] = new Array(5);
            star[i][0] = Math.random() * w * 2 - x * 2;
            star[i][1] = Math.random() * h * 2 - y * 2;
            star[i][2] = Math.round(Math.random() * z);
            star[i][3] = 0;
            star[i][4] = 0;
        }
        let starfield = $i('starfield');
        starfield.style.position = 'absolute';
        starfield.width = w;
        starfield.height = h;
        context = starfield.getContext('2d');
        context.strokeStyle = 'rgb(255,255,255)';
        context.fillStyle = 'rgba(0,0,0,' + opacity + ')';
    }

    function anim() {
        mouse_x = cursor_x - x;
        mouse_y = cursor_y - y;
        context.fillRect(0, 0, w, h);
        for (let i = 0; i < n; i++) {
            test = true;
            star_x_save = star[i][3];
            star_y_save = star[i][4];
            star[i][0] += mouse_x >> 4;
            if (star[i][0] > x << 1) {
                star[i][0] -= w << 1;
                test = false;
            }
            if (star[i][0] < -x << 1) {
                star[i][0] += w << 1;
                test = false;
            }
            star[i][1] += mouse_y >> 4;
            if (star[i][1] > y << 1) {
                star[i][1] -= h << 1;
                test = false;
            }
            if (star[i][1] < -y << 1) {
                star[i][1] += h << 1;
                test = false;
            }
            star[i][2] -= star_speed;
            if (star[i][2] > z) {
                star[i][2] -= z;
                test = false;
            }
            if (star[i][2] < 0) {
                star[i][2] += z;
                test = false;
            }
            star[i][3] = x + (star[i][0] / star[i][2]) * star_ratio;
            star[i][4] = y + (star[i][1] / star[i][2]) * star_ratio;
            if (star_x_save > 0 && star_x_save < w && star_y_save > 0 && star_y_save < h && test) {
                context.lineWidth = (1 - star_color_ratio * star[i][2]) * 2;
                context.beginPath();
                context.moveTo(star_x_save, star_y_save);
                context.lineTo(star[i][3], star[i][4]);
                context.stroke();
                context.closePath();
            }
        }
        timeout = setTimeout('anim()', fps);
    }

    function startStarfield() {
        $('canvas').css('z-index', 0);
        let myCanvas = document.getElementById("starfield");
        myCanvas.style.zIndex = 8000;

        resize();
        anim();
    }

    function stopStarfield() {
        window.clearTimeout(timeout);
    }

    function resize() {
        w = parseInt((url.indexOf('w=') != -1) ? url.substring(url.indexOf('w=') + 2, ((url.substring(url.indexOf('w=') + 2, url.length)).indexOf('&') != -1) ? url.indexOf('w=') + 2 + (url.substring(url.indexOf('w=') + 2, url.length)).indexOf('&') : url.length) : get_screen_size()[0]);
        h = parseInt((url.indexOf('h=') != -1) ? url.substring(url.indexOf('h=') + 2, ((url.substring(url.indexOf('h=') + 2, url.length)).indexOf('&') != -1) ? url.indexOf('h=') + 2 + (url.substring(url.indexOf('h=') + 2, url.length)).indexOf('&') : url.length) : get_screen_size()[1]);
        x = Math.round(w / 2);
        y = Math.round(h / 2);
        z = (w + h) / 2;
        star_color_ratio = 1 / z;
        cursor_x = x;
        cursor_y = y;
        init();
    }
</script>
