<?php
// Recupero il valore dei campi del form
$destinatario = 'info@mail-del-sito.com';
$mittente = $_POST['mittente'];
$oggetto = $_POST['oggetto'];
$messaggio = $_POST['messaggio'];

// Valorizzo le variabili relative all'allegato
$allegato = $_FILES['allegato']['tmp_name'];
$allegato_type = $_FILES['allegato']['type'];
$allegato_name = $_FILES['allegato']['name'];

// Creo altre due variabili ad uno interno
$headers = "From: " . $mittente;
$msg = "";

// Verifico se il file è stato caricato correttamente via HTTP
// In caso affermativo proseguo nel lavoro...
if (is_uploaded_file($allegato))
{
  // Apro e leggo il file allegato
  $file = fopen($allegato,'rb');
  $data = fread($file, filesize($allegato));
  fclose($file);

  // Adatto il file al formato MIME base64 usando base64_encode
  $data = chunk_split(base64_encode($data));

  // Genero il "separatore"
  // Serve per dividere, appunto, le varie parti del messaggio.
  // Nel nostro caso separerà la parte testuale dall'allegato
  $semi_rand = md5(time());
  $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
  
  // Aggiungo le intestazioni necessarie per l'allegato
  $headers .= "\nMIME-Version: 1.0\n";
  $headers .= "Content-Type: multipart/mixed;\n";
  $headers .= " boundary=\"{$mime_boundary}\"";

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
  $msg .= "Content-Disposition: attachment; filename=\"{$allegato_name}\"\n";
  $msg .= "Content-Transfer-Encoding: base64\n\n";
  $msg .= $data . "\n\n";

  // chiudo con il separatore
  $msg .= "--{$mime_boundary}--\n";
}
// se non è stato caricato alcun file
// preparo un semplice messaggio testuale
else
{
  $msg = $messaggio;
}

// Invio la mail
if (mail($destinatario, $oggetto, $msg, $headers))
{
  echo "<p>Mail inviata con successo!</p>";
}else{
  echo "<p>Errore!</p>";
}
?>