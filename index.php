<?php
///////////////////////////////////////////////////////////////////
//
// BoF :: "index.php"
//
// 2016/01/21 - 16:00
///////////////////////////////////////////////////////////////////
?>

<HTML>
    <HEAD >
        <SCRIPT language='javascript' src='jquery-1.12.0.js' ></SCRIPT>
        <SCRIPT language='javascript' >function JSX_JS_Toggle_dir( p_id ){ $("#"+p_id).toggle(1000); }</SCRIPT>
    </HEAD>
<BODY >
<HTML >

<?php

// Error handling.        
ini_set("display_errors", "1");
error_reporting(E_ALL);   
        
// Include files.        
require_once( "jsx_include_php.php" );        
      
// Data.          
$g_aConnData = Array();
$g_aConnData[ 'hostname' ] = "ftp.nic.funet.fi";
$g_aConnData[ 'username' ] = "my_user";
$g_aConnData[ 'password' ] = "my_pass";

// Directory data.     
$g_aPathData = Array();     
$g_aPathData[ 'startdir' ] = ".";
$g_aPathData[ 'suffix'   ] = "docx,gif,png,jpeg,pdf,php";
$g_aPathData[ 'levello'  ] = 0; // Level of "nested directory".  

// Headings.
echo "<H2 >ftp.nic.funet.fi: (ftp server)</H2>";

// Globals.
$g_dir_img = "./media/";
$g_icon_fold = $g_dir_img . "icon_fold_70x70.png";
$g_icon_file = $g_dir_img . "icon_file_70x70.png";

// Init counters.
$g_cnt     = 0;  // Counter for <tags> ID.
$g_dir_cnt = 0;  // Counter for FOLDER <tags> ID.

// Limiter counters.
$g_iMaxCounter = 0;
$g_iMaxLimits = 20;

// Recursive directory scanning through FTP protocol.
$l_aDirData = JSX_PHP_FTP_Directory_List_Get( $g_aConnData, $g_aPathData );

// Print data.
JSX_PHP_FTP_Files_List_Recursive_Print( $l_aDirData );

?>

</HTML>
</BODY>

<?php
///////////////////////////////////////////////////////////////////
// EoF :: "index.php"
///////////////////////////////////////////////////////////////////
?>
