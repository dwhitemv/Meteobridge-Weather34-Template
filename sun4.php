<?php include_once('livedata.php');
# modified version of Wim weather display version good for northern latitudes not good for southern latitudes
$result = date_sun_info(time(), $lat, $lon); '<pre>'.time().print_r($result,true); $nextday = time() + 24*60*60; $result2 = date_sun_info($nextday,$lat, $lon); '<pre>'.print_r($result2,true); 
$nextrise = $result['sunrise']; $now = time(); if ($now > $nextrise) { $nextrise = date('H:i',$result2['sunrise']); $nextrisetxt = '<value>Tomorrow';} else { $nextrisetxt = '<value>Today'; $nextrise = date('H:i',$nextrise);} $nextset = $result['sunset']; if ($now > $nextset) { $nextset = date('H:i',$result2['sunset']); $nextsettxt = '<value>Tomorrow';} else { $nextsettxt = '<value>Today'; $nextset = date('H:i',$nextset);} $firstrise = $result['sunrise']; $secondrise = $result2['sunrise']; $firstset = $result ['sunset']; if ($now < $firstrise) { $time = $firstrise - $now; $hrs = gmdate ('G',$time); $min = gmdate ('i',$time); $txt = '<value>Till Sunrise';} elseif ($now < $firstset) { $time = $firstset - $now; $hrs = gmdate ('G',$time); $min = gmdate ('i',$time); $txt = ' &nbsp;<value>Till Sunset';} else { $time = $secondrise - $now; $hrs = gmdate ('G',$time); $min = gmdate ('i',$time); $txt ='<value>Till Sunrise';}echo "</value>";
//sun position based on https://github.com/KiboOst/php-sunPos
class sunPos{public function getSunPos(){$date=clone $this->date;$date->setTimezone(new DateTimeZone('UTC'));$year=$date->format("Y");$month=$date->format("m");$day=$date->format("d");$hour=$date->format("H");$min=$date->format("i");$pos=$this->getSunPosition($this->latitude,$this->longitude,$year,$month,$day,$hour,$min);$this->elevation=$pos[0];$this->azimuth=$pos[1];return array('elevation'=>$pos[0],'azimuth'=>$pos[1]);}public function getDayPeriod(){$ts=$this->date->getTimestamp();$sun_info=date_sun_info($ts,$this->latitude,$this->longitude);$sunrise=date("H:i:s",$sun_info["sunrise"]);$transit=date("H:i:s",$sun_info["transit"]);$sunset=date("H:i:s",$sun_info["sunset"]);$this->sunrise=$sunrise;$this->transit=$transit;$this->sunset=$sunset;$isDay=0;$isMorning=0;$isNoon=0;$isAfternoon=0;$isEvening=0;$now=$this->date->format('H:i:s');if($now>$sunrise and $now<$sunset)$isDay=1;if($isDay==1){if($now<='12:00:00')$isMorning=1;if($now>'12:00:00' and $now<'14:00:00')$isNoon=1;if($isMorning==0 and $isNoon==0){$sunrise=new DateTime($sunrise);$transit=new DateTime($transit);$sunset=new DateTime($sunset);$nowTime=new DateTime($now);$dayLenght=date_diff($sunset,$sunrise);$dayLenght=$dayLenght->h * 60 + $dayLenght->i;$sunsetDelta=date_diff($sunset,$nowTime);$sunsetDelta=$sunsetDelta->h * 60 + $sunsetDelta->i;$portion=pow($dayLenght / 12,2)/ 40;if($sunsetDelta<$portion)$isEvening=1;else $isAfternoon=1;}}$this->isDay=$isDay;$this->isMorning=$isMorning;$this->isNoon=$isNoon;$this->isAfternoon=$isAfternoon;$this->isEvening=$isEvening;}public function isSunny($from=0,$to=0){if(is_null($this->azimuth)){$pos=$this->getSunPos();$this->elevation=$pos['elevation'];$this->azimuth=$pos['azimuth'];}if($to<$from){if($this->azimuth<$to)$this->azimuth+=360;$to+=360;}if($this->azimuth>$from and $this->azimuth<$to)return true;return false;}public function getSunPosition($lat,$long,$year,$month,$day,$hour,$min){$jd=gregoriantojd($month,$day,$year);$dayfrac=$hour / 24 - .5;$frac=$dayfrac + $min / 60 / 24;$jd=$jd + $frac;$time=($jd - 2451545);$mnlong=(280.460 + 0.9856474 * $time);$mnlong=fmod($mnlong,360);if($mnlong<0)$mnlong=($mnlong + 360);$mnanom=(357.528 + 0.9856003 * $time);$mnanom=fmod($mnanom,360);if($mnanom<0)$mnanom=($mnanom + 360);$mnanom=deg2rad($mnanom);$eclong=($mnlong + 1.915 * sin($mnanom)+ 0.020 * sin(2 * $mnanom));$eclong=fmod($eclong,360);if($eclong<0)$eclong=($eclong + 360);$oblqec=(23.439 - 0.0000004 * $time);$eclong=deg2rad($eclong);$oblqec=deg2rad($oblqec);$num=(cos($oblqec)* sin($eclong));$den=(cos($eclong));$ra=(atan($num / $den));if($den<0)$ra=($ra + pi());if($den>=0&&$num<0)$ra=($ra + 2*pi());$dec=(asin(sin($oblqec)* sin($eclong)));$h=$hour + $min / 60;$gmst=(6.697375 + .0657098242 * $time + $h);$gmst=fmod($gmst,24);if($gmst<0)$gmst=($gmst + 24);$lmst=($gmst + $long / 15);$lmst=fmod($lmst,24);if($lmst<0)$lmst=($lmst + 24);$lmst=deg2rad($lmst * 15);$ha=($lmst - $ra);if($ha<pi())$ha=($ha + 2*pi());if($ha>pi())$ha=($ha - 2*pi());$lat=deg2rad($lat);$el=(asin(sin($dec)* sin($lat)+ cos($dec)* cos($lat)* cos($ha)));$az=(asin(-cos($dec)* sin($ha)/ cos($el)));if((sin($dec)- sin($el)* sin($lat))>00){if(sin($az)<0)$az=($az + 2*pi());}else{$az=(pi()- $az);}$el=rad2deg($el);$az=rad2deg($az);$lat=rad2deg($lat);return array(number_format($el,2),number_format($az,2));}public $latitude=null;public $longitude=null;public $date=null;public $timezone=null;public $elevation=null;public $azimuth=null;public $sunrise=null;public $transit=null;public $sunset=null;public $isDay=null;public $isMorning=null;public $isNoon=null;public $isAfternoon=null;public $isEvening=null;protected $dateFormat='Y-m-d';function __construct($latitude=0,$longitude=0,$timezone=false,$date=false,$time=false){$this->latitude=$latitude;$this->longitude=$longitude;if($timezone){$this->timezone=$timezone;date_default_timezone_set($timezone);}else $this->timezone=date_default_timezone_get();if($date)$this->date=DateTime::createFromFormat($this->dateFormat,$date);else $this->date=new DateTime('NOW',new DateTimeZone($this->timezone));if($time){$var=explode(':',$time);$this->date->setTime($var[0],$var[1]);}$this->getSunPos();$this->getDayPeriod();}}$lati=$lat;$long=$lon;$timezone=$TZ;$_SunPos=new sunPos($lati,$long,$timezone);$azimuth=$_SunPos->azimuth ;$elev=$_SunPos->elevation ;

