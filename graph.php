<?php

// Load the dimensions and break into arrays

$array = file("dimensions.cfg");
$show = $_GET['show'];
$unit = $_GET['unit'];

foreach($array as $line)
	{
	$array_ex = explode(' ',$line);
	$array_key = explode('|',$array_ex[0]);
	$dimensions[$array_key[0]][$array_key[1]] = $array_ex[1];
	}

$image = $dimensions['image'];
$graphbox = $dimensions['graphbox'];
$graphbox[bottom] = $graphbox[top] + $graphbox[height];
$graphbox[right] = $graphbox[left] + $graphbox[width];

if( $show != 1 )
{
echo "unit = $unit (set week, month, day as a get to show this) ";
echo "<pre>\n";
echo "------- image -------\n";
print_r($image);
echo "------ graphbox -------\n";
print_r($graphbox);
echo "</pre>\n";
}
/// render image and set colors
$im = imagecreatetruecolor($image[width],$image[height]);
$white = imagecolorallocate($im,255,255,255);
$red = imagecolorallocate($im,255,0,0);
$green = imagecolorallocate($im,0,255,0);
$black = imagecolorallocate($im,0,0,0);

/// render graphbox
imagerectangle($im, $graphbox[left],$graphbox[top], $graphbox[right], $graphbox[bottom],$white);

// Calculate the sections

/// Monthbox

if ($unit == 'month' )
{
$monthbox[width] = $graphbox[width] / 31;
$monthbox[tic] = $monthbox[width]/4;
$monthbox[ticheight] = 3;

for ($x = 0 ; $x < 31 ; $x++)
        {
        $line = $graphbox[left] + ($monthbox[width] * $x);
        imageline($im,$line,$graphbox[top],$line,$graphbox[bottom],$green);
	for ($y = 0; $y < 5; $y = $y + 1) // Draw the tick marks under the bottom 
		{
		if(($y == 0) || ($y == 2))$monthbox[ticheight] = 5;
			else
				$monthbox[ticheight] = 3;
		imageline($im,$line+$y*$monthbox[tic],$graphbox[bottom],$line+$y*$monthbox[tic],$graphbox[bottom]+$monthbox[ticheight],$white);
		}
	ImageString($im,4,$line+3,$graphbox[bottom]+3,$x+1,$white);
        if( $show != 1 )
                {
                echo "<pre> $x monthbox[width] = $month[width] : line = $line</pre>";
                }
        }
} //// End of if


/// Weekbox

if ($unit == 'week' )
{
$daynames = array ( 'Mon','Tue','Wed','Thu','Fri','Sat','Sun' );
$weekbox[width] = $graphbox[width] / 7;

for ($x = 0 ; $x < 7 ; $x++)
	{
	$line = $graphbox[left] + ($weekbox[width] * $x);
	imageline($im,$line,$graphbox[top],$line,$graphbox[bottom],$white);
        ImageString($im,4,$line+5,$graphbox[bottom]+3,$daynames[$x],$white);
	if( $show != 1 )
		{
		echo "<pre> $x weekbox[width] = $weekbox[width] : line = $line</pre>";
		}
	}
} //// End of if

/// Daybox

if ($unit == 'day')
{
$daybox[width] = $graphbox[width] / 24;

for ($x = 0 ; $x < 24; $x++)
	{
	$line = $graphbox[left] + ($daybox[width] * $x);
	imageline($im,$line,$graphbox[top],$line,$graphbox[bottom],$red);
        ImageString($im,4,$line+5,$graphbox[bottom]+3,$x,$white);
	if ( $show != 1 )
		{
		echo "<pre> $x daybox[width] = $daybox[width] : line = $line</pre>";
		}
	}
} //// End of if 

/// Allows debugging throughout

if( $show == 1)
	{
	header("Content-type: image/png");
	imagepng($im);
	imagedestroy($im);
	}

?>
