<html>
<body>
<h1>ProposalXML Validation</h1>


<?php
if ((($_FILES["file"]["type"] == "text/xml")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/pjpeg"))
&& ($_FILES["file"]["size"] < 2000000))
  {
  if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  else
    {
    //echo "Upload: " . $_FILES["file"]["name"] . "<br />";
    //echo "Type: " . $_FILES["file"]["type"] . "<br />";
    //echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
    //echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

      move_uploaded_file($_FILES["file"]["tmp_name"],"/var/www/html/validation/uploads/temp.xml");
      doXML();
      //echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
    
    	
    
    }
  }
else
  {
  echo "Invalid file";
  }
?>

</body>
</html> 

<?php
function libxml_display_error($error) 
{ 
$return = "<br/>\n"; 
switch ($error->level) { 
case LIBXML_ERR_WARNING: 
$return .= "<b>Warning $error->code</b>: "; 
break; 
case LIBXML_ERR_ERROR: 
$return .= "<b>Error $error->code</b>: "; 
break; 
case LIBXML_ERR_FATAL: 
$return .= "<b>Fatal Error $error->code</b>: "; 
break; 
} 
$return .= trim($error->message); 
if ($error->file) { 
$return .= " in <b>$error->file</b>"; 
} 
$return .= " on line <b>$error->line</b>\n"; 

return $return; 
} 

function libxml_display_errors() { 
$errors = libxml_get_errors(); 
foreach ($errors as $error) { 
print libxml_display_error($error); 
} 
libxml_clear_errors(); 
} 


// Enable user error handling 


function doXML(){
libxml_use_internal_errors(true); 
$xml = new DOMDocument(); 
$xml->load('uploads/temp.xml'); 
if (!$xml->schemaValidate('spotTVCableProposal-0.3.0.5A.xsd')) { 
print '<b>Errors Found!</b>'; 
libxml_display_errors(); 
} 
else { 
echo "<p>Validated - No Errors Found<p/>"; 
} 
}

?>