//use meteobridge daylight with some improvements by Josh(milehighweather)
$light =$weather["daylight"]; $daylight = ltrim($light, '0'); $dark = 24 - str_replace(':','.',$daylight);
$lighthours = substr($daylight, 0, 2); $lightmins = substr($daylight, -2);
$darkhours = 23 - $lighthours; $darkminutes = 60 - $lightmins;
if ($darkminutes<10) $darkminutes= '0' .$darkminutes;
else $darkminutes=$darkminutes;
$thehour=date('H');$theminute=date('i');
//convert meteobridge lunar luminance output with a color shade of yellow
if ($weather["luminance"]==100) {$luminance1="<div class=percent100>";}else if ($weather["luminance"]>90) {$luminance1="<div class=percent90>";}else if ($weather["luminance"]>80) {$luminance1="<div class=percent80>";}else if ($weather["luminance"]>70) {$luminance1="<div class=percent70>";}else if ($weather["luminance"]>60) {$luminance1="<div class=percent60>";}else if ($weather["luminance"]>50) {$luminance1="<div class=percent50>";}else if ($weather["luminance"]>40) {$luminance1="<div class=percent40>";}else if ($weather["luminance"]>30) {$luminance1="<div class=percent30>";}else if ($weather["luminance"]>20) {$luminance1= "<div class=percent20>";}else if ($weather["luminance"]>=10) {$luminance1="<div class=percent10>";}else if ($weather["luminance"]>=0) {$luminance1="<div class=percent0>";}?>
<style>.weather34sunclock {-webkit-transform:rotate(<?php echo ((($thehour*15)+($theminute/4))-86)?>deg);transform:rotate(<?php echo ((($thehour*15)+($theminute/4))-86)?>deg);border:5px solid rgba(255, 255,255,0);width:110px; height:110px;top:-9px;margin-left:104px}.weather34sunclock #poscircircle {display:none;}</style>
<?php if($elev>=0){$elev1=$_SunPos->elevation."&deg;<div class=sunaboveweather34>&nbsp;</div>";}else if($elev<0){$elev1=$_SunPos->elevation."&deg;<div class=sunbelowweather34>&nbsp;</div>";}?>
<div class="updatedtime"><?php echo $online.' '.date($timeFormat);?></div>
<div class="daylightmoduleposition" > 
<?php echo '
<div class="sunlightday"><currentdaylight></currentdaylight>&nbsp;&nbsp;<value> '.$daylight.' hrs<br /><period><value>&nbsp;Daylight</period></div>
<div class="sundarkday"><value> '. $darkhours,":", $darkminutes.' hrs&nbsp;<currentdarkness></currentdarkness><br></value><period><value>&nbsp;Darkness</period></div>
<div class="sunriseday"><value>Sunrise&nbsp;<div class=sunup34></div><br><value>'.$nextrisetxt.'&nbsp;'.$nextrise.'</value></div>
<div class="sunsetday"><div class=sundown34></div><value>Sunset<br><value>'.$nextsettxt.' <value>'.$nextset.'</value></div>
<div class="daylightword"><value>Daylight</div><div class="elevationword"><value>Sun Elevation<span><value><maxred> '.$elev1.'</maxred></value></span></div><div class="circleborder"></div>
<div class="sundialcontainerdiv2" ><div id="sundialcontainer" class=sundialcontainer><canvas id="sundial" class="suncanvasstyle"></canvas><div class="weather34sunclock"><div id="poscircircle"></div></div></div>
<div class="daylightvalue1" ><hrs>hrs</hrs><hours>&nbsp;&nbsp;'.$hrs.'</hours> <minutes>'.$min.'</minutes> <br>&nbsp;<period>'.$txt.'</period><min>min</min>
<div class="moonphasem">Moon Phase <span>'.$weather["moonphase"].'</span><currentmoonrise>'.$weather['moonrise'].'</currentmoonrise></div><div class="luminancem">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Luminance<span> '.$weather["luminance"].'% '.$luminance1.'</span><currentmoonset>'.$weather['moonset'].'</currentmoonrise></div></div></div></div>';
$d_crcl = 24*60/2;function clc_crcl ($integer){  global $d_crcl ;$h= (int) date ('H',$integer);$m = (int) date ('i',$integer);$calc = $m + $h*60; $calc= (float) 0.5 + ($calc / $d_crcl );if ($calc > 2.0) { $calc = $calc - 2;}return round ($calc,5);}$start  = clc_crcl ($result['sunrise']);$end    = clc_crcl ($result['sunset']);$pos    = clc_crcl ($now);if ($now > $result['sunset'] || $now < $result['sunrise'] ){$sn_clr = 'rgba(86,95,103,0)';}else {$sn_clr = 'rgba(255, 112,50,1)';}echo '<script>var c = document.getElementById("sundial");var ctx = c.getContext("2d");ctx.imageSmoothingEnabled =false;ctx.beginPath();ctx.arc(63, 65, 55, 0, 2 * Math.PI);ctx.lineWidth = 0;ctx.strokeStyle = "#565f67";ctx.stroke();ctx.beginPath();ctx.arc(63, 65, 55, '.$start.' * Math.PI, '.$end.' * Math.PI);ctx.lineWidth = 2;ctx.strokeStyle ="#3b9cac";ctx.stroke();ctx.beginPath();ctx.arc(63, 65, 55, '.$pos.'* Math.PI, '.$pos.' * Math.PI);ctx.lineWidth = 0;ctx.strokeStyle = "'.$sn_clr.'";ctx.stroke();</script> ';?>