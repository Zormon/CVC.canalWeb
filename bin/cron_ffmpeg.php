<?php
error_reporting(1);ini_set("display_errors",1);

$servername = "localhost";
$username = "root";
$password = "cvc";
$dbname = "canalClon";
$path = "/var/www/panel.comunicacionvisualcanarias.com/";

$included_files = get_included_files();

chdir("..");

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $ffmpeg = getcwd()."\\bin\\ffmpeg.exe";
    $storage = getcwd()."\\public\\storage\\";
    $storage_thumbs = getcwd()."\\public\\storage\\thumbs\\";
} else {
    $ffmpeg = $path."/bin/ffmpeg";
    $storage = $path."/public/storage/";
    $storage_thumbs = $path."/public/storage/thumbs/";
}

chmod($ffmpeg,0755);

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$rows = $conn->query('SELECT * FROM encode_queues')->fetchall();

foreach($rows as $row) {
    $file = pathinfo($row['filename']);

    if( !file_exists($storage.$file['filename'].'_enc.mp4') ) {
        $duration_gross = shell_exec($ffmpeg.' -i '.$storage.$row['filename'].' 2>&1 | grep Duration | cut -d \' \' -f 4 | sed s/,//');
        $duration_secs = seconds_from_time($duration_gross);

        $sql = "UPDATE encode_queues SET encoding=1 WHERE id=".$row['id'];
        $result = $conn->query($sql);

        $salida = shell_exec($ffmpeg.' -i '.$storage.$row['filename'].' -vcodec libx264 -pix_fmt yuv420p -profile:v baseline -level 3 '.$storage.$file['filename'].'_enc.mp4 2>&1');
        $salida_thumb = shell_exec($ffmpeg.' -i '.$storage.$row['filename'].' -ss 00:00:2.435 -vframes 1 -filter:v scale="250:-1" '.$storage_thumbs.$file['filename'].'_thumb.png 2>&1');
        unlink($storage.$row['filename']);
        
        $sql = "INSERT INTO uploads (id, filename, resized_name, original_name, userId, playlistId, notes,  position, broadcast_from, broadcast_to, active, created_at, updated_at, duration) VALUES (NULL, '".$file['filename']."_enc.mp4', '".$file['filename']."_thumb.png', '".$row['original_name']."', '".$row['userId']."', '".$row['playlistId']."', '".$row['notes']."','".$row['position']."', NOW(), NOW() + INTERVAL 1 YEAR, 1, NOW(), NOW(), ".$duration_secs.")";
        $result = $conn->query($sql);


        $sql = "DELETE FROM encode_queues WHERE id=".$row['id'];
        $result = $conn->query($sql);
    }
}

$conn = null;

function seconds_from_time($time) {
	list($h, $m, $s) = explode(':', $time);
	return round(($h * 3600) + ($m * 60) + $s);
}

?>
