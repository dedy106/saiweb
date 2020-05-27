<?php

if (function_exists($_GET['fx'])) {
  $_GET['fx']();
}

$ver_code;


  function getKoneksi(){
    $root_lib=$_SERVER["DOCUMENT_ROOT"];
    include_once($root_lib."web/lib/koneksi.php");
  }

  function tambahUser(){

    getKoneksi();
    global $ver_code;
    $ver_code=uniqid();

  //   // Nama depan
  //   // Nama Belakang
  //   // Email
  //   // Notelp
  //   // Kata Sandi

    if ($_POST['nama1']=='') {
        $result["status"]="nama_depan";
        echo json_encode($result);
        exit;
    }
    if($_POST['nama2']==''){
        $result["status"]="nama_belakang";
        echo json_encode($result);
        exit;   
    }
    if($_POST['email']==''){
        $result["status"]="email";
        echo json_encode($result);
        exit;   
    }
    
    if($_POST['nope']==''){
      $result["status"]="notelp";
      echo json_encode($result);
      exit;   
  }

    if($_POST['password']==''){
    $result["status"]="password";
    echo json_encode($result);
    exit;   
  }

    // Lakukan pengecekan apabila e-mail pernah didaftarkan atau belum
    $sql_email = "SELECT * FROM sai_user WHERE email='".$_POST['email']."'";
    $res = execute($sql_email);
    $cek_email = $res->RecordCount();
    if($cek_email>0){
      $result["status"]="email-duplicate";
      echo json_encode($result);
      exit;
    }
    else{
      
      // Insert data user baru ke tabel
      $sql = "insert into sai_user (kode_lokasi, email, pass, nama_depan, nama_belakang, no_telp,flag_aktif,verification_code) values ('99', '".$_POST['email']."', '".$_POST['password']."', '".$_POST['nama1']."', '".$_POST['nama2']."', '".$_POST['nope']."', '0', '".$ver_code."')";
      // $query = [$sql];
      $rs = execute($sql);

      $tmp= array();
      $kode = array();
      $sts=false;
      if ($rs)
      {	
          $tmp="sukses";
          $sts=true;
      }else{
          $tmp="gagal";
          $sts=false;
      }	

      $result["message"] =$tmp;
      $result["status"] = $sts;
      $result["sql"] = $sql;
      $result["id"] = $id;
      $result["sql2"] = $sql2;
      
      echo json_encode($result);
      kirim_email();
      exit;
    }

    
    // echo "Data Telah dimasukkan";
    
    
  }

  function kirim_email(){

  
  require_once 'mail/swift_required.php';
  require_once "mail/pop3.php";
  require_once "mail/mime_parser.php";
  require_once "mail/rfc822_addresses.php";

  // LIST TASK
  // DEFINE AUTOGENERATE UNIQUE CODE PBKDF2
  // DEFINE EMAIL RECIPIENT $_POST
  // DEFINE RECIPIENT NAME

  // $_POST['formdata'];


  // Create the Transport
  $transport = Swift_SmtpTransport::newInstance('smtp.googlemail.com', 465, 'tls')
    ->setUsername('devptsai@gmail.com')
    ->setPassword('Saisai2019')
    ;

  /*
  You could alternatively use a different transport such as Sendmail or Mail:

  // Sendmail
  $transport = Swift_SendmailTransport::newInstance('/usr/sbin/sendmail -bs');

  // Mail
  $transport = Swift_MailTransport::newInstance();
  */

  // Create the Mailer using your created Transport
  $mailer = Swift_Mailer::newInstance($transport);
  $body_text="Klik untuk verifikasi http://saina.simkug.com/web/app/sakuaja/verify.php?verify_email=";
    global $ver_code;
  $body_text.=$ver_code;

  // Create a message
  $message = Swift_Message::newInstance('Verifikasi Email Anda')
    ->setFrom(array('devptsai@gmail.com' => 'Pt. Samudera Aplikasi Indonesia'))
    ->setTo(array($_POST['email'] => $_POST['nama1']))
    ->setBody($body_text)
    ;

      // Tambah Kode Unik pada 'setBody'

  // Send the message
  $result = $mailer->send($message);
  
    }
?>