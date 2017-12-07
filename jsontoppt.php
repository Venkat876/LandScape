<?php
	
	$xmldata = file_get_contents('../misc/raw/content.xml');
	$placeHolder_page = '';
	$placeHolder_style = '';
	$placeHolder_content = '';
	$placeHolder_paragraph = '';
	$placeHolder_entireppt = '';
	$placeholder_page_ending = '';

	//$content = '';
	//$y_coord=0;
	/*for($i=0;$i<4;$i++) {
		$x_coord = 1;
		$y_coord = $y_coord + 1;
		$width = 2.5;
		$height = 1;
		$text_value = 'Something'.$i;
		
		$content .= '<draw:custom-shape draw:style-name="gr1" draw:text-style-name="P1" draw:layer="layout" svg:width="'.$width.'cm" svg:height="'.$height.'cm" svg:x="'.$x_coord.'cm" svg:y="'.$y_coord.'cm"><text:p text:style-name="P1">'.$text_value.'</text:p><draw:enhanced-geometry svg:viewBox="0 0 21600 21600" draw:type="rectangle" draw:enhanced-path="M 0 0 L 21600 0 21600 21600 0 21600 0 0 Z N"/></draw:custom-shape>'; 
	}
*/	

	/*print_r($xmldata);
	die('asf');*/
