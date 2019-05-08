<?php
	
	####################################################################################################
	#	DATACHARTS by BRIAN UNDERDOWN 2016-2019                                                        #
	#	CREATED FOR HOMEWEATHERSTATION TEMPLATE at https://weather34.com/homeweatherstation/index.html # 
	# 	                                                                                               #
	# 	built on CanvasJs  	                                                                           #
	#   canvasJs.js is protected by CREATIVE COMMONS LICENCE BY-NC 3.0  	                           #
	# 	free for non commercial use and credit must be left in tact . 	                               #
	# 	                                                                                               #
	# 	Weather Data is based on your PWS upload quality collected at Weather Underground 	           #
	# 	                                                                                               #
	# 	Second General Release: 4th October 2016  	                                                   #
	# 	                                                                                               #
	#   https://www.weather34.com 	                                                                   #
	####################################################################################################
	
	include('chartslivedata.php');include('./chart_theme.php');header('Content-type: text/html; charset=utf-8');
	
$conv = 1;
	if ($rainunit == 'in') {
		$conv = '0.0393701';
	} else if ($rainunit == 'mm') {
		$conv = '1';
	}

	if ($rainunit == 'mm'){
		$raindecimal = '1';
	} else {
		$raindecimal = '2';
	}

    echo '
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title>Rainfall Today DATABASE CHART</title>	
		<script src=../js/jquery.js></script>
		
		
	';
	
	$date= date('D jS Y');$weatherfile = date('dmY');?>
    <br>
    	<script type="text/javascript">
		// today temperature
        $(document).ready(function () {
		var dataPoints1 = [];
		var dataPoints2 = [];
		$.ajax({
			type: "GET",
			url: "result.csv",
			dataType: "text",
			cache:false,
			success: function(data) {processData1(data),processData2(data);}
		});
	
	function processData1(allText) {
		var allLinesArray = allText.split('\n');
		if(allLinesArray.length>0){
			
			for (var i = 2; i <= allLinesArray.length-1; i++) {
				var rowData = allLinesArray[i].split(',');
				if ( rowData.length >1)
					dataPoints1.push({label: rowData[1],y:parseFloat(rowData[4]*<?php echo $conv;?>)});
			}
		}
		requestTempCsv();}function requestTempCsv(){}

	function processData2(allText) {
		var allLinesArray = allText.split('\n');
		if(allLinesArray.length>0){
			
			for (var i = 2; i <= allLinesArray.length-1; i++) {
				var rowData = allLinesArray[i].split(',');
				if ( rowData.length >1)
					dataPoints2.push({label: rowData[1],y:parseFloat(rowData[10]*<?php echo $conv;?>)});
				
			}
			drawChart(dataPoints1 , dataPoints2 );
		}
	}

		function drawChart( dataPoints1 , dataPoints2 ) {
		var chart = new CanvasJS.Chart("chartContainer2", {
		 backgroundColor: '<?php echo $backgroundcolor;?>',
		 animationEnabled: true,
      animationDuration: <?php echo $animationduration;?>,
		 margin: 0,
		
		title: {
            text: " ",
			fontSize: 0,
			fontColor: '<?php echo $fontcolor;?>',
			fontFamily: "arial",
        },
		toolTip:{
			   fontStyle: "normal",
			   cornerRadius: 4,
			   backgroundColor: '<?php echo $backgroundcolor;?>',
			   contentFormatter: function(e) {
					var str = '<span style="color: <?php echo $fontcolor;?>;">' + e.entries[0].dataPoint.label + '</span><br/>';
					for (var i = 0; i < e.entries.length; i++) {
						var temp = '<span style="color: ' + e.entries[i].dataSeries.color + ';">' + e.entries[i].dataSeries.name + '</span> <span style="color: <?php echo $fontcolor;?>;">' + e.entries[i].dataPoint.y.toFixed(2) + "<?php echo ' '.$rainunit ;?>" + '</span> <br/>';
						str = str.concat(temp);
					}
					return (str);
				},
				shared: true,
			   
    
 },
		axisX: {
			gridColor: '<?php echo $gridcolor;?>',
		    labelFontSize: 8,
			labelFontColor: '<?php echo $fontcolorsmall;?>',
			lineThickness: 0.5,
			gridThickness: 1,	
			titleFontFamily: "arial",	
			labelFontFamily: "arial",	
			gridDashType: "dot",
   			intervalType: "hour",
			minimum:0,
        margin: 5,
			
			},
			
		axisY:{
		title: "",
		titleFontColor: '<?php echo $fontcolor;?>',
		titleFontSize: 6,
        titleWrap: false,
		margin: 3,
		interval:0.5,
		lineThickness: 1,		
		gridThickness: 0,	
		gridDashType: "dot",	
        includeZero: true,
		gridColor: '<?php echo $gridcolor;?>',
		labelFontSize: 8,
		labelFontColor: '<?php echo $fontcolorsmall;?>',
		titleFontFamily: "arial",
		labelFontFamily: "arial",
		labelFormatter: function ( e ) {
         return e.value .toFixed(<?php echo $raindecimal;?>) +" <?php echo $rainunit ;?>" ;  
         },		 
// 		crosshair: {
// 			enabled: true,
// 			snapToDataPoint: true,
// 			color: '<?php echo $xcrosshaircolor;?>',
// 			labelFontColor: "#F8F8F8",
// 			labelFontSize:8,
// 			labelBackgroundColor: '<?php echo $xcrosshaircolor;?>',
// 			valueFormatString: "#0.#<?php echo $rainunit ;?>",
// 		}	 
      },
	  
	     axisY2:{
		title: "",
		titleFontColor: '<?php echo $fontcolor;?>',
		titleFontSize: 8,
        titleWrap: false,
		margin: 3,
		interval:'auto',
		
		lineThickness: 1,		
		gridThickness: 1,	
		gridDashType: "dot",	
        includeZero: true,
		gridColor: '<?php echo $gridcolor;?>',
		labelFontSize: 8,
		labelFontColor: '<?php echo $fontcolorsmall;?>',
		titleFontFamily: "arial",
		labelFontFamily: "arial",
		labelFormatter: function ( e ) {
         return e.value .toFixed(<?php echo $raindecimal;?>) + " <?php echo $rainunit ?>" ;  
		},
// 		crosshair: {
// 			enabled: true,
// 			snapToDataPoint: true,
// 			color: '<?php echo $ycrosshaircolor;?>',
// 			labelFontColor: '<?php echo $fontcolor;?>',
// 			labelFontSize:12,
// 			labelBackgroundColor: '<?php echo $ycrosshaircolor;?>',
// 			valueFormatString: "#0.# '<?php echo $rainunit ?>'",
// 		}	 
      },
	  
	  legend:{
      fontFamily: "arial",
      fontColor: '<?php echo $fontcolor;?>',
  
 },
		
		
		
		data: [
		{
			type: "splineArea",
				color: '<?php echo $line2color;?>',
				markerSize:2,
				markerColor: '<?php echo $line2markercolor;?>',
				showInLegend:false,
				lineThickness: 2,
				//lineColor: '<?php echo $line2markercolor;?>',
				markerType: "circle",
				name:"Rainfall",
				dataPoints: dataPoints1,
				yValueFormatString: "#0.# <?php echo $rainunit ;?>",
		},
		{
			type: "spline",
			color: '<?php echo $line1color;?>',
				markerSize:2,
				showInLegend:false,
				axisYType: "secondary",
				axisYIndex: 2,
				lineThickness: 2,
				markerType: "circle",
				name:"Rain Rate",
				dataPoints: dataPoints2,
				yValueFormatString: "#0.# <?php echo $rainunit ;?>",
		}

		]
		});

		chart.render();
	}
});


    </script>

<body>
<div id="chartContainer2" class="chartContainer2" style="width:100%;height:125px;padding:0;margin-top:-25px;border-radius:3px;border: 1px solid rgba(245, 247, 252,.02);
  box-shadow: 2px 2px 6px 0px  rgba(0,0,0,0.6);"></div></div>

</body>
<script src='canvasJs.js'></script>
</html>