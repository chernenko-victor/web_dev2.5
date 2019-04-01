<!-- start clean_block 1 --><!-- end clean_block 1 -->

<!-- start table_header -->
<script type="text/javascript" charset="utf-8">
$(function () {
    // Grab the data
    var data = [],
        axisx = [],
        axisy = [],
        table = $("#for-chart");
    $("tbody td", table).each(function (i) {
        data.push(parseFloat($(this).text(), 10));
    });
    table.hide();
    $("tbody th", table).each(function () {
        axisy.push($(this).text());
    });
    $("tfoot th", table).each(function () {
        axisx.push($(this).text());
    });
    // Draw
    var width = 800,
        height = 300,
        leftgutter = 30,
        bottomgutter = 20,
        r = Raphael("chart", width, height),
        txt = {"font": '10px Fontin-Sans, Arial', stroke: "none", fill: "#fff"},
        X = (width - leftgutter) / axisx.length,
        Y = (height - bottomgutter) / axisy.length,
        color = $("#chart").css("color");
        max = Math.round(X / 2) - 1;
    // r.rect(0, 0, width, height, 5).attr({fill: "#000", stroke: "none"});
    for (var i = 0, ii = axisx.length; i < ii; i++) {
        r.text(leftgutter + X * (i + .5), 294, axisx[i]).attr(txt);
    }
    for (var i = 0, ii = axisy.length; i < ii; i++) {
        r.text(10, Y * (i + .5), axisy[i]).attr(txt);
    }
    var o = 0;
    for (var i = 0, ii = axisy.length; i < ii; i++) {
        for (var j = 0, jj = axisx.length; j < jj; j++) {
            var R = data[o] && Math.min(Math.round(Math.sqrt(data[o] / Math.PI) * 4), max);
            if (R) {
                (function (dx, dy, R, value) {
                    var color = "hsb(" + [(1 - R / max) * .5, 1, .75] + ")";
                    var dt = r.circle(dx + 60 + R, dy + 10, R).attr({stroke: "none", fill: color});
                    if (R < 6) {
                        var bg = r.circle(dx + 60 + R, dy + 10, 6).attr({stroke: "none", fill: "#000", opacity: .4}).hide();
                    }
                    var lbl = r.text(dx + 60 + R, dy + 10, data[o])
                            .attr({"font": '10px Fontin-Sans, Arial', stroke: "none", fill: "#fff"}).hide();
                    var dot = r.circle(dx + 60 + R, dy + 10, max).attr({stroke: "none", fill: "#000", opacity: 0});
                    dot[0].onmouseover = function () {
                        if (bg) {
                            bg.show();
                        } else {
                            var clr = Raphael.rgb2hsb(color);
                            clr.b = .5;
                            dt.attr("fill", Raphael.hsb2rgb(clr).hex);
                        }
                        lbl.show();
                    };
                    dot[0].onmouseout = function () {
                        if (bg) {
                            bg.hide();
                        } else {
                            dt.attr("fill", color);
                        }
                        lbl.hide();
                    };
                })(leftgutter + X * (j + .5) - 60 - R, Y * (i + .5) - 10, R, data[o]);
            }
            o++;
        }
    }
});
</script>
 <style type="text/css" media="screen">
    #chart {
        /*
        color: #333;
        left: 50%;
        margin: -150px 0 0 -400px;
        position: absolute;
        top: 50%;
        */
        width: 300px;
        width: 800px;
    }
</style>
<table id="for-chart">
    <tfoot>
        <tr>
            <td>&nbsp;</td>
            <th>12am</th>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6</th>
            <th>7</th>
            <th>8</th>
            <th>9</th>
            <th>10</th>
            <th>11</th>
            <th>12pm</th>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
            <th>6</th>
            <th>7</th>
            <th>8</th>
            <th>9</th>
            <th>10</th>
            <th>11</th>
        </tr>
    </tfoot>
    <tbody>
<!-- end table_header -->

<!-- start table_group -->

<!-- end table_group -->

<!-- start table_row 1 -->
 <tr>
<th scope="row">Begin</th>
<!-- start table_cols 1 -->
<td>{num1}</td>
<td>{num2}</td>
<td>{num3}</td>
<td>{num4}</td>
<td>{num5}</td>
<td>{num6}</td>
<td>{num7}</td>
<td>{num8}</td>
<td>{num9}</td>
<td>{num10}</td>
<td>{num11}</td>
<td>{num12}</td>
<td>{num13}</td>
<td>{num14}</td>
<td>{num15}</td>
<td>{num16}</td>
<td>{num17}</td>
<td>{num18}</td>
<td>{num19}</td>
<td>{num20}</td>
<td>{num21}</td>
<td>{num22}</td>
<td>{num23}</td>
<td>{num24}</td>
<!-- end table_cols 1 -->
</tr>
<!-- end table_row -->

<!-- start table_footer -->
</tbody>
</table>
<div id="chart"></div>
<!-- end table_footer -->

<!-- start table_empty -->
<br />
<!-- end table_empty -->