?>

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
	$json = file_get_contents('./Landscape_Sample_Data20.js');
	$data = json_decode($json,true);
	foreach($data as $newdata) {
		  
		$sno = 0;  //this is slide number.
		$slide[] = array(); // 2d array to have elements, here index is the slide no 
		$staticSpace = 25;  // the maximum number of elements we can accommodate in one slide.
		$space = $staticSpace;  //max no of elements that we can accommodate in slide 
		$limit = 25;  //when there are more than 35 elements in one phase, limit to 35 and rest will be in the next slide.
		$count = 0;   //to count the elements in the slide.
		$elementsInPhase = 0 ; // total no of elements in that particular phase. 
		$single_slide_for_phase = 20; // if a phase is having more than 25 elements it will be having different slide.
		$row = 5 ; // No of Rows in a slide.
					//other varibles like data,newdata, phases are to get the data in the required format.

		foreach($newdata as $phases) {
			foreach ($phases as $phaseName => $phase ) {
				echo count($phase)."<br />";
				$elementsInPhase = count($phase);
				if($elementsInPhase > $single_slide_for_phase )
				{
					$sno++;
					$count = 0;
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
					$space = $staticSpace;
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

$slideno = 1 ;//this for slide no in ppt 
$gr = 1; // for gr varible in ppt 
$p = 1;



foreach($slide as $slideContain) {
	$placeHolder_page = '<draw:page draw:name="page'.$slideno.'" draw:style-name="dp1" draw:master-page-name="Default" presentation:presentation-page-layout-name="AL1T0">';
	$placeHolder_frame = '<draw:frame presentation:style-name="pr1" draw:layer="layout" svg:width="25.199cm" svg:height="3.506cm" svg:x="1.4cm" svg:y="0.837cm" presentation:class="title" presentation:user-transformed="true"><draw:text-box><text:p><text:s/></text:p></draw:text-box></draw:frame><draw:frame presentation:style-name="pr2" draw:text-style-name="P2" draw:layer="layout" svg:width="25.199cm" svg:height="12.179cm" svg:x="1.4cm" svg:y="4.914cm" presentation:class="subtitle" presentation:user-transformed="true"><draw:text-box><text:p><text:s/></text:p></draw:text-box></draw:frame>';
	$placeHolder_content = '';
	//$placeholder_presentation_notes = '';
	$phase = $slide[0][0]['phase']; //phase will hold phase name i.e .. Phase I Phase II etc.. 
	
	$top = $flag1 ? ($top +  $height) : 0;  // $top and $topFixed are variables for setting elements in there absolute position.
	$topFixed = $top; 
	$flag1 = 1; // flag to set the initial height.
	$flag2 = 0; // flag to set the initail left distance from the screen.
	$row = 0; $maxRow = 6;   //how many row to present in a slide.
	$left = 0;  // position of element from the left edge of the screen.
    $x_coord = 0.5; // x and y co-orinates of ppt .
    $y_coord = 15; 
   
	//$placeHolder_page = '';

	for ($j=0; $j < count($slideContain)  ; $j++) { 
	 	$name = $slideContain[$j]['name'];  // hold the content of the element.
	 	$moa = $slideContain[$j]['moa'][0]; // hold the content of the element.
	 	$boxData = $name ."<br />" .$moa; // combined data to present in the box or div.
	 	$color = $slideContain[$j]['color'][0]; // color of the box or div.

	 	if($row < $maxRow && $slideContain[$j]['phase'] == $phase) {
	 		?>
	 			<div style="background-color: <?php echo $color ?>; top : <?php echo $top.$px ?> ; left: <?php echo $left.$px ?> ">
	 			 	<?php echo $boxData ?>
	 			</div>

	 		<?php
			$placeHolder_content .= '<draw:custom-shape draw:style-name="gr'.$gr.'" draw:text-style-name="P'.$p.'" draw:layer="layout" svg:width="4.191cm" svg:height="2.032cm" svg:x="'.$x_coord.'cm" svg:y="'.$y_coord.'cm"><text:p text:style-name="P1">'.$name.'</text:p><text:p text:style-name="P1"><text:span text:style-name="T1">'.$moa.'</text:span></text:p><draw:enhanced-geometry svg:viewBox="0 0 21600 21600" draw:type="rectangle" draw:enhanced-path="M 0 0 L 21600 0 21600 21600 0 21600 0 0 Z N"/></draw:custom-shape>';

			$placeHolder_style .= '<style:style style:name="gr'.$gr.'" style:family="graphic" style:parent-style-name="standard"><style:graphic-properties draw:fill-color="'.$color.'" draw:textarea-horizontal-align="justify" draw:textarea-vertical-align="middle" draw:auto-grow-height="false" fo:wrap-option="wrap" fo:min-height="1.782cm" fo:min-width="4.326cm"/></style:style>';

			$placeHolder_paragraph .= '<style:style style:name="P'.$p.'" style:family="paragraph"><loext:graphic-properties draw:fill-color="'.$color.'"/>
				<style:paragraph-properties fo:text-align="center"/><style:text-properties fo:font-size="12pt"/></style:style>';

         
			$gr++;
			$p++;
			$y_coord = $y_coord - 2;
	 		$row++;
	 		$top = $top + 104;
	 
	 	}	else {
	 		if($phase != $slideContain[$j]['phase'])
	 		{
	 			$left = $left + 10;
	 			$x_coord = $x_coord + 0.4;
	 		}
	 		$phase = $slideContain[$j]['phase'];
	 		$row = 1;
	 		$top = $topFixed;
	 		$left = $flag2 ? $left + 154 : 0;

	 		$x_coord = $flag2 ? $x_coord + 4.55 : 0.5;
	 		$y_coord = 15;
	 		?>

	 		<div style="background-color: <?php echo $color ?>; top : <?php echo $top.$px ?> ; left: <?php echo $left.$px ?> ">
	 			 <?php echo $boxData ?>
	 		</div>
	 		<?php
	 		$placeHolder_content .= '<draw:custom-shape draw:style-name="gr'.$gr.'" draw:text-style-name="P'.$p.'" draw:layer="layout" svg:width="4.191cm" svg:height="2.032cm" svg:x="'.$x_coord.'cm" svg:y="'.$y_coord.'cm"><text:p text:style-name="P1">'.$name.'</text:p><text:p text:style-name="P'.$p.'"><text:span text:style-name="T1">'.$moa.'</text:span></text:p><draw:enhanced-geometry svg:viewBox="0 0 21600 21600" draw:type="rectangle" draw:enhanced-path="M 0 0 L 21600 0 21600 21600 0 21600 0 0 Z N"/></draw:custom-shape>';
			
			$placeHolder_style .= '<style:style style:name="gr'.$gr.'" style:family="graphic" style:parent-style-name="standard"><style:graphic-properties draw:fill-color="'.$color.'" draw:textarea-horizontal-align="justify" draw:textarea-vertical-align="middle" draw:auto-grow-height="false" fo:wrap-option="wrap" fo:min-height="1.782cm" fo:min-width="4.326cm"/></style:style>';

			$placeHolder_paragraph .= '<style:style style:name="P'.$p.'" style:family="paragraph"><loext:graphic-properties draw:fill-color="'.$color.'" /><style:paragraph-properties fo:text-align="center"/><style:text-properties fo:font-size="12pt"/></style:style>';

	 		$top = $top + 104;
	 		$y_coord = 13;
	 		$gr++;
	 		$p++;
	 		
	 		
	 	}
	 	$flag2 = 1;

	}
    $slideno++;
	$placeholder_page_ending = '</draw:page>';

	$placeHolder_entireppt .= $placeHolder_page . $placeHolder_frame. $placeHolder_content . $placeholder_page_ending;
}

$xml_content1 = str_replace("[[[placeHolder_style]]]", $placeHolder_style, $xmldata);
$xml_content2 = str_replace("[[[placeHolder_paragraph]]]",$placeHolder_paragraph , $xml_content1);
$xml_content3 = str_replace("[[[placeHolder_entireppt]]]", $placeHolder_entireppt, $xml_content2);

//print_r($xml_content3);
//die();	
file_put_contents('content.xml', $xml_content3);

$filename = date("Y-H-i-s") . '.odp';
echo $filename;
`cp content.xml production; cd production ; zip -r ./final/$filename *`;
	
?>
</body>
</html>