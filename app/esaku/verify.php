<?php

    $login_link="http://".$_SERVER['SERVER_NAME']."/web/app/sakuaja/login";
    
    function getKoneksi(){
        $root_lib=$_SERVER["DOCUMENT_ROOT"];
        include_once($root_lib."web/lib/koneksi.php");
      }

      getKoneksi();

    // Ambil kode verifikasi dari url
    $verify = $_GET['verify_email'];

    // Cari email yang belum terverifikasi
    $sql_email = "SELECT * FROM sai_user WHERE verification_code='".$verify."'";
    $res = execute($sql_email);
    $row=$res->FetchNextObject($toupper=false);
    $cek_row = $res->RecordCount();

    if ($cek_row>0) {
        getKoneksi();
        $sql = "UPDATE sai_user SET flag_aktif=1 WHERE verification_code='".$verify."'";
        $rs = execute($sql);
        if($rs){
            kirim_email();
            echo "<script type='text/javascript'>alert('Verifikasi email telah berhasil, silahkan login');window.location.href='$login_link'</script>";
        }else{
            echo "<script type='text/javascript'>alert('Gagal verifikasi');</script>";
        }
    }
    // if ($cek_duplicate>0) {
    //     echo "<script type='text/javascript'>alert('Email anda sudah diverifikasi, silahkan login');window.location.href='$login_link'</script>";
    // }

    function kirim_email(){
  
        getKoneksi();
        global $login_link;
        global $row;

        require_once 'mail/swift_required.php';
        require_once "mail/pop3.php";
        require_once "mail/mime_parser.php";
        require_once "mail/rfc822_addresses.php";
      
        // LIST TASK
        // DEFINE AUTOGENERATE UNIQUE CODE PBKDF2
        // DEFINE EMAIL RECIPIENT $_POST
        // DEFINE RECIPIENT NAME
      
        // Create the Transport
        $transport = Swift_SmtpTransport::newInstance('smtp.googlemail.com', 465, 'tls')
          ->setUsername('devptsai@gmail.com')
          ->setPassword('Saisai2019')
          ;
        // Error checking
        if ($transport) {
            # code...
        }else{
            echo "Error transport";
            exit;
        }
      
        // Create the Mailer using your created Transport
        $mailer = Swift_Mailer::newInstance($transport);
        if ($mailer) {
            # code...
        }else{
            echo "Error Mailer";
            exit;
        }
        $body_text="Email anda telah berhasil di verifikasi! Klik untuk masuk ke laman login ";
        $body_text.=$login_link;
        $to=$row->email;
      
        // Create a message
        $message = Swift_Message::newInstance('Email telah terverifikasi!')
          ->setFrom(array('devptsai@gmail.com' => 'Pt. Samudera Aplikasi Indonesia'))
          ->setTo(array($to))
          ->setBody($body_text)
          ;
        if ($message) {
            
        }else{
            return "Error message create";
            exit;
        }

        // Send the message
        $result = $mailer->send($message);
        if ($result) {
            # code...
        }else{
            echo "Error send";
            exit;
        }
        
          }

?>