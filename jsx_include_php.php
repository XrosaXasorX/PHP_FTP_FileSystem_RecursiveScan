<?php
///////////////////////////////////////////////////////////////////
//
// BoF :: "jsx_include_php.php"
//
// 2016/01/21 - 16:00
///////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////

//
// Recursive directory files retrieving through FTP protocol.
//

function JSX_PHP_FTP_Directory_List_Get( $p_aConnData, $p_aPathData )
    {

    // Connection settings.
    $hostname  = $p_aConnData[ 'hostname' ];
    $username  = $p_aConnData[ 'username' ];
    $password  = $p_aConnData[ 'password' ];
    
    // Directory settings.
    $startdir  = $p_aPathData[ 'startdir' ]; // absolute path
    $suffix    = $p_aPathData[ 'suffix'   ]; // suffixes to list
    $g_levello = $p_aPathData[ 'levello'  ]; // Livello di "nested directory".
    
    // Data array.
    $files = array();
    
    // Ftp connection.
    $conn_id = ftp_connect($hostname);
    $login   = ftp_login($conn_id, $username, $password);          
    if (!$conn_id) 
        {
        echo 'Wrong server!';
        exit;
        } 
    else if (!$login) 
        {
        echo 'Wrong username/password!';
        exit;
        }
    else 
        {
        // Get data through FTP.
        $files = JSX_PHP_FTP_raw_list($conn_id, $p_aConnData, $p_aPathData, $files );
        }

    // Close FTP connection.
    ftp_close($conn_id);

    // Return value.
    return $files;
        
}//JSX_PHP_FTP_Directory_List_Get
///////////////////////////////////////////////////////////////////

//
// Recursive scanning function.
//

function JSX_PHP_FTP_raw_list($p_conn_id, $p_aConnData, $p_aPathData, $p_files ) 
    {
    
    // Limiter counters.
    global $g_iMaxCounter;
    global $g_iMaxLimits;    
    $g_iMaxCounter++;
    if( $g_iMaxCounter > $g_iMaxLimits )
        { return $p_files; }

    // Init.
    $folder    = $p_aPathData[ 'startdir' ];
    $suffix    = $p_aPathData[ 'suffix'   ];
    $g_levello = $p_aPathData[ 'levello'  ];
    $suffixes  = explode(",", $suffix);
    $list      = ftp_rawlist($p_conn_id, $folder);
    $anzlist   = count($list);

    // Loop scanning.
    $i = 0;
    while ($i < $anzlist)
        {
        $split = preg_split("/[\s]+/", $list[$i], 9, PREG_SPLIT_NO_EMPTY);
        $itemname = $split[8];
        $endung   = strtolower(substr(strrchr($itemname ,"."),1));
        $path     = "$folder/$itemname";
        if (substr($list[$i],0,1) === "d" AND substr($itemname,0,1) != ".") 
            {
            $p_aPathData['startdir'] = $path;
            $l_item = Array();
            $l_item[ 'type' ] = "dir";
            $l_item[ 'name' ] = $path;
            $l_item[ 'levl' ] = $g_levello;
            array_push($p_files, $l_item);
            
            $g_levello++;
            $p_aPathData[ 'levello' ] = $g_levello;
            $p_files = JSX_PHP_FTP_raw_list($p_conn_id, $p_aConnData, $p_aPathData, $p_files);
            $g_levello--;
            $p_aPathData[ 'levello' ] = $g_levello;
            } 
        else 
            {
            $l_item = Array();
            $l_item[ 'type' ] = "file";
            $l_item[ 'name' ] = $path;
            $l_item[ 'levl' ] = $g_levello;
            array_push($p_files, $l_item);
            }
            $i++;
        }

        // Return value.
        return $p_files;
        
    }//JSX_PHP_FTP_raw_list
///////////////////////////////////////////////////////////////////

//
// Data printing function.
//
function JSX_PHP_FTP_Files_List_Recursive_Print( $p_aData )
    {
    global $g_icon_fold;
    global $g_icon_file;
    global $g_cnt;
    global $g_dir_cnt;
    
    // Init.
    $l_aFolds = array();
    $l_aFiles = array();
    $l_aTree = $p_aData;

    // Outer container.
    echo "<DIV style='border:0px solid black; ' >\n";
    $l_levcurr = 0;
    foreach ($l_aTree as $key => $val)
        {//loop_strt
        
        // Counter for <DIV>s Identifier.
        $g_cnt++;
        
        // <DIV>s Identifiers.
        $l_id = "id_n" .$g_cnt;
        $l_dir_id = "id_dir_n" .$g_dir_cnt;
        $l_dir_nxt = "id_dir_n" .(1+$g_dir_cnt);

        // Handling of nested <DIV>s.
        if( $val['levl']>$l_levcurr )
          {
          $l_levcurr = $val['levl'];
          echo "<DIV id='" .$l_dir_id. "' style=' border:0px solid red; display:none; margin-left:10px; width:100%; ' >";
          }
        if( $val['levl']<$l_levcurr )
          {
          for( $cl=$l_levcurr; $cl>$val['levl']; $cl-- )
              {
              echo "</DIV>\n"; 
              }
          $l_levcurr = $val['levl'];
          }
      
        // Item type.
        if( $val['type']=="dir" )
          {
          echo "\n   <A href='#000' onclick='JSX_JS_Toggle_dir(\"" .$l_dir_nxt. "\");' >"; 
            echo "<IMG src='" .$g_icon_fold. "' />";
          echo "</A>\n";
          $g_dir_cnt++;
          echo $val['name'] ;
          echo "<BR />\n";
          }
        else
          {
          echo "\n<DIV style='border:0px solid green;  margin-left:10px; ' >";
          $l_sUrlFtp  = "";
          $l_sUrlFtp .= "ftp://my_user:my_pass@ftp.nic.funet.fi";
          $l_sUrlFtp .= "/";
          $l_sUrlFtp .= $val['name'];
          $l_sUrlFtp .= "";
          echo "<A href='" .$l_sUrlFtp. "' target='_blank' >";
            echo "<IMG src='" .$g_icon_file. "' />";
          echo "</A>";
          echo $val['name'];
          echo "</DIV>\n";
          }
       
        }//loop_stop
  echo "</DIV>\n";
  
  // Return value.
  $rv = $l_aFiles;
  return( $rv );
  
  }//JSX_PHP_FTP_Files_List_Recursive_Print
///////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////
// BoF :: "jsx_include_php.php"
///////////////////////////////////////////////////////////////////
?>
