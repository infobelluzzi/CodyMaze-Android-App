<?php
error_reporting(E_ALL);
// Genera un boundary
$mail_boundary = "=_NextPart_" . md5(uniqid(time()));
$to = "duilio.peroni@gmail.com";
$subject = "Testing e-mail";
$sender = "makers@schoolmakerday.it";
$headers = "From: $sender\n";
$headers .= "MIME-Version: 1.0\n";
$headers .= "Content-Type: multipart/alternative;\n\tboundary=\"$mail_boundary\"\n";
$headers .= "X-Mailer: PHP " . phpversion();
// Corpi del messaggio nei due formati testo e HTML
$text_msg = "messaggio in formato testo";
$html_msg = "Messaggio di prova per codymaze";
// Costruisci il corpo del messaggio da inviare
$msg = "This is a multi-part message in MIME format.\n\n";
$msg .= "--$mail_boundary\n";
$msg .= "Content-Type: text/plain; charset=\"iso-8859-1\"\n";
$msg .= "Content-Transfer-Encoding: 8bit\n\n";
$msg .= "Messaggio di prova per codymaze";  // aggiungi il messaggio in formato text
$msg .= "\n--$mail_boundary\n";
$msg .= "Content-Type: text/html; charset=\"iso-8859-1\"\n";
$msg .= "Content-Transfer-Encoding: 8bit\n\n";
$msg .= "Messaggio di prova per codymaze";  // aggiungi il messaggio in formato HTML
// Boundary di terminazione multipart/alternative
$msg .= "\n--$mail_boundary--\n";
// Imposta il Return-Path (funziona solo su hosting Windows)
ini_set("sendmail_from", $sender);
// Invia il messaggio, il quinto parametro "-f$sender" imposta il Return-Path su hosting Linux
if (mail($to, $subject, $msg, $headers, "-f$sender")) { 
    echo "Mail inviata correttamente!";
//    highlight_file($_SERVER["SCRIPT_FILENAME"]);
//    unlink($_SERVER["SCRIPT_FILENAME"]);
} else { 
    echo "<br><br>Recapito e-Mail fallito!";
}
?>