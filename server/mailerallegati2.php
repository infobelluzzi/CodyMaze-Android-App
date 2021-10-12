<?php
error_reporting(E_ALL);
// Genera un boundary
$semi_rand = md5(time());
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
$to = "duilio.peroni@gmail.com";
$subject = "Testing e-mail";
$sender = "makers@schoolmakerday.it";
$headers = "From: $sender\n";
$messaggio="Messaggio di prova per codymaze con certificato allegato";
$msg = "";
//$headers .= "MIME-Version: 1.0\n";
//$headers .= "Content-Type: multipart/alternative;\n\tboundary=\"$mail_boundary\"\n";
//$headers .= "X-Mailer: PHP " . phpversion();
$headers .= "\nMIME-Version: 1.0\n";
$headers .= "Content-Type: multipart/mixed;\n";
$headers .= " boundary=\"{$mime_boundary}\"";
// Apro e leggo il file allegato
$allegato="testfile.pdf";
$file = fopen($allegato,'rb');
$data = fread($file, filesize($allegato));
fclose($file);
// Adatto il file al formato MIME base64 usando base64_encode
$data = chunk_split(base64_encode($data));
// Definisco il tipo di messaggio (MIME/multi-part)
$msg .= "This is a multi-part message in MIME format.\n\n";
// Metto il separatore
$msg .= "--{$mime_boundary}\n";
// Questa è la parte "testuale" del messaggio
$msg .= "Content-Type: text/plain; charset=\"iso-8859-1\"\n";
$msg .= "Content-Transfer-Encoding: 7bit\n\n";
$msg .= $messaggio . "\n\n";
// Metto il separatore
$msg .= "--{$mime_boundary}\n";
// Aggiungo l'allegato al messaggio
$msg .= "Content-Disposition: attachment; filename=\"{$allegato}\"\n";
$msg .= "Content-Transfer-Encoding: base64\n\n";
$msg .= $data . "\n\n";
// chiudo con il separatore
$msg .= "--{$mime_boundary}--\n";
// Invia il messaggio, il quinto parametro "-f$sender" imposta il Return-Path su hosting Linux
if (mail($to, $subject, $msg, $headers, "-f$sender")) { 
    echo "Mail inviata correttamente!";
//    highlight_file($_SERVER["SCRIPT_FILENAME"]);
//    unlink($_SERVER["SCRIPT_FILENAME"]);
} else { 
    echo "<br><br>Recapito e-Mail fallito!";
}
?>