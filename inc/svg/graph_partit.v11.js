/* ======================================================================================
=====================                      lib               ============================
========================================================================================= */

/*
var iOldMin, iOldMax, iNewMin, iNewMax;
//iOldMin = Number.MAX_VALUE;
iCurrMin = Infinity;
iCurrMax = -Infinity;
iNewMin = Infinity;
iNewMax = -Infinity;
*/
var aPtsAll;

function logColorStopArrayElements(element, index, array, thisArg) 
{
  console.log('point: a[[0]' + index + '] = ' + element[0]);
  console.log('read: a[[1]' + index + '] = ' + element[1]);
  console.log('green: a[[2]' + index + '] = ' + element[2]);
  console.log('blue: a[[3]' + index + '] = ' + element[3]);
  console.log('opacity: a[[4]' + index + '] = ' + element[4]);
}

function GenerateAddColorStop(numOfPoints, FigureType, Dispersion) 
{
  var max_color = 255;
  var aPoint;
  var aColorStop = [];
  for(var i = 0; i < numOfPoints; i++) 
  {
      aPoint = [];
      aPoint.push(Math.random()); //point
      //console.log('aPoint[' + (i) + '] = ' + aPoint[0]);
      for(var j = 0; j < 3; j++) 
      {
        aPoint.push((max_color * Math.random() * 0.9)|0); //rgb color
        //console.log('aPoint[' + (j+1) + '] = ' + aPoint[0]);
      }
      aPoint.push(Math.random()); //opacity
      aColorStop.push(aPoint);
  }
  return aColorStop;
}


function logArrayElements(element, index, array) {
  console.log('a[' + index + '] = ' + element);
}

function GeneratePitch(BeginX, BeginY, width, height, numOfPoints, FigureType, Dispersion, aGrad) 
{
  //var numOfPoints = 7; //min. 2
  var aPoint = [];
  
  if(FigureType==1) //random line constant width
  {
    for(var i = 0; i < numOfPoints; i++) 
    {
      x = BeginX + ((width * Math.random() * 0.9 + width * 0.05)|0);
      y = BeginY + ((height * Math.random() * 0.9 + height * 0.05)|0);
      aPoint.push(x, y);
    }
  }
  else if(FigureType==2) //random line var width
  {
    var x;
    var y;
    var delta;
    for(var i = 0, j = numOfPoints; i < j; i++, j--) 
    {
      if((i%2)==0)
      {
        x = BeginX + ((width * Math.random() * 0.9 + width * 0.05)|0);
        y = BeginY + ((height * Math.random() * 0.9 + height * 0.05)|0);
        delta = ((width/Dispersion+height/Dispersion)/2) * Math.random() * 0.9;
        aPoint[i] = x;
        aPoint[i+1] = y;
        //console.log('a[' + i + '] = ' + x);
        //console.log('a[' + (i+1) + '] = ' + y);
      }
      else
      {
        //aPoint[numOfPoints-i] = y;
        //aPoint[numOfPoints-i-1] = x;
        aPoint[j] = y+delta;
        aPoint[j-1] = x+delta;
        //console.log('a[' + j + '] = ' + y);
        //console.log('a[' + (j-1) + '] = ' + x);
      }
    }
    //aPoint.forEach(logArrayElements);
  }
  if(aGrad)
  {
      for(var i = 0; i < 2; i++) 
      {
        x = BeginX + ((width * Math.random() * 0.9 + width * 0.05)|0);
        y = BeginY + ((height * Math.random() * 0.9 + height * 0.05)|0);
        aGrad.push(x, y);
      }
  }
  return aPoint;
}


function drawCurveDemo(Context, BeginX, BeginY, width, height, Tension, Segments, Closed, Fill, Stroke, aGrd, aColorStop)
{
    //Context.clearRect(BeginX, BeginY, width, height);

        // get current options
        //var o = new curveOptions();

    // draw our cardinal spline
        Context.beginPath();
        Context.moveTo(pts[0], pts[1]);
        //Context.curve(pts, o.tension, o.segments, o.closed);
        Context.curve(pts, Tension, Segments, Closed);
        if (Closed && !Fill) Context.closePath();
        if (Fill) 
        {
            var xBeginGrd, yBeginGrd, xEndGrd, yEndGrd;
            if(aGrd)
            {
              //console.log("aGrd");
              xBeginGrd = aGrd[0];
              yBeginGrd = aGrd[1];
              xEndGrd = aGrd[2];
              yEndGrd = aGrd[3];
            }
            else
            {
              xBeginGrd = 0;
              yBeginGrd = 0;
              xEndGrd = width;
              yEndGrd = height;
            }
            //var grd=Context.createLinearGradient(0, 1, 570, 400);
            var grd=Context.createLinearGradient(xBeginGrd, yBeginGrd, xEndGrd, yEndGrd);
            if(aColorStop)
            {
              //console.log("aColorStop");
              for (index = 0; index < aColorStop.length; ++index) 
              {
                grd.addColorStop(aColorStop[index][0],"rgba("+aColorStop[index][1]+","+aColorStop[index][2]+","+aColorStop[index][3]+","+aColorStop[index][4]+")");
              }
            }
            else
            {
              grd.addColorStop(0,"rgba(255,0,0,0.9)");
              grd.addColorStop(0.5,"rgba(0,255,0,0.6)");
              grd.addColorStop(1,"rgba(0,0,255,0.3)");
            }
            Context.fillStyle=grd;
            //Context.fillStyle = '#b7b7b7';
            Context.fillStyle = grd;
            Context.fill();
        }
        if(Stroke)
        {
          Context.strokeStyle = '#6677cc';
          Context.lineWidth = 1;
          Context.stroke();
        }
}


