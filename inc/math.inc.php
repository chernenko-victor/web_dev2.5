<?php
class Math
{
  public function /* Math */ __construct()
  {
    //...
  }
  
  public function /* ~Math */ __destruct()
  {
    //..
  }
  
	public function factLoop($n)
	{
		$result = 1;
	   
		for ($index = 2; $index <= $n; $index++)
		{
			$result = $result * $index;
		}
	   
		return $result;
	}

	public function narayana($in, $n)
	{
		$out	 = $in;
		$k	 = $n - 2;
		while ($k >= 0 && $out[$k] >= $out[$k + 1])
		{
		$k--;
		}

		if (-1 == $k)
		{
		return false;
		}

		$t = $n - 1;
		while ($t >= 0 && $t >= $k + 1 && $out[$k] >= $out[$t])
		{
		$t--;
		}

		$tmp	 = $out[$k];
		$out[$k] = $out[$t];
		$out[$t] = $tmp;

		//Шаг 3
		for ($i = $k + 1; $i < ceil(($n + $k) / 2); $i++)
		{
		$t	 = $n + $k - $i;
		$tmp	 = $out[$i];
		$out[$i] = $out[$t];
		$out[$t] = $tmp;
		}

		return $out;
	}
}
?>