<canvas id="comingup" width="1920" height=1080></canvas>

<script>

    function drawComingup(offset) {
        $('canvas').css('z-index', 0);
        var myCanvas = document.getElementById("comingup");
        myCanvas.style.zIndex = 8000;
        var drawingContext = myCanvas.getContext("2d");

        var color1 = "#87c762", color2 = "#97cd78";
        var numberOfStripes = 50;
        for (var i = 0; i < numberOfStripes * 2; i++) {
            var thickness = 80;
            drawingContext.beginPath();
            drawingContext.strokeStyle = i % 2 ? color1 : color2;
            drawingContext.lineWidth = thickness;
            drawingContext.lineCap = 'round';

            drawingContext.moveTo(offset + i * thickness + thickness / 2, 0);
            drawingContext.lineTo(offset + i * thickness + thickness / 2 - 300, 1080);
            drawingContext.stroke();
        }
    }

    var scrollOffset = 0;

    var comingUpFrame;


    function animateComingup() {
        comingUpFrame = requestAnimFrame(animateComingup);

        scrollOffset -= 1;
        if (scrollOffset < -80) {
            scrollOffset = 0;
        }

        drawComingup(scrollOffset);
    }

    function startComingup() {
        animateComingup();
    }

    function stopComingup() {
        window.cancelAnimationFrame(comingUpFrame);
    }


</script>