//function GenerateDivision(Context, BeginX, BeginY, EndX, EndY)
function GenerateDivision(Context, BeginX, BeginY, width, height, RndParamStruct)
{
  var numOfPointsAngl, numOfPointsDepthOffset, numOfPointsOffset;
  var DistrType, DispersionAngl, DispersionOffset;
  
  numOfPointsAngl = 5.9;
  numOfPointsDepthOffset = 0.8;
  numOfPointsOffset = 5;
  
  DistrType = 3;
  DispersionAngl = 89.9;
  DispersionOffset = 9.1;
  
  
  if(typeof(RndParamStruct) != 'undefined')
  {
    if(typeof(RndParamStruct[0]) != 'undefined')
    {
      numOfPointsAngl = RndParamStruct[0][0];
      numOfPointsDepthOffset = RndParamStruct[0][1];
      numOfPointsOffset = RndParamStruct[0][2]; 
    }
    
    if(typeof(RndParamStruct[1]) != 'undefined')
    {
      DistrType = RndParamStruct[1][0];
      DispersionAngl = RndParamStruct[1][1];
      DispersionOffset = RndParamStruct[1][2];
    }
  }
  
  var x, y;
  //width = EndX - BeginX;
  //height = EndY - BeginY;
  
  var colorStopPts;
  var gradient;

  pts = [];
  colorStopPts = [];
  gradient = [];

  if(currentDepth==0)
  {
    aPtsAll = [];
    Context.clearRect(BeginX, BeginY, width, height);
  }

  //numOfPoints = ((7 * Math.random() * 0.9 / (currentDepth+1))|0)+7;
  numOfPoints = ((numOfPointsAngl/(currentDepth+numOfPointsDepthOffset) * Math.random())|0)+numOfPointsOffset;
  
  
  FigureType = ((2 * Math.random() * 0.9)|0)+1;
  
  
  if(DistrType==1)
  {
    DispersionDistr = LinRnd();
  }
  else if(DistrType==2)
  {
    DispersionDistr = Math.random();
  }
  else
  {
    DispersionDistr = (1-LinRnd());
  }
  Dispersion = ((DispersionAngl * DispersionDistr + DispersionOffset)|0) 
  if(FigureType==2) 
  {
    Dispersion = Math.abs((DispersionAngl + DispersionOffset)*.9 - Dispersion);
  }
  

  pts = GeneratePitch(BeginX, BeginY, width, height, numOfPoints /*14*/, FigureType /*2*/, Dispersion /* 5 */, /* gradient */ gradient); 
  colorStopPts = GenerateAddColorStop(/*numOfPoints*/ 5, /*FigureType*/ 3, /*Dispersion*/ 5);
  drawCurveDemo(Context, BeginX, BeginY, width, height, /*Tension*/ 1, /*Segments*/ 30, /*Closed*/ true, /*Fill*/ true, /*Stroke*/ false, /* gradient */ gradient, /* color stop */ colorStopPts);
  
  //save current point array
  aPtsAll.push(pts);

  x = ((width * Math.random() * 0.9)|0)+BeginX;
  y = ((height * Math.random() * 0.9)|0)+BeginY;
  octant = ((4 * Math.random() * 0.9)|0);
  ++currentDepth;
  
  //console.log("width = "+width);
  //console.log("height = "+height);
  //console.log("x = "+x);
  //console.log("y = "+y);
  //console.log("octant = "+octant);
  //console.log("currentDepth = "+currentDepth);
  //console.log("depth = "+depth);
  //console.log("FigureType = "+FigureType);
  //console.log("Dispersion = "+Dispersion);
  
  if((octant >= 0) && (octant < 1)) //octant I
  {
    NewBeginX = BeginX;
    NewBeginY = BeginY;
    NewW = x;
    NewH = y;
  }
  else if((octant >= 1) && (octant < 2)) //octant II
  {
    NewBeginX = x;
    NewBeginY = BeginY;
    NewW = width - x;
    NewH = y;
  }
  else if((octant >= 2) && (octant < 3)) //octant III
  {
    NewBeginX = BeginX;
    NewBeginY = BeginY;
    NewW = width - x;
    NewH = height - y;
  }
  else if((octant >= 3) && (octant < 4)) //octant IV
  {
    NewBeginX = BeginX;
    NewBeginY = y;
    NewW = x;
    NewH = height - y;
  }
    
  if(currentDepth < depth)
  {
    GenerateDivision(Context, NewBeginX, NewBeginY, NewW, NewH);
  }
}

function LinRnd()
{
var x, y, lin;
  x = Math.random();
  y = Math.random();
  if(x > y)
  {
    lin = x;
  }
  else
  {
    lin = y;
  }
  return lin;
}

