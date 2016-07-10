<?php

//ARGS handling
if (count($argv)!=2){
  print "Usage: phlabel lable_template_file\n";
  exit(1);
}


//Reading the template to memory
$templateFilename = $argv[1];
$templateFile = fopen($templateFilename, "r");
$templateFileContent = array();

if ($templateFile === false){
  fwrite(STDERR, "Unable to read from $templateFilename\n");
  exit(1);
}

$line = fgets($templateFile);
while ($line !== false){
  $templateFileContent[] = $line;
  $line = fgets($templateFile);
}
fclose($templateFile);



function replaceVars($text, $lineNumber, $args){
  $outputText = "";

  for ($i=0; $i<strlen($text); $i++){
    $char = $text[$i];
    if ($char=='$'){
      $number = 0;
      $j = $i+1;
      while (ord($text[$j])>=ord("0") && ord($text[$j])<=ord("9")){
        $number = $number*10+ord($text[$j])-ord('0');
        $j++;
      }
      if ($i == $j+1){
        $outputText .= '$';
      }
      else {
        $i=$j-1;
        if ($number == 0){
          $outputText .= $lineNumber;
        }
        else {
          $outputText .= $args[$number-1];
        }
      }
    }
    else {
      $outputText .= $char;
    }
  }
  return $outputText;
}

function makeLabel($lineNumber, $rows){
  global $templateFileContent;

  $lineNumber = (int)$lineNumber;
  $image = false;
  $font = false;
  $fontSize=12;
  $rotation=0;
  $backgroundColour = false;
  $colour = false;

  foreach ($templateFileContent as $command){
    $command = replaceVars($command, $lineNumber, $rows);
    
    if (preg_match("/^\s*setSize\s*\(\s*(\d+)\s*,\s*(\d+)\s*\)/", $command, $matches)){
      $width = (int)$matches[1];
      $height = (int)$matches[2];
      $image = imagecreatetruecolor($width, $height);
      $backgroundColour = imagecolorallocate($image, 255,255,255);
      $colour = imagecolorallocate($image, 0,0,0);
      imagefilledrectangle($image, 0,0, $width-1,$height-1, $backgroundColour);
    }
    else if (preg_match("/^\s*setFont\s*\((.*),\s*([0-9.]+)\s*\)/", $command, $matches)){
      $font = $matches[1];
      $fontSize = (float)$matches[2];
    }
    else if (preg_match("/\s*print\s*\(\s*(\d+)\s*,\s*(\d+),(.*)\)/", $command, $matches)){
      $x = (int)$matches[1];
      $y = (int)$matches[2];
      $text = $matches[3];
      if ($image!==false && $font!==false){
        imagettftext ($image, $fontSize, $rotation, $x, $y, -$colour, $font, $text);
      }
    }
    else {
      print "Unhandled: $command\n";
    }
  }

  if ($image !== false){
    imagepng($image, "$lineNumber.png");
  }
}



//Reading the "CSV" data for std in
$lineNumber = 0;
$line = fgets(STDIN);
while($line !== false){
  $line = rtrim($line, "\r\n");
  if (strlen($line)>0){
    $rows = explode("\t", $line);
    makeLabel($lineNumber, $rows);
  }
  $lineNumber++;
  $line = fgets(STDIN);
}



?>