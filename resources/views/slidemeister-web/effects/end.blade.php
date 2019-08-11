<canvas id="end" width="1920" height=1080></canvas>

<script>

    function drawEnd(offset) {
        $('canvas').css('z-index', 0);
        let myCanvas = document.getElementById("end");
        myCanvas.style.zIndex = 8000;
        let drawingContext = myCanvas.getContext("2d");

        let color1 = "#ef4a82", color2 = "#e56697";
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

    let scrollOffset = 0;

    let endFrame;


    function animateEnd() {
        endFrame = requestAnimFrame(animateEnd);

        scrollOffset -= 1;
        if (scrollOffset < -80) {
            scrollOffset = 0;
        }

        drawEnd(scrollOffset);
    }

    function startEnd() {
        animateEnd();
    }

    function stopEnd() {
        window.cancelAnimationFrame(endFrame);
    }


</script>
