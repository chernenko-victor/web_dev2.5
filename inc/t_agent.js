//var Agent = {
//  /* protected vector<Event<T>> */ vRes:"",
//  /* Event<T> abstract public function */ GenEvent: function()
//  {
//    alert("execute GenEvent");
//  }
//}

//var ThingA = {
//  /* private vector<double> */ vVel:"",
//  /* Event<T> abstract public function */ GetRes: function()
//  {
//    alert("execute GetRes");
//  }
//}

//ThingA.__proro__ = Agent;
//ThingA.GenEvent();

var Agent = {
  /* protected vector<Event<T>> */ vRes: "" ,
  /* string */ sNm: "Agent" ,
  /* Event<T> abstract public function */ GenEvent: function() 
  {
    alert("execute GenEvent in "+this.sNm);
  } 
}
	 
var ThingA = {
  /* private vector<double> */ vVel: "" ,
  /* Event<T> abstract public function */ GetRes: function() 
  { 
    alert("execute GetRes"); 
  }
}
	 
//ThingA.__proto__ = Agent 

ThingA = Object.create(Agent);
ThingA.sNm = "ThingA";
ThingA.GenEvent();

function ThingB() {
  // /* private vector<double> */ vVel: "" ,
  // /* Event<T> abstract public function */ GetRes: function() 
  //{ 
  //  alert("execute GetRes"); 
  //}, 
  this.sNm = "ThingB";
}
ThingB.prototype = Agent;

thExempl1 = new ThingB();
thExempl1.GenEvent();

Agent.GenEvent();
