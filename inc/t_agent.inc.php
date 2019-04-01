<?php
$fEpsilon = .00001;
$__iCountDebug = 5;
//$__iCountDebugMusicFunction = 5;

class Enum 
{
  public static $iSize;
  public static $aArr;
  
  /* int */ public function GetSize()
  {
    return static::$iSize;
  }
  
  //public function GetValueByIndex()
  //{
  //  return static::$iSize;
  //}
};

class MusicFunction extends Enum
{
  public static $iSize = 6;
  public static $aArr = Array (
    "theme"
  , "contrtheme"
  , "pedal"
  , "factura"
  , "line"
  , "riff"
  );
};

class MusicFunctionType extends Enum
{
  public static $iSize = 3;
  public static $aArr = Array (
    "melody"
  , "rythmoharmony"
  , "bass"
  );
};

//echo MusicFunction::$iSize."<br/>";
//echo MusicFunction::GetSize()."<br/>";
//echo MusicFunctionType::$iSize."<br/>";
//echo MusicFunctionType::GetSize()."<br/>";


include_once("C:/Program Files/wamp/www/dev2.5/algo/inc/stochastic.class.php");

//class Event<T>
class Event
{
	private /* int */ $iBegin;
	private /* int */ $iLen;
	private /* T */ $Content;
	private /* string */ $sTplNm;
	
	public function __construct(/* template name */ $sTplNmNew)
	{
	  $this->sTplNm = $sTplNmNew;
	}
	
	public function SetValue (
	    /* int */ $iBeginNew
	  , /* int */ $iLenNew
	  , /* T */ $ContentNew
	)
	{
	  $this->iBegin = $iBeginNew;
	  $this->iLen = $iLenNew;
	  $this->Content = $ContentNew;
	}
	
	public function GetValue (
	    /* int */ &$iBeginNew
	  , /* int */ &$iLenNew
	  , /* T */ &$ContentNew
	)
	{
	  $iBeginNew = $this->iBegin;
	  $iLenNew = $this->iLen;
	  $ContentNew = $this->Content;
	}
	
	/* string */ public function GetTplNm ()
	{
	  return $this->sTplNm;
	}
};

class Algo
{

};

class Velocity2D extends Algo
{
  protected /* Stochastic */ $stVelocity;
  protected /* string */ $sTplNm;
  protected /* int */ $iBeginEvent;
  protected /* int */ $iEndEvent;
  protected /* int */ $iBeginEventLen;
  protected /* int */ $iEndEventLen;
  
  public function __construct(
      /* template name */ $sTplNmNew
	, /* int */ $iBeginEventNew = 0
	, /* int */ $iEndEventNew = 5
	, /* int */ $iBeginEventLenNew = 3
	, /* int */ $iEndEventLenNew = 7
  )
  {
	$this->stVelocity = new Stochastic();
	$this->sTplNm = $sTplNmNew;
	$this->iBeginEvent = $iBeginEventNew;
    $this->iEndEvent = $iEndEventNew;
	$this->iBeginEventLen = $iBeginEventLenNew;
    $this->iEndEventLen = $iEndEventLenNew;
  }

  public function /* double */ GetVelocity(/* const vector<double> */ $vVel)
  {
    //velocity of vertical change (0. = const,.. 0.5 = linear,.. 1. = exponent | + = up, - = down)
    
	/* float */ $fRes = 0.;
	if(count($vVel)<=0)
	{
	  $fRes = $this->stVelocity->get_double_rnd(-1., 1);
	}
	else
	{
	  $fRes = $this->stVelocity->get_double_rnd(0., 1.);
	  $iCntPos = 0; 
	  $iCntNeg = 0;
	  
	  foreach($vVel as $fVel)
	  {
	    if($fVel>=0) 
		{
		  $iCntPos++;  
		}
		else
		{
		  $iCntNeg++;
		}
	  }
	  if($iCntPos>=$iCntNeg)
	  {
	    $fRes *= -1.;
	  }
	}
	return $fRes;
  }
  
