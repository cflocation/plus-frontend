<?phpini_set('max_execution_time','0');ini_set('memory_limit','1024M');set_time_limit(480);//phpEXCEL Classesrequire_once 'Classes/PHPExcel.php';$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;$cacheSettings = array( ' memoryCacheSize ' => '64MB');PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);require_once 'Classes/PHPExcel/Writer/Excel2007.php';//GETTING PROPOSAL DATArequire_once 'proposal.data.php';require_once 's3/upload.php';// json decode proposal data$proposalLines = json_decode($json_data,true);//corporation$corporation_id = $proposalLines['corporation'][0]['id'];//USER ID$userid = $proposalLines['user'][0]['userid'];// passed proposal data to array variable$arrProposal 	= $proposalLines['proposal'];//REDIRECTING TO RATE CARD VERSIONtry {	include_once('xlsspec/index.php');} catch (Exception $e) {    echo 'Caught exception: ',  $e->getMessage(), "\n";}	exit;//FORMATTING FILE NAME		function cleanStr($string) {   	$string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.	   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.	}?>