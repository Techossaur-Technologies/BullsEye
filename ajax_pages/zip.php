<?php
include_once('../plugins/pclzip/pclzip.lib.php');
include('../includes/config.php');

$path = date('Y').'/'.date('m').'/';
if (!file_exists('../uploads/'.$path)) {
    mkdir(('../uploads/'.$path), 0777, true);
    $target = '../uploads/'.$path;
} else {
    $target = '../uploads/'.$path;
}

$filelist = array();
$zip_name = 'archive2.zip';
$file_qry = mysqli_query($con, "SELECT * FROM `files`");

while ($file_list = mysqli_fetch_array($file_qry, MYSQLI_BOTH)) {
	echo "string2";
	copy('../server/php/files/'.$file_list['name'], $target.$file_list['name']);
	array_push($filelist, $target.$file_list['name']);
	$final_file = mysqli_query($con, "INSERT INTO `final_files`(`name`, `size`, `type`, `url`, `title`, `description`, `zip_name`) VALUES ('".$file_list['name']."','".$file_list['size']."','".$file_list['type']."','".$file_list['url']."','".$file_list['title']."','".$file_list['description']."','".$zip_name."')");
	$file = mysqli_query($con, "DELETE FROM `files` WHERE `id` = '".$file_list['id']."'");
}

print_r($filelist);

foreach ($filelist as $file) {
	if (file_exists($file)) {
		echo 'file found';
	}
}

$archive = new PclZip('../'.$zip_name);
//array_push($filelist, 'index.php');
$v_list = $archive->create(''.implode(",",$filelist).'', PCLZIP_OPT_REMOVE_ALL_PATH, PCLZIP_OPT_ADD_TEMP_FILE_ON);
// $v_list = $archive->add($filelist, PCLZIP_OPT_REMOVE_PATH, $target);
if ($v_list == 0) {
	echo "string3";
	die("Error : ".$archive->errorInfo(true));

}
echo "string4";


?>