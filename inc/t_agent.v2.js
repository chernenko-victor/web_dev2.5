function inherit(proto) {
  function F() {}
  F.prototype = proto
  return new F
}


// Returns a random number between 0 (inclusive) and 1 (exclusive)
function getRandom() {
  return Math.random();
}

// Returns a random number between min (inclusive) and max (exclusive)
function getRandomArbitrary(min, max) {
  return Math.random() * (max - min) + min;
}

// Returns a random integer between min (included) and max (excluded)
// Using Math.round() will give you a non-uniform distribution!
function getRandomInt(min, max) {
  min = Math.ceil(min);
  max = Math.floor(max);
  return Math.floor(Math.random() * (max - min)) + min;
}

// Returns a random integer between min (included) and max (included)
// Using Math.round() will give you a non-uniform distribution!
function getRandomIntInclusive(min, max) {
  min = Math.ceil(min);
  max = Math.floor(max);
  return Math.floor(Math.random() * (max - min + 1)) + min;
}

//abstract class Agent //<T>
//{
//  protected /* vector<Event<T>> */ $vRes;
//  protected /* Algo */ $aGen;
//  protected /* string */ $sTplNm;
//  
//  /* Event<T> */ abstract public function GenEvent();
//  public function __construct(/* template name */ $sTplNmNew, /* Algo */ $aGenNew)
//  {
//    $this->sTplNm = $sTplNmNew;
//	$this->aGen = $aGenNew;
//	$this->vRes = array(/* Event<T> */);
//  }
//};

function Agent(/* template name */ sTplNmNew, /* Algo */ aGenNew)
{
  this.sTplNm = sTplNmNew;
  this.aGen =  aGenNew;
}

Agent.prototype = {
  /* protected vector<Event<T>> */ $vRes: "", 
  GetTplNm: function() {
    alert(this.sTplNm)
  },
  SetTplNm: function(/* template name */ sTplNmNew) {
    this.sTplNm = sTplNmNew;
  }  
}

var agA1 = new Agent("double", "Algo1");
agA1.GetTplNm();
agA1.SetTplNm("int");
agA1.GetTplNm();


//class ThingA extends Agent
//{
//
//  private /* vector<double> */ $vVel;
//  private /* bool */ $bContinuity;
//  private /* bool */ $bMonoton;
//  
//  public function __construct(/* template name */ $sTplNmNew, /* Algo */ $aGenNew, $bContinuityNew = false, $bMonotonNew = true)
//  {
//    parent::__construct($sTplNmNew, $aGenNew);
//    $this->vVel = array(/* double */);
//    $this->bContinuity = $bContinuityNew;
//    $this->bMonoton = $bMonotonNew;
//  }
//    
//  /* Event<T> */ public function GenEvent()
//  {
//    global $__iCountDebug;
//    /* Event<T> */ $eTmp = new Event($this->sTplNm);
//	/* double */ $fVel; //velocity of vertical change (0. = const,.. 0.5 = linear,.. 1. = exponent | + = up, - = down)
//	$i=0;
//    do
//	{
//	  $fVel = $this->aGen->GetVelocity($this->vVel);
//	  $this->vVel[] = $fVel;
//	  $eTmp = $this->aGen->GetPoint($fVel, $this->vRes, $this->bContinuity, $this->bMonoton);
//	  $this->vRes[] = $eTmp;
//	  $i++;
//	}
//	while($i<=$__iCountDebug);
//  }
//  
//  public function GetRes()
//  {
//    return $this->vRes;
//  }
//};

function ThingA(/* template name */ sTplNmNew, /* Algo */ aGenNew, bContinuityNew /* = false */, bMonotonNew /* = true */) // in IE default values causes an error
{
  /* private vector<double> */ this.vVel=[];
  /* private bool */ this.bContinuity = bContinuityNew;
  /* private bool */ this.bMonoton = bMonotonNew;
  Agent.apply(this, [sTplNmNew, aGenNew]);
}

ThingA.prototype = inherit(Agent.prototype);

/* Event<T> public function */ ThingA.prototype.GenEvent = function() {
  for (var i = 0; i < 10; i++) 
  {
    this.vVel.push(getRandomArbitrary(1, 5));
  }
}

/* Event<T> public function */ ThingA.prototype.ShowCont = function() {
  alert('bContinuity = ' + this.bContinuity);
}

/* Event<T> public function */ ThingA.prototype.ShowVel = function() {
  /* string */ sSerialize = "";
//  this.vVel.forEach( function(s) { 
//    alert("!");
//    sSerialize += s;
//    sSerialize += " | ";
//  });
//  alert('vVel = ' + sSerialize);
  if(this.vVel.length)
  {
    for (var prop in this.vVel)
    {
      //alert("prop: " + prop + " value: " + this.vVel[prop]);
      sSerialize = sSerialize + "prop: " + prop + " value: " + this.vVel[prop] + " | ";
    }
    alert('ShowVel: ' + sSerialize);
  }
  else
  {
    alert('vVel is empty');
  }
}


var thLine1 = new ThingA('double', 'Gen1', true, true);
thLine1.GenEvent();
thLine1.ShowVel();

thLine1.GetTplNm();
thLine1.SetTplNm("MusicFunction");
thLine1.GetTplNm();