function arrayMin(arr) {
  var len = arr.length, min = Infinity;
  while (len--) {
    if (Number(arr[len]) < min) {
      min = Number(arr[len]);
    }
  }
  return min;
};

function arrayMax(arr) {
  var len = arr.length, max = -Infinity;
  while (len--) {
    if (Number(arr[len]) > max) {
      max = Number(arr[len]);
    }
  }
  return max;
};

function GetDynamicArea()
{
  //console.log("======================== GetDynamicArea() ========================");
  var len = aPtsAll.length;
  var iMin, iMax;
  //var aRegion;
  var aRegion = [];
  while (len--) 
  {
    //console.log("======== new region ========== ");
    var lenPts = aPtsAll[len].length;
    var PtsXOnly = [];
    while (lenPts--) 
    {
      //console.log("lenPts = "+lenPts+" lenPts%2 = "+(lenPts%2));
      //console.log("aPtsAll["+len+"]["+lenPts+"] = "+aPtsAll[len][lenPts]);
      if(lenPts%2==0) //x is in 0, 2, 4, ... indicies!!!
      {
        PtsXOnly.push(aPtsAll[len][lenPts]);
      }
    }
    aRegionOne = [];
    iMin = arrayMin(PtsXOnly);
    aRegionOne.push(iMin);
    iMax = arrayMax(PtsXOnly);
    aRegionOne.push(iMax);
    //console.log("iMin = "+iMin);
    //console.log("iMax = "+iMax);
    aRegion.push(aRegionOne);
  }
  
  len = aRegion.length;
  //while (len--) 
  //{
  //  console.log("aRegion["+len+"] = ["+aRegion[len][0]+", "+aRegion[len][1]+"]");
  //}
  
  ProcessRegionArray(aRegion);
  
  len = aRegion.length;
  while (len--) 
  {
    //console.log("aRegion["+len+"] = ["+aRegion[len][0]+", "+aRegion[len][1]+"]");
  }
  
  return aRegion;
}

function ProcessRegionArray(aRegion)
{
  //console.log("======================== ProcessRegionArray(aRegion) ========================");

  //======================================= BEGIN DeleteRegion func ===========================================
  DeleteRegion(aRegion);
  //======================================= END DeleteRegion func ===========================================
  
  //======================================= BEGIN UniteRegion func ===========================================
  UniteRegion(aRegion);
  //======================================= END UniteRegion func ===========================================
  
  //len = aRegion.length;
  //while (len--) 
  //{
  //  console.log("aRegion["+len+"] = ["+aRegion[len][0]+", "+aRegion[len][1]+"]");
  //}
}

function UniteRegion(aRegion2Unite)
{
  //console.log("======================== UniteRegion ========================");
  var found = 0;
  var a2Unite = [];
  len = aRegion2Unite.length;
  while (len--) 
  {
    //test if (RegionOne intersect with other RegionOne) then (unite two)
    if((len-1)<0) break;
    len2 = len;
    while (len2--) 
    {
          //console.log("aRegion2Unite[len][0] = "+aRegion2Unite[len][0]);
          //console.log("aRegion2Unite[len][1] = "+aRegion2Unite[len][1]);
          //console.log("aRegion2Unite[len2][0] = "+aRegion2Unite[len2][0]);
          //console.log("aRegion2Unite[len2][1] = "+aRegion2Unite[len2][1]);
      if(
          (aRegion2Unite[len][0]<=aRegion2Unite[len2][0]) && (aRegion2Unite[len][1]<=aRegion2Unite[len2][1]) && (aRegion2Unite[len2][0]<=aRegion2Unite[len][1])
        )
      {
        a2Unite.push([len, len2]);
          //console.log("|| a2Unite [len, len2] = " + len + ", " + len2);
          //console.log("aRegion2Unite[len][0] = "+aRegion2Unite[len][0]);
          //console.log("aRegion2Unite[len][1] = "+aRegion2Unite[len][1]);
          //console.log("aRegion2Unite[len2][0] = "+aRegion2Unite[len2][0]);
          //console.log("aRegion2Unite[len2][1] = "+aRegion2Unite[len2][1]);
          //console.log("break");
        found = 1;
        break;
      }
      if(
          (aRegion2Unite[len][0]>=aRegion2Unite[len2][0]) && (aRegion2Unite[len][1]>=aRegion2Unite[len2][1]) && (aRegion2Unite[len][0]<=aRegion2Unite[len2][1])
        )
      {
        a2Unite.push([len2, len]);
          //console.log("|| a2Unite [len2, len] = " + len2 + ", " + len);
        
          //console.log("aRegion2Unite[len][0] = "+aRegion2Unite[len][0]);
          //console.log("aRegion2Unite[len][1] = "+aRegion2Unite[len][1]);
          //console.log("aRegion2Unite[len2][0] = "+aRegion2Unite[len2][0]);
          //console.log("aRegion2Unite[len2][1] = "+aRegion2Unite[len2][1]);
          //console.log("break");
        found = 1;
        break;
      }
    }
    if(found == 1)
    {
      break;
    }
  }
  
  if(found == 1)
  {
    len = a2Unite.length;
    var iBeginRegionIndex, iEndRegionIndex;
    var iBeginNewRegion, iEndNewRegion;
    while (len--) 
    {
      iBeginRegionIndex=a2Unite[len][0];
      iEndRegionIndex=a2Unite[len][1];
      iBeginNewRegion=aRegion2Unite[iBeginRegionIndex][0];
      iEndNewRegion=aRegion2Unite[iEndRegionIndex][1];//ERROR THERE TypeError: aRegion[iEndRegionIndex] is undefined
      aRegion2Unite.splice(iBeginRegionIndex, 1, [iBeginNewRegion, iEndNewRegion]);
      aRegion2Unite.splice(iEndRegionIndex, 1);
    }
    UniteRegion(aRegion2Unite);
  }
  
  //test for delete again there!!!!!!!
  DeleteRegion(aRegion2Unite);
}

