$(function () {
    $("body").prepend('<canvas id="drawing_surface" style="position:absolute;top:0;left:0;"></canvas>')
    var cnvs = document.getElementById("drawing_surface");
    var ctx = cnvs.getContext("2d");
    
    var resetCanvas = function() {
        ctx.canvas.width = $("body").width();
        ctx.canvas.height = $(document).height();
    }
    
    $(window).resize(function() {
        resetCanvas();
    });
})