  public function /* Event<T> */ GetPoint(/* double */ $fVel, /* const vector<Event<T>> */ &$vRes, $bContinuity = false, $bMonoton = true)
  {
    /* Event<T>*/ $eRes = new Event($this->sTplNm);
	//$iLen = $this->stVelocity->get_number(3, 7);
	$iLen = $this->stVelocity->get_number($this->iBeginEventLen, $this->iEndEventLen);
	if(count($vRes)<=0)
	{
	  //$iBegin = $this->stVelocity->get_number(0, 5);
	  $iBegin = $this->stVelocity->get_number($this->iBeginEvent, $this->iEndEvent);
	  echo "<br />\$iBegin = $iBegin<br />";
	  if("MusicFunction" == $this->sTplNm)
	  {
	    $iTmp = $this->stVelocity->get_number(0, MusicFunction::$iSize-1);
		$Content = MusicFunction::$aArr[$iTmp];
	  }
	  else //default = float
	  {
	    $Content = $this->stVelocity->get_double_rnd(0., 1.);
	  }
	}
	else
	{
	  if($bMonoton)
	  {
		  $iBeginNew = -1;
		  $iLenNew = -1;
		  $ContentNew = null;
		  
		  $vRes[count($vRes)-1]->GetValue (
			  $iBeginNew
			, $iLenNew
			, $ContentNew
		  );	  
		  $iBegin = $iBeginNew + $iLenNew;
	  }
	  else
	  {
	      $iBegin = 0;
	  }
	  echo "<br />a) \$iBegin = $iBegin<br />";
	  
	  if($bContinuity)
	  {
	    if($this->stVelocity->get_double_rnd(0., 1.)>=.6)
		{
		  $bContinuity = false;
		}
	  }
	  if(!$bContinuity)
	  {
	    echo "<br />\$this->iBeginEvent, \$this->iEndEvent = ".$this->iBeginEvent.", ".$this->iEndEvent."<br />";
		$iRnd = $this->stVelocity->get_number($this->iBeginEvent, $this->iEndEvent);
		echo "<br />rnd = $iRnd<br />";
		if($bMonoton)
	    {
	      $iBegin += $iRnd;
		}
		else
		{
		  $iBegin = $iRnd;
		}
		echo "<br />b) \$iBegin = $iBegin<br />";
	  }
	  //0. = const,.. 0.5 = linear,.. 1. = exponent | + = up, - = down
	  /*
	  if()
	  {
	  
	  }
	  else
	  {
	  
	  }
	  */
	  if("MusicFunction" == $this->sTplNm)
	  {
	    $iTmp = $this->stVelocity->get_number(0, MusicFunction::$iSize-1);
		$Content = MusicFunction::$aArr[$iTmp];
	  }
	  else //default = float
	  {
	    $Content = $this->stVelocity->get_double_rnd(-1., 1.);
	  }
	  
	}
	
	$eRes->SetValue (
	    $iBegin
	  , $iLen
	  , $Content
	);
   
    return $eRes;
  }
};

abstract class Agent //<T>
{
  protected /* vector<Event<T>> */ $vRes;
  protected /* Algo */ $aGen;
  protected /* string */ $sTplNm;
  
  /* Event<T> */ abstract public function GenEvent();
  public function __construct(/* template name */ $sTplNmNew, /* Algo */ $aGenNew)
  {
    $this->sTplNm = $sTplNmNew;
	$this->aGen = $aGenNew;
	$this->vRes = array(/* Event<T> */);
  }
};

//class ThingA<T>
class ThingA extends Agent
{

  private /* vector<double> */ $vVel;
  //private /* vector<Event<T>> */ $vRes;
  //private /* string */ $sTplNm;
  private /* bool */ $bContinuity;
  private /* bool */ $bMonoton;
  
  public function __construct(/* template name */ $sTplNmNew, /* Algo */ $aGenNew, $bContinuityNew = false, $bMonotonNew = true)
  {
    parent::__construct($sTplNmNew, $aGenNew);
    $this->vVel = array(/* double */);
    $this->bContinuity = $bContinuityNew;
    $this->bMonoton = $bMonotonNew;
  }
    
  /* Event<T> */ public function GenEvent()
  {
    global $__iCountDebug;
    /* Event<T> */ $eTmp = new Event($this->sTplNm);
	/* double */ $fVel; //velocity of vertical change (0. = const,.. 0.5 = linear,.. 1. = exponent | + = up, - = down)
	$i=0;
    do
	{
	  $fVel = $this->aGen->GetVelocity($this->vVel);
	  $this->vVel[] = $fVel;
	  $eTmp = $this->aGen->GetPoint($fVel, $this->vRes, $this->bContinuity, $this->bMonoton);
	  $this->vRes[] = $eTmp;
	  $i++;
	}
	while($i<=$__iCountDebug);
  }
  