function DeleteRegion(aRegion2delete)
{
  //console.log("======================== DeleteRegion ========================");
  var a2Delete = [];
  //var a2Unite = [];
  var len, len2;
  len = aRegion2delete.length;

  while (len--) 
  {
    //test if (RegionOne enter in other RegionOne) then (delete first)
    if((len-1)<0) break;
    len2 = len;
    while (len2--) 
    {
      if
        (
          (aRegion2delete[len][0]>=aRegion2delete[len2][0]) && (aRegion2delete[len][1]<=aRegion2delete[len2][1])
        )
      {
        //delete aRegion2delete[len]
        a2Delete.push(len);
        //console.log("|| a2Delete = " + len);
        //console.log("aRegion2delete[len][0] = "+aRegion2delete[len][0]);
        //console.log("aRegion2delete[len][1] = "+aRegion2delete[len][1]);
        //console.log("aRegion2delete[len2][0] = "+aRegion2delete[len2][0]);
        //console.log("aRegion2delete[len2][1] = "+aRegion2delete[len2][1]);
      }
      if
        (
          (aRegion2delete[len][0]<=aRegion2delete[len2][0]) && (aRegion2delete[len][1]>=aRegion2delete[len2][1])
        )
      {
        //delete aRegion2delete[len2]
        a2Delete.push(len2);
        //console.log("|| a2Delete = " + len);
        //console.log("aRegion2delete[len][0] = "+aRegion2delete[len][0]);
        //console.log("aRegion2delete[len][1] = "+aRegion2delete[len][1]);
        //console.log("aRegion2delete[len2][0] = "+aRegion2delete[len2][0]);
        //console.log("aRegion2delete[len2][1] = "+aRegion2delete[len2][1]);
      }
    }
  }
  
  //console.log("aRegion2delete BEFORE Delete = "+ aRegion2delete.length);
  
  len = a2Delete.length;
  while (len--) 
  {
    index = a2Delete[len];
    aRegion2delete.splice(index,1);
  }
  
  //console.log("aRegion2delete AFTER Delete = "+ aRegion2delete.length);
}

