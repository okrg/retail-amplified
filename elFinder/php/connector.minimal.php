<?php
error_reporting(0);
$project_id = $_REQUEST['project_id'];
$project_path = realpath(dirname(dirname(dirname(__FILE__))))."/files/$project_id/";
if (!file_exists($project_path)) {
    mkdir($project_path, 0777, true);
    mkdir($project_path.".trash/", 0777, true);
}

require './autoload.php';
elFinder::$netDrivers['ftp'] = 'FTP';

// // Online converter (online-convert.com) APIKey
// // https://apiv2.online-convert.com/docs/getting_started/api_key.html
// define('ELFINDER_ONLINE_CONVERT_APIKEY', '');
// ===============================================

// // Zip Archive editor
// // Installation by composer
// // `composer require nao-pon/elfinder-flysystem-ziparchive-netmount`
// define('ELFINDER_DISABLE_ZIPEDITOR', false); // set `true` to disable zip editor
// ===============================================

/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from '.' (dot)
 *
 * @param  string    $attr    attribute name (read|write|locked|hidden)
 * @param  string    $path    absolute file path
 * @param  string    $data    value of volume option `accessControlData`
 * @param  object    $volume  elFinder volume driver object
 * @param  bool|null $isDir   path is directory (true: directory, false: file, null: unknown)
 * @param  string    $relpath file path relative to volume root directory started with directory separator
 * @return bool|null
 **/
function access($attr, $path, $data, $volume, $isDir, $relpath) {
	$basename = basename($path);
	return $basename[0] === '.'                  // if file/folder begins with '.' (dot)
			 && strlen($relpath) !== 1           // but with out volume root
		? !($attr == 'read' || $attr == 'write') // set read+write to false, other (locked+hidden) set to true
		:  null;                                 // else elFinder decide it itself
}

// https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options
$opts = array(
	// 'debug' => true,
	'roots' => array(
		// Items volume
		array(
			'driver'        => 'LocalFileSystem',
			'path'          => realpath(dirname(dirname(dirname(__FILE__))))."/files/$project_id/",
			'URL'           => dirname($_SERVER['PHP_SELF'])."/../files/$project_id/",
			'trashHash'     => 't1_Lw',
			'winHashFix'    => DIRECTORY_SEPARATOR !== '/',
			'uploadDeny'    => array('all'),
			'uploadAllow'   => array('image', 'text/plain'),
			'uploadOrder'   => array('deny', 'allow'),
			'accessControl' => 'access'
		),
		// Trash volume
		array(
			'id'            => '1',
			'driver'        => 'Trash',
			'path'          => realpath(dirname(dirname(dirname(__FILE__))))."/files/$project_id/.trash/",
			'tmbURL'        => dirname($_SERVER['PHP_SELF'])."/../files/$project_id/.trash/.tmb/",
			'winHashFix'    => DIRECTORY_SEPARATOR !== '/',
			'uploadDeny'    => array('all'),
			'uploadAllow'   => array('image', 'text/plain'),// Same as above
			'uploadOrder'   => array('deny', 'allow'),      // Same as above
			'accessControl' => 'access',                    // Same as above
		)
	)
);

// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();