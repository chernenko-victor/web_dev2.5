<script src="./inc/svg/curve.min.js"></script>
<script src="./inc/svg/graph_partit.v11.js"></script>
<script src="./inc/svg/tool.v1.js"></script>

<canvas id="myCanvas" width="600" height="400" style="border:1px solid #000000"></canvas>

<div class="fixed" id="fixed"><div class="close"><a href="#" onclick="hide(); return false;">X</a></div>
<br /><br /><strong>Преобладающие частоты</strong><br />Цветовое кодирование. Горячие цвета - низкая частота, холодные - высокая.<br /><br />
<strong>Широта спектра</strong><br />Интенсивность цвета: чем более узкий частотный диапазон, тем более насыщенный цвет.<br /><br />
<strong>Изменение тембра</strong><br />Градиент
</div>
  <div class="quest_sign"  id="quest_sign"><a href="#" onclick="show(); return false;">?</a></div>

<script>

/* ======================================================================================
=====================                      example 1               ======================
========================================================================================= */
var canvas, ctx, w, h, pts;

canvas = document.getElementById('myCanvas');
ctx = canvas.getContext("2d");
w = canvas.width;
h = canvas.height;

var currentDepth, depth;
var aCoordFrame;

currentDepth = 0;
depth = 2;

var RndParamStruct=[];

RndParamStruct[0]=[];
RndParamStruct[0][0] = 3.9; //numOfPointsAngl
RndParamStruct[0][1] = 0.59; //numOfPointsDepthOffset
RndParamStruct[0][2] = 2;  //numOfPointsOffset

RndParamStruct[1]=[];
RndParamStruct[1][0] = 2; //DistrType
RndParamStruct[1][1] = 105.9; //DispersionAngl
RndParamStruct[1][2] = 1.0;  //DispersionOffset

GenerateDivision(ctx, 0, 0, w, h, RndParamStruct);
GenerateDynamic(ctx);


aCoordFrame = {
    ctx:ctx
  , xB:0
  , yB:0
  , xE:w
  , yE:h
  , hResolut:50
  , vResolut:100
  , hUnit:"sec"
  , vUnit:null
  , vUnitB:0
  , vUnitE:100
};
SetCoordFrame(aCoordFrame);

</script>