function GenerateDynamic(CurrentContext) 
{
   //console.log("======================== GenerateDynamic() ========================");
  var aRegion = []; 
  var len;
  var RegSymbolLen = 30;
  var RegSymbolHeight = 30;
  var y = 20;
  var numOfPoints;
  var aDynamicSybmols = [];
  
  aRegion = GetDynamicArea();
  len = aRegion.length;
  var j = 0;
  while (len--) 
  {
    var aDynamicSybmolsInReg = [];
    //console.log("aRegion["+len+"] = ["+aRegion[len][0]+", "+aRegion[len][1]+"]");
    var RegionLen = aRegion[len][1]-aRegion[len][0];
    if(RegionLen<=RegSymbolLen)
    {
      numOfPoints = 1;
    }
    else
    {
      //all RegSymbolLen <= 30% from RegionLen
      //maximum: RegSymbolLen * numOfPoints = 0.3 * RegionLen
      var numOfPointsMax = 0.7 * RegionLen / RegSymbolLen;
      //console.log("RegionLen = "+RegionLen);
      //console.log("RegSymbolLen = "+RegSymbolLen);
      //console.log("numOfPointsMax = "+numOfPointsMax);
      numOfPoints = 1 + (((numOfPointsMax-1) * Math.random() * 0.9)|0);
    }
    
    var width = RegionLen/numOfPoints;
    if(width-RegSymbolLen<=0) 
    {
      numOfPoints = 1;
    }
    
    var DynamicSybmolsCurrentLen = aDynamicSybmols.length;
    
    for(var i = 0; i < numOfPoints; i++) 
    { 
      var BeginX = aRegion[len][0] + i*width;
      var x = BeginX + (((width-RegSymbolLen) * Math.random() * 0.9)|0);
      //var y = BeginY + ((height * Math.random() * 0.9 + height * 0.05)|0);
      //aPoint.push(x, y);
      //console.log("x = "+x);
      
      //if((i-1>=0))
      //{
        //console.log("aDynamicSybmolsInReg[i-1] = "+aDynamicSybmolsInReg[i-1]);
        //console.log("RegSymbolLen = "+RegSymbolLen);
        //tmp = aDynamicSybmolsInReg[i-1]+RegSymbolLen;
        //console.log("aDynamicSybmolsInReg[i-1]+RegSymbolLen = "+tmp);
      //}
      
      if((i-1>=0)&&(x<aDynamicSybmolsInReg[i-1]+RegSymbolLen))
      {
        x = aDynamicSybmolsInReg[i-1] + RegSymbolLen + 1;
      }
      
      if((j-1>=0) && (x < aDynamicSybmols[j-1][0] + RegSymbolLen))
      {
        x = aDynamicSybmols[j-1][0] + RegSymbolLen + 1;
      }
      
      //========================================= new new new
      if(DynamicSybmolsCurrentLen>0)
      {
        var k = DynamicSybmolsCurrentLen;
        while (k--) 
        {
          var tmpLen = aDynamicSybmols[k].length;
          var m = tmpLen;
          var isIntersect = 0;
          while (m--) 
          {
            console.log("DynamicSybmols["+k+"]["+m+"] = "+aDynamicSybmols[k][m]);
            if(x + RegSymbolLen <= aDynamicSybmols[k][m])
            {
              isIntersect = 1;
              break;
            }
          }
          if(isIntersect==1)
          {
            break;
          }
        }
        if(isIntersect==0)
        {
          //console.log("NO INTERSECT x = "+x);
          aDynamicSybmolsInReg.push(x);
        }
        else
        {
          //console.log("INTERSECT DETECTED x = "+x);
        }
      } 
      else
      {
        //console.log("x = "+x);
        aDynamicSybmolsInReg.push(x);
        /*
        var m = aDynamicSybmolsInReg.length;
        while (m--) 
        {
          //DynamicSybmols[k][m];
          console.log("aDynamicSybmolsInReg["+k+"]["+m+"] = "+aDynamicSybmolsInReg[m]);
        }
        */
      }
      //========================================= new new new
      
      
      //DrawDynamicSymbol(CurrentContext, x, y, RegSymbolLen, RegSymbolHeight);
    }
    aDynamicSybmols.push(aDynamicSybmolsInReg);
    j++;
  }
  //2DO ====================================
  //2DO maybe in array something else then in x???
  DrawAllDynamicSymbol(CurrentContext, x, y, RegSymbolLen, RegSymbolHeight, aDynamicSybmols);
}

function DrawDynamicSymbol(Context, x, y, RegSymbolLen, RegSymbolHeight) //aDynamicSybmols
{
  /*
  var Pic1BaseX = x;
  var Pic1BaseY = y;
  Context.beginPath();
  Context.moveTo(Pic1BaseX+0, Pic1BaseY+0);
  Context.lineTo(Pic1BaseX+RegSymbolLen, Pic1BaseY+0);
  Context.lineTo(Pic1BaseX+RegSymbolLen, Pic1BaseY+RegSymbolHeight);
  Context.lineTo(Pic1BaseX+0, Pic1BaseY+RegSymbolHeight);
  Context.lineTo(Pic1BaseX+0, Pic1BaseY+0);
  Context.lineWidth = 1;
  Context.strokeStyle = '#000000';
  Context.stroke();
  */
  
  LineType = Math.floor((4 * Math.random()) + 1);
  console.log("LineType = " + LineType);
  
  LineSubType = Math.floor((2 * Math.random()) + 1);
  console.log("LineSubType = " + LineSubType);
  
  type = 1;
  BeginLvl = 30;
  EndLvl = 30;
  
  
  if(LineType == 1) // Const
  {
    if(LineSubType == 1) // Full
    {
      type = 1;
      BeginLvl = 30;
      EndLvl = 30;
    }
    else if(LineSubType == 2) // Abbrev
    {
      type = 2;
      BeginLvl = 30;
      EndLvl = 30;
    }
  }
  else if(LineType == 2) // Crescendo
  {
    if(LineSubType == 1) // Full
    {
      type = 1;
      BeginLvl = 20;
      EndLvl = 35;
    }
    else if(LineSubType == 2) // Abbrev
    {
      type = 2;
      BeginLvl = 20;
      EndLvl = 35;
    }
  }
  else if(LineType == 3) // Diminuendo 
  {
    if(LineSubType == 1) // Full
    {
      type = 1;
      BeginLvl = 35;
      EndLvl = 20;
    }
    else if(LineSubType == 2) // Abbrev
    {
      type = 2;
      BeginLvl = 35;
      EndLvl = 20;
    }
  }
  else if(LineType == 4) // Amplitude Vibrato
  {
    if(LineSubType == 1) // Full
    {
      type = 3;
      BeginLvl = 30;
      EndLvl = 30;
    }
    else if(LineSubType == 2) // Abbrev
    {
      type = 4;
      BeginLvl = 30;
      EndLvl = 30;
    }
  }
  
  aDynamicSybmolParam = {
      Context: Context
    , BeginX : x
    , BeginY : y
    , Len : RegSymbolLen
    , Height : RegSymbolHeight
    , type : type 
    , BeginLvl : BeginLvl
    , EndLvl : EndLvl
  };
  
  DrawDynamicSymbolInner(aDynamicSybmolParam);
}

