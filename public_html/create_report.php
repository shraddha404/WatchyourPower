<?php
$details['to_date'] = $argv[1];
$file = 'people.txt';
$current = "Name,Location\n";
$current .= "John Smith\n";
// Write the contents back to the file
file_put_contents($file, $current);
$title = file_get_contents($file, $current);
$title .= "Shraddha Kulkarni";
file_put_contents($file, $title);
?>
