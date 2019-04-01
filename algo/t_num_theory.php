<?php
define("LOWEST_MIDI_PITCH", 21);

//include_once("./inc/stochastic.class.php");
include_once("./inc/combinatoric.class.php");
include_once("./inc/number_theory.class.php");
include_once("./inc/fraction.class.php");

$st1 = new Stochastic();
$ntEucl = new NumberTheory();
$cmComb = new Combinatoric();

$frFract1 = new Fraction(5, 3);
$frFract3 = new Fraction(13, 39);
$frFract4 = new Fraction(898989, 787878);
$frFract5 = new Fraction(1761, 980976);

echo "<br />".$frFract1->GetVal();
echo "<br />".$frFract1->GetVal(true);
echo "<br />".$frFract1->GetGCD();

echo "<br />".$frFract3->GetVal();
echo "<br />".$frFract3->GetVal(true);
echo "<br />".$frFract3->GetGCD();

echo "<br />".$frFract4->GetVal();
echo "<br />".$frFract4->GetVal(true);
echo "<br />".$frFract4->GetGCD();

echo "<br />".$frFract5->GetVal();
echo "<br />".$frFract5->GetVal(true);
echo "<br />".$frFract5->GetGCD();

$frFract6 = $frFract1->Add($frFract3);
echo "<br />".$frFract6->GetVal();
echo "<br />".$frFract6->GetVal(true);
echo "<br />".$frFract6->GetGCD();


$frFract7 = new Fraction(-3, 4);
$frFract8 = $frFract1->Mult($frFract7);
echo "<br />".$frFract8->GetVal();
echo "<br />".$frFract8->GetVal(true);
echo "<br />".$frFract8->GetGCD();

$frFract9 = $frFract3->Sub($frFract1);
echo "<br /><br />13/39 - 5/3 = ".$frFract9->GetVal();
echo "<br />".$frFract9->GetVal(true);
echo "<br />".$frFract9->GetGCD();

$frMinusOne = new Fraction(-1, 1);
$frFract10 = $frMinusOne->Div($frFract1);
echo "<br /><br />-1/1 / 5/3 = ".$frFract10->GetVal();
//echo "<br />".$frFract9->GetVal(true);
//echo "<br />".$frFract9->GetGCD();

echo "<br /><br />(5/3 ==  13/39) = ".($frFract1->Eq($frFract3) ? "true" : "false");

$frFract11 = new Fraction(4, 4);
$frFract12 = $frFract1->Mult($frFract11);
echo "<br /><br />(".($frFract1->GetVal())." ==  ".($frFract12->GetVal()).") = ".($frFract1->Eq($frFract12) ? "true" : "false");

$a = $st1->get_number(10, 10000);
$b = $st1->get_number(10, 10000);
$aTrace = array();
echo "<br />\$a = $a | \$b = $b | EuclGCD = ".$ntEucl->EucleadGCD($a, $b, $aTrace)."<br />";
print_r($aTrace);

$iMidiMinPitch = $st1->get_number(LOWEST_MIDI_PITCH+36, LOWEST_MIDI_PITCH+48);
$iMidiMaxPitch = $st1->get_number($iMidiMinPitch+12, $iMidiMinPitch+24);

$aMidiPitch = array();
$aMidiSys = array();
$aMidiPitchSeria = array();
$aDurSeria = array();

$aDur = array();
$aDurFraction = array();
$aBigDur = array();
$aSmallDur = array();

foreach ($aTrace as $value)
{
	$aMidiPitch[] = $value % ($iMidiMaxPitch-$iMidiMinPitch) + $iMidiMinPitch;
	$aCurrDur = $value % 256 + 1;
	if($aCurrDur>32)
	{
		$aBigDur[] = $aCurrDur;
	}
	else
	{
		$aSmallDur[] = $aCurrDur;
	}
}

$iSmallDurCnt = count($aSmallDur);
$iBigInTotalDurCnt = (int)($iSmallDurCnt/3);
if($iBigInTotalDurCnt<1) 
{
	$iBigInTotalDurCnt = 1;
}

echo "<br />aMidiPitch :: ";
print_r($aMidiPitch);

echo "<br />aBigDur :: ";
print_r($aBigDur);

echo "<br />aSmallDur :: ";
print_r($aSmallDur);

echo "<br />\$iBigInTotalDurCnt = $iBigInTotalDurCnt";

foreach ($aSmallDur as $value)
{
		$aDur[] = $value;
}
if($iBigInTotalDurCnt>=count($aBigDur))
{
	foreach ($aBigDur as $value)
	{
		$aDur[] = $value;
	}
}
else
{
	$aTmp = $cmComb->get_array_wout_repeat($aBigDur, $iBigInTotalDurCnt);
	/*
	for($i=1; $i<=$iBigInTotalDurCnt; $i++)
	{
		$aDur[] = $aBigDur[$st1->get_number(0, count($aBigDur)-1)];
	}
	*/
	foreach ($aTmp as $value)
	{
		$aDur[] = $value;
	}
}

