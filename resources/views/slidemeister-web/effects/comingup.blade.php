<canvas id="comingup" width="1920" height=1080></canvas>

<script>

    function drawComingup(offset) {
        $('canvas').css('z-index', 0);
        let myCanvas = document.getElementById("comingup");
        myCanvas.style.zIndex = 8000;
        let drawingContext = myCanvas.getContext("2d");

        let color1 = "#87c762", color2 = "#97cd78";
        let numberOfStripes = 50;
        for (let i = 0; i < numberOfStripes * 2; i++) {
            let thickness = 80;
            drawingContext.beginPath();
            drawingContext.strokeStyle = i % 2 ? color1 : color2;
            drawingContext.lineWidth = thickness;
            drawingContext.lineCap = 'round';

            drawingContext.moveTo(offset + i * thickness + thickness / 2, 0);
            drawingContext.lineTo(offset + i * thickness + thickness / 2 - 300, 1080);
            drawingContext.stroke();
        }
    }

    let scrollOffsetComingUp = 0;

    let comingUpFrame;


    function animateComingup() {
        comingUpFrame = requestAnimFrame(animateComingup);

        scrollOffsetComingUp -= 1;
        if (scrollOffsetComingUp < -80) {
            scrollOffsetComingUp = 0;
        }

        drawComingup(scrollOffsetComingUp);
    }

    function startComingup() {
        animateComingup();
    }

    function stopComingup() {
        window.cancelAnimationFrame(comingUpFrame);
    }


</script>
