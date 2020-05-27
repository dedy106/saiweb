
<!DOCTYPE html>
<html>
 <head>
  <title>Webslesson Tutorial | Make Treeview using Bootstrap Treeview Ajax JQuery with PHP</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>    
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-treeview/1.2.0/bootstrap-treeview.min.js"></script>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-treeview/1.2.0/bootstrap-treeview.min.css" />
  
  <style>
  </style>
 </head>
 <body>
  <br /><br />
  <div class="container" style="width:900px;">
   <h2 align="center">Make Treeview using Bootstrap Treeview Ajax JQuery with PHP</h2>
   <br /><br />
   <div id="treeview"></div>
  </div>
 </body>
</html>
<?php
$root=$_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME'];
?>
<script>
$(document).ready(function(){
 $.ajax({ 
//    url: "fetch.php",
   url:"<?=$root?>/api/belajar/Siswa.php?fx=tesJSON",
   method:"POST",
   dataType: "json",       
   success: function(data)  
   {
  $('#treeview').treeview({data: data});
   }   
 });
 
});
</script>