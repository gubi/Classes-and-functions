<?php
// ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~
//  
//  Copyright (C) 2012  Alessandro Gubitosi
//  
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, either version 3 of the License, or
//  (at your option) any later version.
//  
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//  
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
//
// Lecense URI: http://www.gnu.org/licenses/gpl-3.0.txt
//
// ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~ ~

header("Content-type: text/plain");
require_once("blowfish.php");
class Captcha {
	function random_string($len){
		$chars = array("A", "B", "C", "D", "E", "F", "G",
			"H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
			"S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
			"3", "4", "5", "6", "7", "8", "9");
		for($i = 0; $i < $len; ++$i){
			shuffle($chars);
			$string[] = $chars[0];
		}
		return $string;
	}
	function wave_area($img, $x, $y, $width, $height, $amplitude = 10, $period = 10){
		// Make a copy of the image twice the size
		$height2 = $height * 2;
		$width2 = $width * 2;
		$img2 = imagecreatetruecolor($width2, $height2);
		imagecopyresampled($img2, $img, 0, 0, $x, $y, $width2, $height2, $width, $height);
		if($period == 0) $period = 1;
		// Wave it
		for($i = 0; $i < ($width2); $i += 2)
		imagecopy($img2, $img2, $x + $i - 2, $y + sin($i / $period) * $amplitude, $x + $i, $y, 2, $height2);
		// Resample it down again
		imagecopyresampled($img, $img2, $x, $y, 0, 0, $width, $height, $width2, $height2);
		imagedestroy($img2);
	}
	function convert($name){
		$conversion_table = array(
			"0" => "{1}{2}{3}\r\n{4} {6}\r\n{7} {9}\r\n{10} {12}\r\n{13}{13}{15}",
			"1" => "  {3}\r\n  {6}\r\n  {9}\r\n  {12}\r\n  {15}",
			"2" => "{1}{2}{3}\r\n  {6}\r\n{7}{8}{9}\r\n{10}  \r\n{13}{14}{15}",
			"3" => "{1}{2}{3}\r\n  {6}\r\n{7}{8}{9}\r\n  {12}\r\n{13}{14}{15}",
			"4" => "{1} {3}\r\n{4} {6}\r\n{7}{8}{9}\r\n  {12}\r\n  {15}",
			"5" => "{1}{2}{3}\r\n{4}  \r\n{7}{8}{9}\r\n  {12}\r\n{13}{14}{15}",
			"6" => "{1}{2}{3}\r\n{4}  \r\n{7}{8}{9}\r\n{10} {12}\r\n{13}{14}{15}",
			"7" => "{1}{2}{3}\r\n  {6}\r\n  {9}\r\n  {12}\r\n  {15}",
			"8" => "{1}{2}{3}\r\n{4} {6}\r\n{7}{8}{9}\r\n{10} {12}\r\n{13}{13}{15}",
			"9" => "{1}{2}{3}\r\n{4} {6}\r\n{7}{8}{9}\r\n  {12}\r\n{13}{13}{15}",
			"+" => "   \r\n {5} \r\n{7}{8}{9}\r\n {11} \r\n   ",
			"-" => "   \r\n   \r\n{7}{8}{9}\r\n   \r\n   ",
			"*" => "   \r\n{4} {6}\r\n {8} \r\n{10} {11}\r\n   ",
			"=" => "   \r\n{4}{5}{6}\r\n   \r\n{7}{8}{9}\r\n   \r\n"
		);
		return $conversion_table[$name];
	}
	function minus($numero){
		$numero = (int)$numero;
		$numero2 = rand(1, 9);
		if(($numero - $numero2) > 0) {
			return $numero2;
		} else {
			return @minus($numero);
		}
	}
	function division($numero){
		$numero = (int)$numero;
		$numero2 = rand(1, 9);
		if(($numero/$numero2) > 0 && is_decimal($numero/$numero2)) {
			return $numero2;
		} else {
			return @division($numero);
		}
	}
	function generate_captcha($GLOBALS["key"] = "captcha") {
		$operatori = Array("+", "*", "-");
		shuffle($operatori);

		$numero = rand(1, 9);
		$l_operatore = $operatori[0];
		switch ($l_operatore){
			case "+":
				$numero2 = rand(1, 9);
				$risultato = (int)$numero+(int)$numero2;
				break;
			case "*":
				$numero2 = rand(1, 4);
				$risultato = (int)$numero*(int)$numero2;
				break;
			case "-":
				$numero2 = @minus($numero);
				$risultato = (int)$numero-(int)$numero2;
				break;
		}
		setcookie("rs", PMA_blowfish_encrypt($risultato, $GLOBALS["key"]), time()+600, "/");
		
		$num = preg_split("//", $numero, -1, PREG_SPLIT_NO_EMPTY);
		$num2 = preg_split("//", $numero2, -1, PREG_SPLIT_NO_EMPTY);
		
		// Captcha conversion
			// First digit
			foreach($num as $nn){
				$this->template .= $this->convert($nn);
			}
			foreach($this->random_string(15) as $k => $v){
				$k = $k+1;
				$this->template = str_replace("{" . $k . "}", $v, $this->template);
			}
			
			// Second digit
			foreach($num2 as $nn2){
				$this->template2 .= $this->convert($nn2);
			}
			foreach($this->random_string(15) as $k2 => $v2){
				$k2 = $k2+1;
				$this->template2 = str_replace("{" . $k2 . "}", $v2, $this->template2);
			}
			
			// Operator
			$this->operatore .= $this->convert($l_operatore);
			foreach($this->random_string(15) as $ko => $vo){
				$ko = $ko+1;
				$this->operatore = str_replace("{" . $ko . "}", $vo, $this->operatore);
			}
			// Equal
			$this->uguale .= $this->convert("=");
			foreach($this->random_string(15) as $ku => $vu){
				$ku = $ku+1;
				$this->uguale = str_replace("{" . $ku . "}", $vu, $this->uguale);
			}
		
		// Creates 300x100 images
		$im = imagecreatetruecolor(108, 60);
		$rand_color = imagecolorallocate($im, rand(0,39),rand(0,100),rand(0,100));
		$rand_color1 = imagecolorallocate($im, rand(0,39),rand(0,100),rand(0,100));
		$rand_color2 = imagecolorallocate($im, rand(0,39),rand(0,100),rand(0,100));
		$rand_color3 = imagecolorallocate($im, rand(0,39),rand(0,100),rand(0,100));
		$red = imagecolorallocate($im, 0xFF, 0x00, 0x00);
		$black = imagecolorallocate($im, 0x00, 0x00, 0x00);
		$white = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
		
		$rand_color_bg = imagecolorallocate($im, rand(250,255),rand(250,255),rand(250,255));
		
		imagefilledrectangle($im, 0, 0, 299, 99, $rand_color_bg);
		
		// Path to the ttf font file
		$font_file = "miriam_fixed.ttf";
		
		// Draw the text
		imagefttext( $im, 6, rand(-10, 10), 10, 12, $rand_color, $font_file, $this->template, array("lineheight"=>2.0) );
		imagefttext( $im, 6, rand(-6, 6), 34, 12, $rand_color1, $font_file, $this->operatore, array("lineheight"=>2.0) );
		imagefttext( $im, 6, rand(-10, 10), 58, 12, $rand_color2, $font_file, $this->template2, array("lineheight"=>2.0) );
		imagefttext( $im, 6, rand(-10, 10), 82, 12, $rand_color3, $font_file, $this->uguale, array("lineheight"=>2.0) );
		
		// ONLY FOR DEBUG
		// Print the result in the image
		//imagefttext( $im, 30, 0, 104, 30, $red, $font_file, $risultato, array("lineheight"=>2.0) );
		
		// Draw lines
		$num_lines = rand(3, 12);
		for ($fox = 0; $fox < $num_lines; $fox++){
			$rand_color_line = imagecolorallocate($im, rand(200, 255),rand(200, 255),rand(200, 255));
			$x1_rand = rand(0, 108);
			$y1_rand = rand(0, 108);
			$x2_rand = rand(0, 108);
			$y2_rand = rand(0, 108);
			imageline($im, $x1_rand, $y1_rand, $x2_rand, $y2_rand, $rand_color_line);
		}
		// Wave it
		$this->wave_area($im, 0, 0, 50, 60, 10);
		
		// Output image to the browser
		header("Content-Type: image/png");
		imagepng($im);
		imagedestroy($im);
	}
}
$captcha = new Captcha();
$captcha->generate_captcha();
//captcha();
?>