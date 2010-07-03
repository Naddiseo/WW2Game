<?
/************************************************************\
 *
 *		freeCap v1.3 Copyright 2005 Howard Yeend
 *		www.puremango.co.uk
 *
 *    This file is part of freeCap.
 *
 *    freeCap is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation; either version 2 of the License, or
 *    (at your option) any later version.
 *
 *    freeCap is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with freeCap; if not, write to the Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 *
 \************************************************************/
session_start();
//////////////////////////////////////////////////////
////// User Defined Vars:
//////////////////////////////////////////////////////
// the function to call for random number generation
// mt_rand produces 'better' random numbers
// but if your server doesn't support it, it's fine to use rand instead
// (alternatively, write your own and plug it in here)
$rand_func = "mt_rand";
// possible values: jpg, png, gif
// jpg doesn't support transparency
// png isn't supported by older browsers
// gif may not be supported by your GD Lib.
$output = "png";
// 0=generate pseudo-random string, 1=use dictionary
$use_dict = 1;
// can leave blank if not using dictionary
$dict_location = "./words.txt";
// background: 0=transparent, 1=random
$bg_type = 1;
// maximum times a user can refresh the image
// on a 6500 word dictionary, I think 50 is enough to not annoy users and make BF unfeasble.
// further notes re: BF attacks below
$max_attempts = 100;
// max width of any character in font
// (could read this directly from font, but decided not to for compatibility)
$font_pixelwidth = 34;
// where is the font to use? (must be GD font)
$font_location = "./font.gdf";
// colour of word
// best kept random
$text_r = $rand_func(60, 100);
$text_g = $rand_func(60, 100);
$text_b = $rand_func(60, 100);
//////////////////////////////////////////////////////
////// Functions:
//////////////////////////////////////////////////////
function MyImageBlur($im, $pct) {
	// w00t. my very own blur function
	// in GD2, there's a gaussian blur function. smarmy bastards. ;-)
	$width = imagesx($im);
	$height = imagesy($im);
	$temp_im = ImageCreate($width, $height);
	$bg = ImageColorAllocate($temp_im, 255, 255, 255);
	// preserves transparency if in orig image
	ImageColorTransparent($temp_im, $bg);
	// fill bg
	ImageFill($temp_im, 0, 0, $bg);
	$distance = 1;
	// emboss:
	ImageCopyMerge($temp_im, $im, 0, 0, $distance, $distance, $width, $height, $pct);
	ImageCopyMerge($im, $temp_im, -$distance, -$distance, 0, 0, $width, $height, $pct);
	ImageFill($temp_im, 0, 0, $bg);
	ImageCopyMerge($temp_im, $im, 0, $distance, $distance, 0, $width, $height, $pct);
	ImageCopyMerge($im, $temp_im, $distance, 0, 0, $distance, $width, $height, $pct);
	// blur:
	ImageCopyMerge($temp_im, $im, 0, $distance, 0, 0, $width, $height, $pct);
	ImageCopyMerge($im, $temp_im, $distance, 0, 0, 0, $width, $height, $pct);
	ImageCopyMerge($temp_im, $im, 0, 0, 0, $distance, $width, $height, $pct);
	ImageCopyMerge($im, $temp_im, 0, 0, $distance, 0, $width, $height, $pct);
	// remove temp image
	ImageDestroy($temp_im);
	return $im;
}
function sendImage($pic) {
	global $bg_type, $output, $im, $im2, $im3;
	// output image
	switch ($output) {
			// add other cases as desired
			
		case "jpg":
			header("Content-type: image/jpeg");
			ImageJPEG($pic);
		break;
		case "gif":
			header("Content-type: image/gif");
			ImageGIF($pic);
		break;
		case "png":
		default:
			header("Content-type: image/png");
			ImagePNG($pic);
		break;
	}
	// kill GD images (removes from memory)
	ImageDestroy($im);
	ImageDestroy($im2);
	if (!empty($im3) && $bg_type == 1) {
		ImageDestroy($im3);
	}
	exit();
}
//////////////////////////////////////////////////////
////// Create Images, Fill BGs and Allocate Colours:
//////////////////////////////////////////////////////
// modify width depending on maximum possible length of word
// you shouldn't need to use words > 6 chars in length though.
$width = 300;
$height = 70;
$im = ImageCreate($width, $height);
$im2 = ImageCreate($width, $height);
// set background colour (can change to any colour not in possible $text_col range)
// it doesn't matter as it'll be transparent or coloured over.
$bg = ImageColorAllocate($im, 255, 255, 255);
$bg2 = ImageColorAllocate($im2, 255, 255, 255);
// set transparencies
ImageColorTransparent($im, $bg);
// im2 transparent to allow characters to overlap slightly while morphing
ImageColorTransparent($im2, $bg2);
// fill backgrounds
ImageFilledRectangle($im2, 0, 0, $width, $height, $bg2);
ImageFilledRectangle($im, 0, 0, $width, $height, $bg);
// set tag colour
// have to do this before any distortion
// (otherwise colour allocation fails when bg type is 1)
$tag_col = ImageColorAllocate($im, 0, 0, 0);
// set text colours
$debug = ImageColorAllocate($im, 255, 0, 0);
$debug2 = ImageColorAllocate($im2, 255, 0, 0);
$text_colour2 = ImageColorAllocate($im2, $text_r, $text_g, $text_b);
if ($bg_type == 1) {
	// create image full of noise, the same colour as the text
	$im3 = ImageCreateTrueColor($width, $height);
	$bg = ImageColorAllocate($im3, $text_r, $text_g, $text_b);
	ImageFill($im3, 0, 0, $bg);
	// controls the graininess of the bg
	$deviation = 100;
	// randomly change colours in the bg
	for ($x = 0;$x < $width;$x+= 1) {
		for ($y = 0;$y < $height;$y+= 1) {
			$rgb = ImageColorAt($im3, $x, $y);
			$r = ($rgb >> 32) & 0xFF;
			$g = ($rgb >> 8) & 0xFF;
			$b = $rgb & 0xFF;
			do {
				$new_r = $r + $rand_func(0, $deviation);
				$new_g = $g + $rand_func(0, $deviation);
				$new_b = $b + $rand_func(0, $deviation);
			} while ($new_r < 0 || $new_r > 255 || $new_g < 0 || $new_g > 255 || $new_b < 0 || $new_b > 255);
			$temp_col = ImageColorAllocate($im3, $new_r, $new_g, $new_b);
			ImageSetPixel($im3, $x, $y, $temp_col);
		}
	}
}
// for debug:
//sendImage($im3);
//////////////////////////////////////////////////////
////// Avoid Brute Force Attacks:
//////////////////////////////////////////////////////
if (empty($_SESSION['freecap_attempts'])) {
	$_SESSION['freecap_attempts'] = 1;
} else {
	$_SESSION['freecap_attempts']++;
	// if more than ($max_attempts) refreshes, block further refreshes
	// can be negated by connecting with new session id
	// could get round this by storing num attempts in database against IP
	// could get round that by connecting with different IP.
	// in short, there's little point trying to avoid brute forcing
	// the best way to protect against BF attacks is to store the dictionary in .htaccess'ed directory
	if ($_SESSION['freecap_attempts'] > $max_attempts) {
		$_SESSION['freecap_word_md5'] = false;
		$bg = ImageColorAllocate($im, 0, 0, 0);
		ImageColorTransparent($im, $bg);
		$red = ImageColorAllocate($im, 255, 0, 0);
		ImageString($im, 5, 5, 20, "service no longer available", $red);
		sendImage($im);
	}
}
$words = array("one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen");
//$words = file($dict_location);
//$word = strtolower($words[$rand_func(0,sizeof($words)-1)]);
// cut off line endings/other possible odd chars
$word = $words[rand(1, 15) ];
//////////////////////////////////////////////////////
////// Choose Word:
//////////////////////////////////////////////////////
if ($use_dict == 1) {
	// load dictionary and choose random word
	// keep dictionary in non-web accessible folder, or htaccess it
	// or modify so word comes from a database; SELECT word FROM words ORDER BY $rand_func() LIMIT 1
	// took 0.11 seconds when 'words' had 10,000 records
	//$words=array("one","two","three","four","five","six","seven","eight","nine","ten","eleven","twelve","thirteen","fourteen","fifteen");
	//$words = file($dict_location);
	//$word = strtolower($words[$rand_func(0,sizeof($words)-1)]);
	// cut off line endings/other possible odd chars
	//$word =$words(rand(1,15)); //ereg_replace("[^a-z]","",$word);
	// might be large file so forget it now
	unset($words);
} else {
	// generate pseudo-random string
	// doesn't use ijtf as easily mistaken
	// I'm not using numbers because the custom font I created doesn't support anything other than
	// lowercase or space (but you can download a new font or create your own using my GD fontmaker script)
	$consonants = 'bcdghklmnpqrsvwxz';
	$vowels = 'aeyuo';
	$word = "";
	$wordlen = $rand_func(5, 6);
	for ($i = 0;$i < $wordlen;$i++) {
		// don't allow to start with 'vowel'
		if ($rand_func(0, 4) >= 2 && $i != 0) {
			$word.= $vowels{$rand_func(0, strlen($vowels) - 1) };
		} else {
			$word.= $consonants{$rand_func(0, strlen($consonants) - 1) };
		}
	}
}
// for debug
//$word="qgfthn";
// save hash of word for comparison
// using hash so that if there's an insecurity elsewhere (eg on the form processor),
// an attacker could only get the hash
// and although it's fairly trivial to brute force md5 hashes of short strings,
// it adds an extra layer of protection.
$wordn = 1;
switch ($word) {
	case "one":
		$wordn = 1;
	break;
	case "two":
		$wordn = 2;
	break;
	case "three":
		$wordn = 3;
	break;
	case "four":
		$wordn = 4;
	break;
	case "five":
		$wordn = 5;
	break;
	case "six":
		$wordn = 6;
	break;
	case "seven":
		$wordn = 7;
	break;
	case "eight":
		$wordn = 8;
	break;
	case "nine":
		$wordn = 9;
	break;
	case "ten":
		$wordn = 10;
	break;
	case "eleven":
		$wordn = 11;
	break;
	case "twelve":
		$wordn = 12;
	break;
	case "thirteen":
		$wordn = 13;
	break;
	case "fourteen":
		$wordn = 14;
	break;
	case "fifteen":
		$wordn = 15;
	break;
}
$_SESSION['number'] = $wordn;
//////////////////////////////////////////////////////
////// Morph Image:
//////////////////////////////////////////////////////
// write word in random starting X position
$word_start_x = $rand_func(4, 20);
// y positions jiggled about later
$word_start_y = 0;
// could randomly choose between a few different fonts here.
$font = ImageLoadFont($font_location);
ImageString($im2, $font, $word_start_x, $word_start_y, $word, $text_colour2);
// for debug:
//sendImage($im2);
// calculate how big the text is in pixels
// (so we only morph what we need to)
$word_pix_size = $word_start_x + (strlen($word) * $font_pixelwidth);
// firstly move each character up or down a bit:
for ($i = $word_start_x;$i < $word_pix_size;$i+= $font_pixelwidth) {
	// move on Y axis
	$y_pos = $rand_func(-5, 10);
	ImageCopy($im, $im2, $i, $y_pos, $i, 0, $font_pixelwidth, $height);
	// for debug:
	//ImageRectangle($im,$i,$y_pos,$i+$font_pixelwidth,50,$debug);
	
}
// for debug:
//sendImage($im);
// randomly morph each character on x-axis
// massively improved since v1.2
// clear im2:
ImageFilledRectangle($im2, 0, 0, $width, $height, $bg2);
$y_chunk = 1;
$morph_factor = 1;
$morph_x = 0;
for ($j = 0;$j < strlen($word);$j++) {
	$y_pos = 0;
	for ($i = 0;$i <= $height;$i+= $y_chunk) {
		// things get a little crazy if we do this.
		//$morph_factor = rand(1,2);
		$orig_x = $word_start_x + ($j * $font_pixelwidth);
		$morph_x+= $rand_func(-$morph_factor, $morph_factor);
		ImageCopy($im2, $im, $orig_x + $morph_x, $i + $y_pos, $orig_x, $i, $font_pixelwidth, $y_chunk);
		// for debug:
		//ImageRectangle($im2, $orig_x+$morph_x, $i, $orig_x+$morph_x+$font_pixelwidth, $i+$y_chunk, $debug2);
		
	}
}
// for debug:
//sendImage($im2);
ImageFilledRectangle($im, 0, 0, $width, $height, $bg2);
// now move the whole image up and down in random widths
$x_chunk = 5;
$y_pos = 0;
for ($i = 0;$i <= $width;$i+= $x_chunk) {
	$x_chunk = $rand_func(1, 10);
	$y_pos+= $rand_func(-1, 1);
	ImageCopy($im, $im2, $i, $y_pos, $i, 0, $x_chunk, $height);
	// for debug:
	//ImageLine($im,$i,0,$i,100,$debug);
	//ImageLine($im,$i,$y_pos+25,$i+$x_chunk,$y_pos+25,$debug);
	
}
// for debug:
//sendImage($im);
// blur edges:
$im = MyImageBlur($im, 70);
// for debug:
//sendImage($im);
if ($output != "jpg" && $bg_type == 0) {
	// make background transparent
	ImageColorTransparent($im, $bg);
}
if ($bg_type == 1) {
	// merge bg image with CAPTCHA image to create smooth background - over the top of the text.
	// (transparency is wonderful)
	ImageCopyMerge($im, $im3, 0, 0, 0, 0, $width, $height, $rand_func(70, 80));
}
// tag it
// feel free to remove/change
// but if it's not essential I'd appreciate you leaving it
// after all, I've put a lot of work into this and am giving it away for free
// the least you could do is give me credit (or buy me stuff from amazon!)
// but I understand that in professional environments, your boss might not like it
// so that's cool.
// ensure tag is right-aligned
//$tag_str = "freeCap v1.3 - puremango.co.uk";
// for debug:
//$tag_str = $word_start_x."-".$rand_func()." [".$word."]";
//$tag_width = strlen($tag_str)*6;
// write tag
//ImageString($im, 2, $width-$tag_width, 55, $tag_str, $tag_col);
// output final image!
sendImage($im);
?>