  public function GetRes()
  {
    return $this->vRes;
  }
};


//class MusicFunctionA extends Agent
//{
//  /* Event<T> */ public function GenEvent()
//  {
//    global $__iCountDebugMusicFunction;
//	
//	/* Event<T> */ $eTmp = new Event($this->sTplNm);
//    for($i=0; $i<$__iCountDebugMusicFunction; $i++)
//	{
//	  $eTmp = $this->aGen->GetPoint($fVel, $this->vRes);
//	  $this->vRes[] = $eTmp;
//	}
//  }
//};

class Util
{
  private /* double */ $fMinBegin, $fMaxBegin, $fMinEnd, $fMaxEnd;
  
  public function SetLimit(
      $fMinBeginNew
	, $fMaxBeginNew
	, $fMinEndNew
	, $fMaxEndNew
  )
  {
    $this->fMinBegin = $fMinBeginNew;
	$this->fMaxBegin = $fMaxBeginNew;
	$this->fMinEnd = $fMinEndNew;
	$this->fMaxEnd = $fMaxEndNew;
	
	echo "<br />===============	<br />
	\$fMinBeginNew $fMinBeginNew <br />
	\$fMaxBeginNew $fMaxBeginNew <br />
	\$fMinEndNew $fMinEndNew <br />
	\$fMaxEndNew $fMaxEndNew <br />
	================<br />";
	
	
	//A fMinBegin + B = fMinEnd
    //A fMaxBegin + B = fMaxEnd
	$this->dA = ($this->fMaxEnd - $this->fMinEnd) / ($this->fMaxBegin-$this->fMinBegin);
	$this->dB = $this->fMaxEnd - $this->dA * $this->fMaxBegin;
	//echo "<br />===============	\$this->dA ".$this->dA."<br />\$this->dB ".$this->dB."================<br />";
  }	

