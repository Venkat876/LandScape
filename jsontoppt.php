
<!DOCTYPE html>
<html>
<head>

	<title>
		
	</title>

	<style>
		div {
			width: 150px;
			height: 100px;
			position: absolute;
			margin: 10px;
		    border: 2px solid black;
		    border-collapse: collapse;
		    align-content: center;
		    text-align: center;
		    border-radius: 7px;
		}
    </style>

</head>
<body>
 <?php
	$json = file_get_contents('./Landscape_Sample_Data.js');
	$data = json_decode($json,true);
	foreach($data as $newdata) {
		  
		$sno = 0;  //this is slide number.
		$slide[] = array(); // 2d array to have elements, here index is the slide no 
		$space = 35;  // the maximum number of elements we can accommodate in one slide.
		$limit = 35;  //when there are more than 35 elements in one phase, limit to 35 and rest will be in the next slide.
		$count = 0;   //to count the elements in the slide.
		$elementsInPhase = 0 ; // total no of elements in that particular phase. 
		$single_slide_for_phase = 26; // if a phase is having more than 25 elements it will be having different slide.
		$row = 5 ; // No of Rows in a slide.
					//other varibles like data,newdata, phases are to get the data in the required format.

		foreach($newdata as $phases) {
			foreach ($phases as $phaseName => $phase ) {
				echo count($phase)."<br>";
				$elementsInPhase = count($phase);
				if($elementsInPhase > $single_slide_for_phase )
				{
					for ($i=0; $i < $elementsInPhase; $i++) { 
							if($count == $limit)
							{
								$count = 0;
								$sno++;
							}
							$phase[$count]['phase'] = $phaseName;
							$slide[$sno][$count] = $phase[$count];
							$count++;
					}
				
				}
				else if($space - count($slide[$sno]) >= $elementsInPhase )
				{
					foreach($phase as $product) {
						$product['phase'] = $phaseName;
						$slide[$sno][]= $product; 					
					}
					if($elementsInPhase % $row)
					{
						for($i = 0 ; $i < $row - ($elementsInPhase % $row ) ; $i++)
						{
							$space--;
						}
					}
				}
				else
				{
					$sno++;
					$space = 35;
					$slide[$sno] = array();
					foreach($phase as $product) {
						$product['phase'] = $phaseName;
						$slide[$sno][]= $product; 					
					}
					if($elementsInPhase % $row)
					{
						for($i = 0 ; $i < $row - ($elementsInPhase % $row ) ; $i++)
						{
							$space--;
						}
					}
				}
			}
		}
	}

$px = 'px'; // this is pixel string that is appended with size.
$height = 500; // after displaying a slide it has to display the next slide after this hight.
$flag1 = 0; // flag for setting the initial hight.
$phase = $slide[0][0]['phase']; //phase will hold phase name i.e .. Phase I Phase II etc.. 

foreach($slide as $slideContain){
	
	$top = $flag1 ? ($top +  $height) : 0;  // $top and $topFixed are variables for setting elements in there absolute position.
	$topFixed = $top; 
	$flag1 = 1; // flag to set the initial height.
	$flag2 = 0; // flag to set the initail left distance from the screen.
	$row = 0; $maxRow = 5;   //how many row to present in a slide.
	$left = 0;  // position of element from the left edge of the screen.

	for ($j=0; $j < count($slideContain)  ; $j++) { 
	 	
	 	$name = $slideContain[$j]['name'];  // hold the content of the element.
	 	$moa = $slideContain[$j]['moa'][0]; // hold the content of the element.
	 	$boxData = $name ."<br>" .$moa; // combined data to present in the box or div.
	 	$color = $slideContain[$j]['color'][0]; // color of the box or div.

	 	if($row < $maxRow && $slideContain[$j]['phase'] == $phase)
	 	{
	 		?>
	 			<div style="background-color: <?php echo $color ?>; top : <?php echo $top.$px ?> ; left: <?php echo $left.$px ?> ">
	 			 	<?php echo $boxData ?>
	 			</div>

	 		<?php
	 		
	 		$row++;
	 		$top = $top + 104;
	 
	 	}
	 	else
	 	{
	 		if($phase != $slideContain[$j]['phase'])
	 		{
	 			$left = $left + 10;
	 		}
	 		$phase = $slideContain[$j]['phase'];
	 		$row = 1;
	 		$top = $topFixed;
	 		$left = $flag2 ? $left + 154 : 0;
	 		?>
	 		<div style="background-color: <?php echo $color ?>; top : <?php echo $top.$px ?> ; left: <?php echo $left.$px ?> ">
	 			 <?php echo $boxData ?>
	 		</div>
	 		<?php
	 		$top = $top + 104;
	 		
	 	}
	 	$flag2 = 1;
	}
}
	
?>
</body>
</html>