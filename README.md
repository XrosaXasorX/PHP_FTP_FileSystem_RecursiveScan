# PHP_FTP_FileSystem_RecursiveScan
PHP File System Recursive Scan Procedure (through FTP Protocol)

Example usage:

// Some inits...

// Recursive directory files retrieving through FTP protocol.

$l_aDirData = JSX_PHP_FTP_Directory_List_Get( $g_aConnData, $g_aPathData );

// Print data.

JSX_PHP_FTP_Files_List_Recursive_Print( $l_aDirData );

JESAX.NET [ http://www.jesax.net/ ]

JAVA/script Environmental Subroutines for Accessible XML

(C) CopyRight JESAX.net / Synkhro.com [1984 - 2016]

Last update: 2016/01/21 - 16:00 