  public function /* vector<Event<T>> */ ScaleEvent(
      /* vector<Event<T>> */ &$vEv
	, /* <Event<T> */ &$eMinTarget
	, &$eMaxTarget
	, &$eMinSrc = null /* if not set source min and max will be defined from min and max values of Begin param */
	, &$eMaxSrc = null
	, /* bool */ $bSameLenScaleParam = true /* if true Length will be scaled with same parameters like previous Begin */
  )
  {
    /* T */ $sTplNm = $vEv[0]->GetTplNm();
    
    /* vector<Event<T>> */ $vRes = array(/* Event<T> */);
	
	$aSliceSrc = array();
	if($eMinSrc)
	{
		/* $iBeginMin */ $aSliceSrc[0][0] = -1;
		/* $iLenMin */ $aSliceSrc[1][0] = -1;
		/* $ContentMin */ $aSliceSrc[2][0]= null;
		$eMinSrc->GetValue (
			  $aSliceSrc[0][0]
			, $aSliceSrc[1][0]
			, $aSliceSrc[2][0]
		);
	}
	if($eMaxSrc)
	{
		/* $iBeginMax */ $aSliceSrc[0][1] = -1;
		/* $iLenMax */ $aSliceSrc[1][1] = -1;
		/* $ContentMax */ $aSliceSrc[2][1]= null;
		$eMaxSrc->GetValue (
			  $aSliceSrc[0][1]
			, $aSliceSrc[1][1]
			, $aSliceSrc[2][1]
		);
	}
	
	$aSliceTarget = array();
	/* $iBeginMin */ $aSliceTarget[0][0] = -1;
	/* $iLenMin */ $aSliceTarget[1][0] = -1;
	/* $ContentMin */ $aSliceTarget[2][0]= null;
	$eMinTarget->GetValue (
	      $aSliceTarget[0][0]
	    , $aSliceTarget[1][0]
	    , $aSliceTarget[2][0]
	);
	/* $iBeginMax */ $aSliceTarget[0][1] = -1;
	/* $iLenMax */ $aSliceTarget[1][1] = -1;
	/* $ContentMax */ $aSliceTarget[2][1] = null;
	$eMaxTarget->GetValue (
	      $aSliceTarget[0][1]
	    , $aSliceTarget[1][1]
	    , $aSliceTarget[2][1]
	);	
	  
	
    //extract slices
	/* vector<int> */ $aiBegin;
	/* vector<int> */ $aiLen;
	/* vector<T> */ $atContent;
	foreach($vEv as $eVal)
	{
	  $iBeginNew = -1;
	  $iLenNew = -1;
	  $ContentNew = null;
	  
	  $eVal->GetValue (
	      $iBeginNew
	    , $iLenNew
	    , $ContentNew
	  );
	  
	  $aiBegin[] = $iBeginNew;
	  $aiLen[] = $iLenNew;
	  $atContent[] = $ContentNew;
	}
	$aSlice = array();
	$aSlice[] = $aiBegin;
	$aSlice[] = $aiLen;
	$aSlice[] = $atContent;
	
	$aSliceNew = array();
	$aSliceNew[] = array();
	$aSliceNew[] = array();
	$aSliceNew[] = array();
	
	//echo "<br />===============	aSliceNew	================<br />";
	//print_r($aSlice);
	//echo "<br />============================================<br />";
	
	//for all slices
	foreach($aSlice as $CurKey => $CurSlice)
	{	  
	  $bScaleEnable = false;
	  if($CurKey==2)
	  {
	    if("float"==$sTplNm)
		{
		  $bScaleEnable = true;
		}
	  }
	  else
	  {
	    $bScaleEnable = true;
	  }
	  //echo "<br />===============	(double)\$aSliceTarget[$CurKey][0] ".(double)$aSliceTarget[$CurKey][0]."<br />
	  //, (double)\$aSliceTarget[$CurKey][1] ".(double)$aSliceTarget[$CurKey][1]."<br />
	  //, (double)min(\$CurSlice) ".(double)min($CurSlice)."<br />
	  //, (double)max(\$CurSlice) ".(double)max($CurSlice)."	================<br />";
	  
	  //echo "<br />\$bScaleEnable = $bScaleEnable<br />";
	  //echo "<br />\$CurKey = $CurKey<br />";
	  //echo "<br />\$CurSlice = <br />";
	  //print_r($CurSlice);
	  //echo "<br /><br />";
	  
	  
	  if($bScaleEnable)
	  {
		  //find min/ max
		  //$aSliceTarget[$CurKey] = array(min($CurSlice), max($CurSlice));
		  $bReSetLimit = true;
		  if(($CurKey==1)&&($bSameLenScaleParam))
		  {
		    $bReSetLimit = false;
		  }
		  if($bReSetLimit)
		  {
		    
		    if($eMinSrc)
			{
			  $fMinSrcVal = $aSliceSrc[$CurKey][0];
			}
			else
			{
			  $fMinSrcVal = (double)min($CurSlice);
			}
			if($eMaxSrc)
			{
			  $fMaxSrcVal = $aSliceSrc[$CurKey][1];
			}
			else
			{
			  $fMaxSrcVal = (double)max($CurSlice);
			}
			
		    $this->SetLimit(
			  $fMinSrcVal
		    , $fMaxSrcVal
		    , (double)$aSliceTarget[$CurKey][0]
		    , (double)$aSliceTarget[$CurKey][1]
		    );
		  }
		  
		  
		  foreach($CurSlice as $CurVal)
		  {
			//scale
			$aSliceNew[$CurKey][] = $NewVal = $this->ScaleDouble($CurVal);		
		  }	  
	  }
	  else
	  {
		  foreach($CurSlice as $CurVal)
		  {
			//scale
			$aSliceNew[$CurKey][] = $CurVal;		
		  }
	  }
	}
	
	foreach($aSliceNew[0] as $KeyNew => $ValNew)
	{
	  $eRes = new Event("double");
	  $eRes->SetValue (
	      $aSliceNew[0][$KeyNew]
	    , $aSliceNew[1][$KeyNew]
	    , $aSliceNew[2][$KeyNew]
	  );
	  $vRes[] = $eRes;
	}
	
	return $vRes;
  }
  
  public function /* double */ ScaleDouble($fOld)
  {
    echo "<br />\$this->dA * \$fOld + \$this->dB; = ".$this->dA." * $fOld + ".$this->dB."<br />";
    return $this->dA * $fOld + $this->dB;
  }
};
?>