<?PHP
include('settings.php');

$edit='';

if(!empty($_REQUEST["edit"])) {$edit=$_REQUEST["edit"];} 
if(!empty($_REQUEST["gamename"])){$GameName=$_REQUEST["gamename"];} else {$GameName='';}
if(!empty($_REQUEST["company"])){$Company=$_REQUEST["company"];} else {$Company='';}
if( empty($_REQUEST["company"]) && empty($_REQUEST["gamename"])) {$GameName='A';}

CreateHeader();
SearchMenu();

function listdir_by_date($path){
    $dir = opendir($path);
    $list = array();
    while($file = readdir($dir)){
        if ($file != '.' and $file != '..'){
            // add the filename, to be sure not to
            // overwrite a array key
            $ctime = filectime($path . $file) . ',' . $file;
            $list[$ctime] = $file;
        }
    }
    closedir($dir);
    krsort($list);
    return $list;
}

$path='Archive/';

$a= listdir_by_date($path);


echo('<div id="container" style="width:925px;">');
echo('<table>');
echo('<tr style="font-weight:bold;background-color:#dddddd;">');
	echo('<td>Created</td>');
	echo('<td>File Name</td>');
	echo('<td>Size</td>');
echo('</tr>');
			
while (list($strTime, $logFile) = each($a)) {
		#echo date("M-d-Y @ H:i:s",(int)$strTime)." => $logFile";
		$size=floor((int)filesize($path.$logFile)/1024);
		echo ('<tr>');
		echo ('<td style="width:170px;">'.date("M-d-Y @ H:i:s",(int)$strTime).'</td>');
		echo ('<td style="width:250px;"><a href="Archive/'.$logFile.'">'.$logFile.'</a></td>');
		echo ('<td style="width:50;">'.$size.' Kb</td>');
		echo ('</tr>');
			}
	
echo('</table>');
echo('</div>');

?>