function DrawAllDynamicSymbol(CurrentContext, x, y, RegSymbolLen, RegSymbolHeight, aDynamicSybmols)
{
  var len = aDynamicSybmols.length;
  var i = len;
  while (i--) 
  {
    //console.log("=========== aDynamicSybmols ================");
    var aDynamicSybmolsInReg = aDynamicSybmols[i];
    var lenInner = aDynamicSybmolsInReg.length;
    var j = lenInner;
    
    if((i+1<len)&&(aDynamicSybmols[i][j-1] + RegSymbolLen > aDynamicSybmols[i+1][0])) //это не последний регион
    {
      j--;
    }
    
    while (j--) 
    {
      //console.log("aDynamicSybmolsInReg["+j+"] = "+aDynamicSybmolsInReg[j]);
      DrawDynamicSymbol(CurrentContext, aDynamicSybmolsInReg[j], y, RegSymbolLen, RegSymbolHeight);
    }
  }
}

function SetCoordFrame(aCoordFrame)
{
  var ctx = aCoordFrame["ctx"];
  var xB = aCoordFrame["xB"];
  var yB = aCoordFrame["yB"];
  var xE = aCoordFrame["xE"];
  var yE = aCoordFrame["yE"];
  var hResolut = aCoordFrame["hResolut"];
  var vResolut = aCoordFrame["vResolut"];
  var hUnit = aCoordFrame["hUnit"];
  var vUnit = aCoordFrame["vUnit"];
  
  var curX = xB;
  var curY = yB;
  
  var hPostfix = "";
  var vPostfix = "";
  
  //ctx.strokeStyle="#FF0000";
  ctx.strokeStyle="#a0a0a0";
  ctx.lineWidth=1;
  
  ctx.font="11px Georgia";
 
  if(hUnit!=null)
  {
    hPostfix = hUnit;
  }
  if(vUnit!=null)
  {
    vPostfix = vUnit;
  }
  
  //set hor line  
  while(curY <= yE)
  {
    ctx.beginPath();
    ctx.moveTo(xB, curY);
    ctx.lineTo(xE, curY);
    ctx.stroke();
    ctx.strokeText(curY+" "+vPostfix, xB+4, curY+13);
    curY += hResolut;
  }
  
  //set vert line
  while(curX <= xE)
  {
    ctx.beginPath();
    ctx.moveTo(curX, yB);
    ctx.lineTo(curX, yE);
    ctx.stroke();
    ctx.strokeText(curX+" "+hPostfix, curX+4, yB+13);
    curX += vResolut;
  }
}


function GenerateDynamicTest(CurrentContext, aRegion) 
{
  //console.log("======================== GenerateDynamic() ========================");
  //var aRegion = []; 
  var len;
  var RegSymbolLen = 30;
  var RegSymbolHeight = 30;
  var y = 20;
  var numOfPoints;
  var aDynamicSybmols = [];
  
  //aRegion = GetDynamicArea();
  len = aRegion.length;
  var j = 0;
  while (len--) 
  {
    var aDynamicSybmolsInReg = [];
    //console.log("aRegion["+len+"] = ["+aRegion[len][0]+", "+aRegion[len][1]+"]");
    var RegionLen = aRegion[len][1]-aRegion[len][0];
    if(RegionLen<=RegSymbolLen)
    {
      numOfPoints = 1;
    }
    else
    {
      //all RegSymbolLen <= 30% from RegionLen
      //maximum: RegSymbolLen * numOfPoints = 0.3 * RegionLen
      var numOfPointsMax = 0.7 * RegionLen / RegSymbolLen;
      //console.log("RegionLen = "+RegionLen);
      //console.log("RegSymbolLen = "+RegSymbolLen);
      //console.log("numOfPointsMax = "+numOfPointsMax);
      numOfPoints = 1 + (((numOfPointsMax-1) * Math.random() * 0.9)|0);
    }
    
    var width = RegionLen/numOfPoints;
    if(width-RegSymbolLen<=0) 
    {
      numOfPoints = 1;
    }
    //console.log("numOfPoints = "+numOfPoints);
    for(var i = 0; i < numOfPoints; i++) 
    { 
      var BeginX = aRegion[len][0] + i*width;
      var x = BeginX + (((width-RegSymbolLen) * Math.random() * 0.9)|0);
      //var y = BeginY + ((height * Math.random() * 0.9 + height * 0.05)|0);
      //aPoint.push(x, y);
      //console.log("x = "+x);
      
      //if((i-1>=0))
      //{
        //console.log("aDynamicSybmolsInReg[i-1] = "+aDynamicSybmolsInReg[i-1]);
        //console.log("RegSymbolLen = "+RegSymbolLen);
        //tmp = aDynamicSybmolsInReg[i-1]+RegSymbolLen;
        //console.log("aDynamicSybmolsInReg[i-1]+RegSymbolLen = "+tmp);
      //}
      
      if((i-1>=0)&&(x<aDynamicSybmolsInReg[i-1]+RegSymbolLen))
      {
        x = aDynamicSybmolsInReg[i-1] + RegSymbolLen + 1;
      }
      
      if((j-1>=0) && (x < aDynamicSybmols[j-1][0] + RegSymbolLen))
      {
        x = aDynamicSybmols[j-1][0] + RegSymbolLen + 1;
      }
      
      aDynamicSybmolsInReg.push(x);
      //DrawDynamicSymbol(CurrentContext, x, y, RegSymbolLen, RegSymbolHeight);
    }
    aDynamicSybmols.push(aDynamicSybmolsInReg);
    j++;
  }
  //maybe in array something else then in x???
  DrawAllDynamicSymbol(CurrentContext, x, y, RegSymbolLen, RegSymbolHeight, aDynamicSybmols);
}

