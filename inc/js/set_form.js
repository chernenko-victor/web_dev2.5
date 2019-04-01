//
var DistanceStepPitchArrayRowCnt = 0;

function addDistanceStepPitchArrayRow()
{
  /*
  ((Count of interval <p_condition[1]> then <pitch[1]>) is <c_condition[1]> then <count[1]>) -
  AND ((Count of interval <p_condition[2]> then <pitch[2]>) is <c_condition[2]> then <count[2]>) -
  +
  */
  var sCurrId = 'DistanceStepPitchArrayRow'+DistanceStepPitchArrayRowCnt;
  $('<div>')
  .attr( { id:sCurrId, name:sCurrId } )
  .text("((Count of interval that ").appendTo("#aDistanceStepPitch");

  
  if(DistanceStepPitchArrayRowCnt)
  {
    $('<span>')
    .text("AND ").prependTo("#"+sCurrId);
  }
  else
  {
    $('<span>')
    .text("_____").prependTo("#"+sCurrId);
  }
  
  var sCurrPCondition = "p_condition["+DistanceStepPitchArrayRowCnt+"]";
  $('<input/>')
  .attr( { type:'text', size:20, autofocus:0, id:sCurrPCondition, name:sCurrPCondition } )
  .val(sCurrPCondition).appendTo("#"+sCurrId);
  
  $('<span>')
  .text(" then (to) ").appendTo("#"+sCurrId);
  
  var sCurrPitch = "pitch["+DistanceStepPitchArrayRowCnt+"]";
  $('<input/>')
  .attr( { type:'text', size:20, autofocus:0, id:sCurrPitch, name:sCurrPitch } )
  .val(sCurrPitch).appendTo("#"+sCurrId);
  
  $('<span>')
  .text(") is ").appendTo("#"+sCurrId);
  
  var sCurrCCondition = "c_condition["+DistanceStepPitchArrayRowCnt+"]";
  $('<input/>')
  .attr( { type:'text', size:20, autofocus:0, id:sCurrCCondition, name:sCurrCCondition } )
  .val(sCurrCCondition).appendTo("#"+sCurrId);
  
  $('<span>')
  .text(" then (to) ").appendTo("#"+sCurrId);
  
  var sCurrCount = "count["+DistanceStepPitchArrayRowCnt+"]";
  $('<input/>')
  .attr( { type:'text', size:20, autofocus:0, id:sCurrCount, name:sCurrCount } )
  .val(sCurrCount).appendTo("#"+sCurrId);
  
  $('<span>')
  .text(") ").appendTo("#"+sCurrId);
  
  
  $('<input/>', {
    type:'button',
    'id':'RmBtnId'+DistanceStepPitchArrayRowCnt,
    'class':'RmBtnClass',
    'style':'cursor:pointer;font-weight:bold;',
    'click':function(){ 
	  //alert(this.id);
	}
  }).val("-").appendTo("#"+sCurrId);
  
  $('<input/>', {
    type:'button',
    'id':'AddBtnId'+DistanceStepPitchArrayRowCnt,
    'class':'AddBtnClass',
    'style':'cursor:pointer;font-weight:bold;',
    'click':function(){ 
	  //alert(this.id);
	  addDistanceStepPitchArrayRow();
	}
  }).val("+").appendTo("#"+sCurrId);

  DistanceStepPitchArrayRowCnt++;
}

/*
$("#btn1").click(function(){
    $("#test1").text("Hello world!");
});
*/