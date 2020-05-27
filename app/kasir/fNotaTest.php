<?php
   session_start();
   $root_lib=$_SERVER["DOCUMENT_ROOT"];
   if (substr($root_lib,-1)!="/") {
       $root_lib=$root_lib."/";
   }
   include_once($root_lib.'app/kasir/setting.php');


	$size_judul="font-size:12px";
	$size_isi="font-size:10px";
    $text  = "
	<!DOCTYPE html>
<html>
<body>
	<table width='100%' border='0' cellspacing='0' cellpadding='0'>
  <tr>
    <td colspan='2' align='center' style='$size_judul'>Toko Asrama Putra TJ</td>
  </tr>
  <tr>
    <td colspan='2' align='center' style='$size_judul'>Jl.Telekomunikasi No. 1 Trs.Buahbatu </td>
  </tr>
  <tr>
    <td colspan='2' align='center' style='$size_judul'>Bandung</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align='right'>&nbsp;</td>
  </tr>
  <tr>
    <td width='90%' style='$size_isi'> Trenz Crispy Cracker Beef BBQ 100g X 1</td>
    <td width='10%' align='right' style='$size_isi'>5.145</td>
  </tr>
  <tr>
    <td style='$size_isi'>Doritos Barbeque 30g X 10</td>
    <td align='right' style='$size_isi'>1.976</td>
  </tr>
  <tr>
    <td style='$size_isi'> Trenz Crispy Cracker Beef BBQ 100g X 5</td>
    <td align='right' style='$size_isi'>5.145</td>
  </tr>
  <tr>
    <td style='$size_isi'>Doritos Barbeque 30g X 1</td>
    <td align='right' style='$size_isi'>1.976</td>
  </tr>
  
  <tr>
    <td style='$size_isi'> Trenz Crispy Cracker Beef BBQ 100g X 1</td>
    <td align='right' style='$size_isi'>5.145</td>
  </tr>
  <tr>
    <td style='$size_isi'>Doritos Barbeque 30g X 1</td>
    <td align='right' style='$size_isi'>1.976</td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td style='$size_isi'>Total Diskon</td>
    <td align='right' style='$size_isi'>0</td>
  </tr>
  <tr>
    <td style='$size_isi'>Total Set. Disc</td>
    <td align='right' style='$size_isi'>10.830</td>
  </tr>
  <tr>
    <td style='$size_isi'>Total Bayar</td>
    <td align='right' style='$size_isi'>15.000</td>
  </tr>
  <tr>
    <td style='$size_isi'>Kembalian</td>
    <td align='right' style='$size_isi'>4.170</td>
  </tr>
  <tr>
    <td style='$size_isi'>&nbsp;</td>
    <td align='right' style='$size_isi'>&nbsp;</td>
  </tr>
  <tr>
    <td colspan='2' align='center' style='$size_judul'>Terima Kasih </td>
  </tr>
</table>
</body>
</html>
";
	echo $text;
	echo "<script>
		window.print();
	</script>";

?>
