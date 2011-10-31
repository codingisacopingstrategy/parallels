jQuery(document).ready(function($) {
(function($) {
    $.fn.lineTo = function(e) {
        var Points = function(element) {
/* Constructor that takes a dom element,
    returns an object with access to the following coordinates:
    
    --------top--------
    |                 |
    left          right
    |                 |
    ------bottom-------
    
    each method returns an array [x,y]
    */
            this.element = element;

            this.top = function() {
                var x = $(this.element).offset()['left'] + Math.floor($(this.element).width() / 2);
                var y = $(this.element).offset()['top'];
                return [x, y];
            };

            this.right = function() {
                var x = $(this.element).offset()['left'] + $(this.element).width();
                var y = $(this.element).offset()['top'] + Math.floor($(this.element).height() / 2);
                return [x, y];
            };

            this.left = function() {
                var x = $(this.element).offset()['left'];
                var y = $(this.element).offset()['top'] + Math.floor($(this.element).height() / 2);
                return [x, y];
            };

            this.bottom = function() {
                var x = $(this.element).offset()['left'] + Math.floor($(this.element).width() / 2);
                var y = $(this.element).offset()['top'] + $(this.element).height();
                return [x, y];
            };
        };

        var drawLine = function(xyfrom, xyto) {
            ctx.beginPath();
            ctx.moveTo(xyfrom[0], xyfrom[1]);
            ctx.lineTo(xyto[0], xyto[1]);
            ctx.strokeStyle = "#000";
            ctx.stroke();
        }

        var fromTo = function(from, to) {

            // determine which of the two elements is on the left
            //if ($(from).offset()['left'] < $(to).offset()['left']) {
                var left = new Points(from);
                var right = new Points(to);
            //}
           // else {
                var right = new Points(from);
                var left = new Points(to);
           // }

            // determine from which side to which side to draw
            if (left.bottom()[1] > right.top()[1]) {
                drawLine(left.bottom(), right.top());
            }
            else if (left.top()[1] < right.bottom()[1]) {
                drawLine(left.top(), right.bottom());
            }
            else {
                drawLine(left.right(), right.left());
            }
        }
    fromTo(this, e)
    };
})(jQuery);

    var cnvs = document.getElementById("drawing_surface");
    var ctx = cnvs.getContext("2d");
        ctx.canvas.width = $("body").width();
        ctx.canvas.height = $(document).height();
});