echo "<br />aDur :: ";
print_r($aDur);

foreach ($aDur as $value)
{
	$aDurFraction[] = new Fraction($value, 1);
}
echo "<br />aDurFraction :: ";
foreach ($aDurFraction as $value)
{
	echo "<br />\$aDurFraction[] = ".$value->GetVal();
}

echo "<br /><br />============================= rythm system =============================";

if(($iDurFractionCnt = count($aDurFraction))<4)
{
	$iDurRepeatCnt = 1;
}
else
{
	$iDurRepeatCnt = ceil($iDurFractionCnt/4);
}
echo "<br />\$iDurFractionCnt = $iDurFractionCnt";
echo "<br />\$iDurRepeatCnt = $iDurRepeatCnt";

$aRythmSys = $cmComb->get_array_wout_repeat($aDurFraction, $iDurRepeatCnt);
echo "<br />aRythmSys :: ";
foreach ($aRythmSys as $value)
{
	echo "<br />\$aRythmSys[] = ".$value->GetVal();
}

function SearchInObjArray($mNeedle, $aHaystack)
{
	$bRes = false;
	foreach ($aHaystack as $value)
	{
		if($value->Eq($mNeedle))
		{
			$bRes = true;
			break;
		}
	}
	return $bRes;
}

foreach ($aDurFraction as $value)
{
	//if(array_search($value, $aRythmSys)!==false) !!!
	if(SearchInObjArray($value, $aRythmSys)!==false)
	{
		$iDurRepeatCnt = $st1->get_number(2, 5);
		for($i=1; $i<=$iDurRepeatCnt; $i++)
		{
			$aDurSeria[] = $value;
		}
	}
	else
	{
		$aDurSeria[] = $value;
	}
}
shuffle($aDurSeria);

echo "<br />\$aDurSeria :: ";
foreach ($aDurSeria as $value)
{
	echo "<br />\$aDurSeria[] = ".$value->GetVal();
}


echo "<br /><br />============================= pitch system =============================";

if(count($aMidiPitch)<4)
{
	$iMidiSysCnt = 1;
}
else
{
	$iMidiSysCnt = ceil(count($aMidiPitch)/4);
}
echo "<br />\$iMidiSysCnt = $iMidiSysCnt";

$aMidiSys = $cmComb->get_array_wout_repeat($aMidiPitch, $iMidiSysCnt);
echo "<br />aMidiSys :: ";
print_r($aMidiSys);

foreach ($aMidiPitch as $value)
{
	if(array_search($value, $aMidiSys)!==false)
	{
		$iMidiPitchRepeatCnt = $st1->get_number(2, 3);
		for($i=1; $i<=$iMidiPitchRepeatCnt; $i++)
		{
			$aMidiPitchSeria[] = $value;
		}
	}
	else
	{
		$aMidiPitchSeria[] = $value;
	}
}
shuffle($aMidiPitchSeria);

echo "<br />\$aMidiPitchSeria :: ";
print_r($aMidiPitchSeria);

/*
0 = no change
1 = asc
2 = desc
3 = shuffle
*/

function split_and_org(&$aArr)
{
	global $st1;
	
	if(count($aArr)<=1)
	{
		return;
	}
	elseif(count($aArr)==2)
	{
		$iIndxBeginSplit = 2;
	}
	else
	{
		$iIndxBeginSplit = $st1->get_number(1, count($aArr)-2);
	}
	echo "<br />\$iIndxBeginSplit = $iIndxBeginSplit";
	$aPart = array();
	$aPart[] = array_slice($aArr, 0, $iIndxBeginSplit);
	$aPart[] = array_slice($aArr, $iIndxBeginSplit);
	
	echo "<br />\$aPart[0] :: ";
	print_r($aPart[0]);
	
	echo "<br />\$aPart[1] :: ";
	print_r($aPart[1]);
	
	foreach ($aPart as &$aValue)
	{
		$iSortType = $st1->get_number(0, 2);
		switch ($iSortType) 
		{
			case 1:
				sort($aValue);
				break;
			case 2:
				rsort($aValue);
				break;
			case 3:
				shuffle($aValue);
				break;
		}
		$iGoFurther = $st1->get_number(0, 1);
		if($iGoFurther)
		{
			split_and_org($aValue);
		}
	}
	$aArr = array_merge($aPart[0], $aPart[1]);
}

split_and_org($aMidiPitchSeria);

echo "<br />\$aMidiPitchSeria :: ";
print_r($aMidiPitchSeria);

/*
String[] noteString = new String[] { "C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B" };

int octave = (initialNote / 12) - 1;
int noteIndex = (initialNote % 12);
String note = noteString[noteIndex];
*/

$aNoteKeys = array("C", "C#", "D", "D#", "E", "F", "F#", "G", "G#", "A", "A#", "B");

function midi_to_key_oct($iMidiNum, &$iOctave, &$sNoteKey)
{
	global $aNoteKeys;
	$iOctave = (int)(($iMidiNum / 12) - 1);
	$iNoteIndex = ($iMidiNum % 12);
	$sNoteKey = $aNoteKeys[$iNoteIndex];
}

