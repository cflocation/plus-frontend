<?php
	ini_set("display_startup_errors",1);
	ini_set("display_errors",1);
	error_reporting(E_ALL);

$hostname = "{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX";
$username = "ratecards@showseeker.com";
$password = "!r@W2e8r%S";

$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
$emails = imap_search($inbox,'SUBJECT "Charter Ratecard Import - d61d832"');
$datestamp = date('m-d-Y_a');

if($emails) {
 
    $count = 1;
 
    /* put the newest emails on top */
    rsort($emails);
 
    /* for every email... */
    foreach($emails as $email_number) 
    {
 
        /* get information specific to this email */
        $overview = imap_fetch_overview($inbox,$email_number,0);

		$header = imap_header($inbox,$email_number); // get first mails header
		$sender = $header->sender[0]->mailbox ."@". $header->sender[0]->host;

        /* get mail message */
        $message = imap_fetchbody($inbox,$email_number,2);
 
        /* get mail structure */
        $structure = imap_fetchstructure($inbox, $email_number);
 
        $attachments = array();
 
        /* if any attachments found... */
        if(isset($structure->parts) && count($structure->parts)) 
        {
            for($i = 0; $i < count($structure->parts); $i++) 
            {
                $attachments[$i] = array(
                    'is_attachment' => false,
                    'filename' => '',
                    'name' => '',
                    'attachment' => ''
                );
 
                if($structure->parts[$i]->ifdparameters) 
                {
                    foreach($structure->parts[$i]->dparameters as $object) 
                    {
                        if(strtolower($object->attribute) == 'filename') 
                        {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object->value;
                        }
                    }
                }
 
                if($structure->parts[$i]->ifparameters) 
                {
                    foreach($structure->parts[$i]->parameters as $object) 
                    {
                        if(strtolower($object->attribute) == 'name') 
                        {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['name'] = $object->value;
                        }
                    }
                }
 
                if($attachments[$i]['is_attachment']) 
                {
                    $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);
 
                    /* 4 = QUOTED-PRINTABLE encoding */
                    if($structure->parts[$i]->encoding == 3) 
                    { 
                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                    }
                    /* 3 = BASE64 encoding */
                    elseif($structure->parts[$i]->encoding == 4) 
                    { 
                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                    }
                }
            }
        }
 
        /* iterate through each attachment and save it */
        foreach($attachments as $attachment)
        {
            if($attachment['is_attachment'] == 1)
            {
                $filename = $attachment['name'];
                if(empty($filename)) $filename = $attachment['filename'];
 
                if(empty($filename)) $filename = time() . ".dat";
 
                /* prefix the email number to the filename in case two emails
                 * have the attachment with the same file name.
                 */
				$final_filename = $datestamp . '-' . $filename    ;
				$fp = fopen('excels/' . $final_filename, 'w+');
                fwrite($fp, $attachment['attachment']);
                fclose($fp);

				imap_delete($inbox, $email_number);
            }
 
        }
 
    }
 

//echo "Filename Saved as: " . $final_filename ."<br>" ;
//echo "<a href='process_excel.php?file=". $final_filename."&sender=". $sender."'>Click Here</a> to begin";

//imap_expunge($inbox);
imap_close($inbox);



$request = curl_init('https://scrapers.prod.showseeker.com/xlsfileconverter.php');

curl_setopt($request, CURLOPT_POST, true);
curl_setopt(
    $request,
    CURLOPT_POSTFIELDS,
    array(
      'xlsfile' => '@' . realpath('excels/'.$final_filename)
    ));

// output the response
curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
$thefilename = curl_exec($request);


// close the session
curl_close($request);

$url = "https://scrapers.prod.showseeker.com/xlsfile/converted/" ;
$new_file = str_replace($url, '', $thefilename);

$path = "excels/" . $new_file . "" ;

file_put_contents($path, fopen(str_replace(' ', '%20',$thefilename), 'r'));



$pathout = 'process_excel.php?file='. $new_file.'&sender='. $sender .'';
header("location:$pathout");

} 
echo "No Emails Found to Process";

?>
<?php

function getBody($uid, $imap) {
    $body = get_part($imap, $uid, "TEXT/HTML");
    // if HTML body is empty, try getting text body
    if ($body == "") {
        $body = get_part($imap, $uid, "TEXT/PLAIN");
    }
    return $body;
}

function get_part($imap, $uid, $mimetype, $structure = false, $partNumber = false) 
{
    if (!$structure) {
           $structure = imap_fetchstructure($imap, $uid);
    }
    if ($structure) {
        if ($mimetype == get_mime_type($structure)) {
            if (!$partNumber) {
                $partNumber = 1;
            }
            $text = imap_fetchbody($imap, $uid, $partNumber);
            switch ($structure->encoding) {
                case 3: return imap_base64($text);
                case 4: return imap_qprint($text);
                default: return $text;
           }
       }
 
        // multipart 
        if ($structure->type == 1) {
            foreach ($structure->parts as $index => $subStruct) {
                $prefix = "";
                if ($partNumber) {
                    $prefix = $partNumber . ".";
                }
                $data = get_part($imap, $uid, $mimetype, $subStruct, $prefix . ($index + 1));
                if ($data) {
                    return $data;
                }
            }
        }
    }
    return false;
}

function get_mime_type($structure)
{
    $primaryMimetype = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");
 
    if ($structure->subtype)
    {
       return $primaryMimetype[(int)$structure->type] . "/" . $structure->subtype;
    }
    return "TEXT/PLAIN";
}
?>