function drawCurve(Context, aPoint, Tension, Segments) /*BeginX, BeginY, width, height, Tension, Segments, Closed, Fill, Stroke, aGrd, aColorStop*/
{
  Context.beginPath();
  Context.moveTo(aPoint[0], aPoint[1]);
  Context.curve(aPoint, Tension, Segments, /* Closed */ 0);
}

function DrawBezier(aDynamicSybmolParam, iType)
{
  //iType
  //0 = full
  //1 = clear left
  //2 = clear right
      
  var Context = aDynamicSybmolParam.Context;
  NumOfPeriod = 2;
  //PeriodWidth = Math.ceil(aDynamicSybmolParam.Len / NumOfPeriod);
  PeriodWidth = aDynamicSybmolParam.Len / NumOfPeriod;
    //console.log('PeriodWidth = ' + PeriodWidth);
  BezierMiddleY = aDynamicSybmolParam.BeginY + aDynamicSybmolParam.BeginLvl / 2;
    //console.log('BezierMiddleY = ' + BezierMiddleY);
  BezierFullY = aDynamicSybmolParam.BeginY + aDynamicSybmolParam.BeginLvl;
    //console.log('BezierFullY = ' + BezierFullY);

  Context.moveTo(aDynamicSybmolParam.BeginX, BezierMiddleY);
    //console.log('Context.moveTo('+aDynamicSybmolParam.BeginX+', '+BezierMiddleY+')');
  for (i = 0; i <= NumOfPeriod; i=i+2) 
  {
    Context.bezierCurveTo(
        aDynamicSybmolParam.BeginX + i * PeriodWidth / 2, aDynamicSybmolParam.BeginY, 
        aDynamicSybmolParam.BeginX + (i + 1) * PeriodWidth / 2, aDynamicSybmolParam.BeginY, 
        aDynamicSybmolParam.BeginX + (i + 1) * PeriodWidth / 2, BezierMiddleY
    );    
    Context.bezierCurveTo(
        aDynamicSybmolParam.BeginX + (i + 1) * PeriodWidth / 2, BezierFullY, 
        aDynamicSybmolParam.BeginX + (i + 2) * PeriodWidth / 2, BezierFullY, 
        aDynamicSybmolParam.BeginX + (i + 2) * PeriodWidth / 2, BezierMiddleY
    );
  }

  /* */
  Context.lineTo(aDynamicSybmolParam.BeginX + aDynamicSybmolParam.Len, BezierMiddleY);
    //console.log('Context.lineTo(aDynamicSybmolParam.BeginX + aDynamicSybmolParam.Len = '+(aDynamicSybmolParam.BeginX + aDynamicSybmolParam.Len)+', BezierMiddleY = '+BezierMiddleY+');');
  
  if(iType != 2)
  {
    Context.lineTo(aDynamicSybmolParam.BeginX + aDynamicSybmolParam.Len, aDynamicSybmolParam.BeginY + aDynamicSybmolParam.Height);
      //console.log('iType != 2');
      //console.log('Context.lineTo(aDynamicSybmolParam.BeginX + aDynamicSybmolParam.Len = '+(aDynamicSybmolParam.BeginX + aDynamicSybmolParam.Len)+', aDynamicSybmolParam.BeginY + aDynamicSybmolParam.Height = '+(aDynamicSybmolParam.BeginY + aDynamicSybmolParam.Height)+');');
      //console.log('aDynamicSybmolParam.BeginY = '+aDynamicSybmolParam.BeginY);
      //console.log('aDynamicSybmolParam.Height = '+aDynamicSybmolParam.Height);
  }
  else
  {
    Context.moveTo(aDynamicSybmolParam.BeginX + aDynamicSybmolParam.Len, aDynamicSybmolParam.BeginY + aDynamicSybmolParam.Height);
  }
  
  Context.lineTo(aDynamicSybmolParam.BeginX, aDynamicSybmolParam.BeginY + aDynamicSybmolParam.Height);
  
  if(iType != 1)
  {
    Context.lineTo(aDynamicSybmolParam.BeginX, BezierMiddleY);
      //console.log('iType != 1');
      //console.log('Context.lineTo(aDynamicSybmolParam.BeginX = '+aDynamicSybmolParam.BeginX+', BezierMiddleY = '+BezierMiddleY+');');
  }
  else
  {
    Context.moveTo(aDynamicSybmolParam.BeginX, BezierMiddleY);
  }
  /* */
  
  Context.lineWidth = 1;
  Context.strokeStyle = '#000000';
  Context.stroke();

}

