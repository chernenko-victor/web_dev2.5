<style>
#holder 
{
    /* background: #333;
    color: #fff;
    font: 300 100.1% "Helvetica Neue", Helvetica, "Arial Unicode MS", Arial, sans-serif;
    left: 50%;
    margin: -240px 0 0 -320px; 
    position: absolute; 
    top: 50%; */
    height: 480px;
    width: 640px;
}
</style>

<script type="text/javascript" charset="utf-8">
            window.onload = function () {
                var r = Raphael("holder", 640, 480),
                    angle = 0;
                while (angle < 360) {
                    var color = Raphael.getColor();
                    (function (t, c) {
                        r.circle(320, 450, 20).attr({stroke: c, fill: c, transform: t, "fill-opacity": .4}).click(function () {
                            s.animate({transform: t, stroke: c}, 2000, "bounce");
                        }).mouseover(function () {
                            this.animate({"fill-opacity": .75}, 500);
                        }).mouseout(function () {
                            this.animate({"fill-opacity": .4}, 500);
                        });
                    })("r" + angle + " 320 240", color);
                    angle += 30;
                }
                Raphael.getColor.reset();
                var s = r.set();
                s.push(r.path("M320,240c-50,100,50,110,0,190").attr({fill: "none", "stroke-width": 2}));
                s.push(r.circle(320, 450, 20).attr({fill: "none", "stroke-width": 2}));
                s.push(r.circle(320, 240, 5).attr({fill: "none", "stroke-width": 10}));
                s.attr({stroke: Raphael.getColor()});
            };
        </script>
<div id="holder" style=""></div>