<?php
include_once("C:/Program Files/wamp/www/dev2.3/algo/inc/stochastic.class.php");

class ParameterFactory
{
private type;
private set;
private changeFreq;
private changeLim;
private delta;
private trend;
private fragLen;
private repetition;
private repetFrameLen;
private repetFrameScaling;
private repetAccuracy;
private devStructur;
private /* Stochastic */ $st1;


	public function __construct (
		typeNew, 
		setNew, 
		changeFreqNew = 2, 
		changeLimNew = 2, 
		deltaNew = 2, 
		trendNew = 2, 
		fragLenNew = 2, 
		repetitionNew = 2, 
		repetFrameLenNew = 2,
		repetFrameScalingNew = 2, 
		repetAccuracyNew = 2, 
		devStructurNew = 2
	)
	{
		$this->type = typeNew;
		$this->set = setNew;
		$this->changeFreq = changeFreqNew;
		$this->changeLim = changeLimNew;
		$this->delta = deltaNew;
		$this->trend = trendNew;
		$this->fragLen = fragLenNew;
		$this->repetition = repetitionNew;
		$this->repetFrameLen = repetFrameLenNew;
		$this->repetFrameScaling = repetFrameScalingNew;
		$this->repetAccuracy = repetAccuracyNew;
		$this->devStructur = devStructurNew;
		$this->st1 = new Stochastic();
	}

	public function __destruct()
	{
	  unset($this->st1);
	}

	public function GetVal()
	{
	  return $this->st1->get_number(0, (count($this->set)-1));
	}
}

?>