/*
Flow.durationToTicks.durations = {
  '1/2': Flow.RESOLUTION * 2,
  '1': Flow.RESOLUTION / 1,
  '2': Flow.RESOLUTION / 2,
  '4': Flow.RESOLUTION / 4,
  '8': Flow.RESOLUTION / 8,
  '16': Flow.RESOLUTION / 16,
  '32': Flow.RESOLUTION / 32,
  '64': Flow.RESOLUTION / 64,
  '128': Flow.RESOLUTION / 128,
  '256': Flow.RESOLUTION / 256,
};
*/

$iOctave = 0;
$sNoteKey = "";
$aKeyOctavePitchSeria = array();
$sVexNotes = "var notes = [";
$bFstVexNote = true;
foreach ($aMidiPitchSeria as $iValue)
{
	midi_to_key_oct($iValue, $iOctave, $sNoteKey);
	echo "<br />midi number = $iValue, \$iOctave = $iOctave, \$sNoteKey = $sNoteKey";
	$sAccidental = "n";
	if (strpos($sNoteKey, '#') !== false) 
	{
		$sAccidental = "#";
	}
	$aKeyOctavePitchSeria[] = array($iValue, $sNoteKey, $iOctave, $sAccidental);
	if($bFstVexNote == false)
	{
		$sVexNotes .= ", ";
	}
	else
	{
		$bFstVexNote = false;
	}
	$sVexNotes .= "new Vex.Flow.StaveNote({ keys: [\"$sNoteKey/$iOctave\"], duration: \"4\" }).addAccidental(0, new Vex.Flow.Accidental(\"$sAccidental\"))";
}
$sVexNotes .= "];";

echo "<br />\$aKeyOctavePitchSeria :: ";
print_r($aKeyOctavePitchSeria);

echo "<br />\$sVexNotes = $sVexNotes";
?>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	
	<title>Dev 2.5</title>
	
	<!-- Latest compiled and minified CSS -->
	<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
	<link href="/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- Optional theme -->
	<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">-->
	<link href="/bootstrap-3.3.7-dist/css/bootstrap-theme.min.css" rel="stylesheet">
	
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
    <link href="http://getbootstrap.com/examples/sticky-footer-navbar/sticky-footer-navbar.css" rel="stylesheet">
    <link href="/dev2.5/inc/fix.css" rel="stylesheet">
	
	
	<!-- VexFlow Compiled Source -->
    <script src="../inc/vexflow/vexflow-min.js"></script>

    <!-- Support Sources -->
    <script src="../inc/vexflow/jquery.js"></script>
    <script src="../inc/vexflow/raphael.js"></script>
    
    <meta http-equiv='refresh' content=''>
    <meta http-equiv="pragma" content="no-cache" />
    <link href="/dev2.5/tpl/style.css" rel="stylesheet" type="text/css" />
    <link href="/dev2.5/tpl/style_svg.css" rel="stylesheet" type="text/css" />	
</head>
<body>
<div>
 <p><em>VexflowNote</em><br /><br /><div class="description div_outer1D8D87CD-753E-6755-8BF5-2054B19AEE20">
  <div class="example div_inner1D8D87CD-753E-6755-8BF5-2054B19AEE20" example="a">
    <canvas width="525" height="420"></canvas>
    <script>
      var canvas = $("div.div_outer1D8D87CD-753E-6755-8BF5-2054B19AEE20 div.div_inner1D8D87CD-753E-6755-8BF5-2054B19AEE20 canvas")[0];
      var renderer = new Vex.Flow.Renderer(canvas, Vex.Flow.Renderer.Backends.CANVAS);

      var ctx = renderer.getContext();
      var stave = new Vex.Flow.Stave(10, 150, 500);
      stave.addClef("treble").setContext(ctx).draw();
      
      // Create the notes
	  /*
      var notes = [
        // A quarter-note C.
        //new Vex.Flow.StaveNote({ keys: ["c/4"], duration: "q" }),
		new Vex.Flow.StaveNote({ keys: ["g/4"], duration: "w" }),

        // A quarter-note D.
        new Vex.Flow.StaveNote({ keys: ["f#/4"], duration: "w" }).addAccidental(0, new Vex.Flow.Accidental("#")),

        // A quarter-note rest. Note that the key (b/4) specifies the vertical
        // position of the rest.
        new Vex.Flow.StaveNote({ keys: ["a/4"], duration: "w" }),

        // A C-Major chord.
        new Vex.Flow.StaveNote({ keys: ["f/4"], duration: "w" }).addAccidental(0, new Vex.Flow.Accidental("n"))
      ];
	  */
	  <?php echo $sVexNotes; ?>

      Vex.Flow.Formatter.FormatAndDraw(ctx, stave, notes);
    </script>
  </div>
  </div></p>
</div>
</body>
</html>

<?php
unset($cmComb);
unset($ntEucl);
unset($st1);
?>