function DrawDynamicSymbolInner(aDynamicSybmolParam)
{
  var Context = aDynamicSybmolParam.Context;
  Context.beginPath();
  
  if(aDynamicSybmolParam.type==1)
  {
    Context.moveTo(aDynamicSybmolParam.BeginX, aDynamicSybmolParam.BeginY+(aDynamicSybmolParam.Height-aDynamicSybmolParam.BeginLvl));
    Context.lineTo(aDynamicSybmolParam.BeginX, aDynamicSybmolParam.BeginY+aDynamicSybmolParam.Height);
    Context.lineTo(aDynamicSybmolParam.BeginX+aDynamicSybmolParam.Len, aDynamicSybmolParam.BeginY+aDynamicSybmolParam.Height);
    Context.lineTo(aDynamicSybmolParam.BeginX+aDynamicSybmolParam.Len, aDynamicSybmolParam.BeginY+(aDynamicSybmolParam.Height-aDynamicSybmolParam.EndLvl));
    Context.lineTo(aDynamicSybmolParam.BeginX, aDynamicSybmolParam.BeginY+(aDynamicSybmolParam.Height-aDynamicSybmolParam.BeginLvl));
  }
  else if(aDynamicSybmolParam.type==2)
  {
    fLenPart = 1./3;
    x1 = aDynamicSybmolParam.BeginX+aDynamicSybmolParam.Len;
    y1 = aDynamicSybmolParam.BeginY+(aDynamicSybmolParam.Height-aDynamicSybmolParam.EndLvl);
    x2 = aDynamicSybmolParam.BeginX
    y2 = aDynamicSybmolParam.BeginY+(aDynamicSybmolParam.Height-aDynamicSybmolParam.BeginLvl)
    fA = (y1 - y2) / (x1 - x2) ;
    fB = y1 - fA * x1;
    
    //a = (y1 - y2)/(x1 - x2) 
    //b = y1 - a x1
    
    Context.moveTo(aDynamicSybmolParam.BeginX, aDynamicSybmolParam.BeginY+(aDynamicSybmolParam.Height-aDynamicSybmolParam.BeginLvl));
    Context.lineTo(aDynamicSybmolParam.BeginX, aDynamicSybmolParam.BeginY+aDynamicSybmolParam.Height);
    
    
    //Context.lineTo(aDynamicSybmolParam.BeginX+aDynamicSybmolParam.Len, aDynamicSybmolParam.BeginY+aDynamicSybmolParam.Height);
    Context.lineTo(aDynamicSybmolParam.BeginX+fLenPart*aDynamicSybmolParam.Len, aDynamicSybmolParam.BeginY+aDynamicSybmolParam.Height);
    
    Context.moveTo(aDynamicSybmolParam.BeginX+2*fLenPart*aDynamicSybmolParam.Len, aDynamicSybmolParam.BeginY+aDynamicSybmolParam.Height);
    Context.lineTo(aDynamicSybmolParam.BeginX+aDynamicSybmolParam.Len, aDynamicSybmolParam.BeginY+aDynamicSybmolParam.Height);
    
    Context.lineTo(aDynamicSybmolParam.BeginX+aDynamicSybmolParam.Len, aDynamicSybmolParam.BeginY+(aDynamicSybmolParam.Height-aDynamicSybmolParam.EndLvl));
    
    //Context.lineTo(aDynamicSybmolParam.BeginX, aDynamicSybmolParam.BeginY+(aDynamicSybmolParam.Height-aDynamicSybmolParam.BeginLvl));
    xCurr = aDynamicSybmolParam.BeginX + 2*fLenPart*aDynamicSybmolParam.Len;
    Context.lineTo(xCurr, fA * xCurr + fB);
    xCurr = aDynamicSybmolParam.BeginX + fLenPart*aDynamicSybmolParam.Len;
    Context.moveTo(xCurr, fA * xCurr + fB);
    Context.lineTo(aDynamicSybmolParam.BeginX, aDynamicSybmolParam.BeginY+(aDynamicSybmolParam.Height-aDynamicSybmolParam.BeginLvl));
  }
  else if(aDynamicSybmolParam.type==3)
  {
    DrawBezier(aDynamicSybmolParam, 0);
  }
  else if(aDynamicSybmolParam.type==4)
  {    
    iMainPartLen = Math.floor(aDynamicSybmolParam.Len * .45);
    iCleanPartLen = Math.floor(aDynamicSybmolParam.Len * .2);
    
    aDynamicSybmolParamLeftPart = aDynamicSybmolParam;
    aDynamicSybmolParamLeftPart.Len = iMainPartLen;
    DrawBezier(aDynamicSybmolParamLeftPart, 2);
    
    aDynamicSybmolParamRightPart = aDynamicSybmolParam;
    //aDynamicSybmolParamRightPart.BeginX = aDynamicSybmolParam.BeginX + 2 * iPartLen;
    aDynamicSybmolParamRightPart.BeginX = aDynamicSybmolParam.BeginX + iMainPartLen + iCleanPartLen;
    aDynamicSybmolParamLeftPart.Len = iMainPartLen;
    DrawBezier(aDynamicSybmolParamRightPart, 1);
  }
  
  Context.lineWidth = 1;
  Context.strokeStyle = '#000000';
  Context.stroke();

}