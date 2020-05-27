<?php
    $request_method=$_SERVER["REQUEST_METHOD"];

    switch($request_method) {
        case 'GET':
            if(isset($_GET["fx"]) AND function_exists($_GET['fx'])){
                $_GET['fx']();
            }
        break;
    }

    function getKoneksi(){
        $root_lib=$_SERVER["DOCUMENT_ROOT"];
        include_once($root_lib."lib/koneksi.php");
        include_once($root_lib."lib/helpers.php");
    }

    function getArt(){
        getKoneksi();
        $kode_lokasi = $_GET['kode_lokasi'];  
        $result = array("message" => "", "rows" => 0, "status" => "" );                 
        //asalnya SELECT TOP 4
        $sql = "SELECT *, CONVERT(VARCHAR, tanggal, 110) AS tanggalnya FROM sai_konten LEFT JOIN sai_konten_galeri ON sai_konten.file_gambar = convert(varchar,sai_konten_galeri.id) LEFT JOIN sai_konten_ktg ON sai_konten_galeri.kode_ktg = sai_konten_ktg.kode_ktg WHERE sai_konten.flag_aktif = 1 AND sai_konten.jenis = 'Artikel' ORDER BY tanggal DESC";
        $rs = execute($sql);
        while($row = $rs->FetchNextObject($toupper))
        {
        
            $result['daftar'][] = (array)$row;
        
        }
                            
        $result['status'] = true;
        $result['sql'] = $sql;
        echo json_encode($result);
    }

    function getArtUtama(){
        getKoneksi();
        $no_konten = $_GET['no'];
        $kode_lokasi = $_GET['kode_lokasi'];  
        $result = array("message" => "", "rows" => 0, "status" => "" );                 
        $sql = "SELECT *, CONVERT(VARCHAR, tanggal, 110) AS tanggalnya FROM sai_konten LEFT JOIN sai_konten_galeri ON sai_konten.file_gambar = convert(varchar,sai_konten_galeri.id) LEFT JOIN sai_konten_ktg ON sai_konten_galeri.kode_ktg = sai_konten_ktg.kode_ktg WHERE sai_konten.flag_aktif = 1 AND sai_konten.jenis = 'Artikel' AND no_konten = '$no_konten' ORDER BY tanggalnya DESC";
        $rs = execute($sql);
        while($row = $rs->FetchNextObject($toupper))
        {
        
            $result['daftar'][] = (array)$row;
        
        }
                            
        $result['status'] = true;
        $result['sql'] = $sql;
        echo json_encode($result);
    }

    function getArtCari(){
        getKoneksi();
        $cari = $_GET['cari'];
        $kode_lokasi = $_GET['kode_lokasi'];  
        $result = array("message" => "", "rows" => 0, "status" => "" );                 
        $sql = "SELECT *, CONVERT(VARCHAR, tanggal, 110) AS tanggalnya, sai_konten_ktg.nama AS ktg FROM sai_konten INNER JOIN sai_konten_galeri ON sai_konten.file_gambar = convert(varchar,sai_konten_galeri.id) INNER JOIN sai_konten_ktg ON sai_konten.kode_kategori = sai_konten_ktg.kode_ktg WHERE sai_konten.flag_aktif = 1 AND sai_konten.jenis = 'Artikel' AND judul LIKE '%".$cari."%' OR sai_konten_ktg.nama LIKE '%".$cari."%' ORDER BY tanggalnya DESC";
        $rs = execute($sql);
        while($row = $rs->FetchNextObject($toupper))
        {
        
            $result['daftar'][] = (array)$row;
        
        }
                            
        $result['status'] = true;
        $result['sql'] = $sql;
        echo json_encode($result);
    }

    function getVidCari(){
        getKoneksi();
        $cari = $_GET['cari'];
        $kode_lokasi = $_GET['kode_lokasi'];  
        $result = array("message" => "", "rows" => 0, "status" => "" );                 
        $sql = "SELECT *, sai_konten.file_gambar AS file_gambarnya, CONVERT(VARCHAR, tanggal, 110) AS tanggalnya, sai_konten_ktg.nama AS ktg FROM sai_konten LEFT JOIN sai_konten_galeri ON sai_konten.file_gambar = convert(varchar,sai_konten_galeri.id) LEFT JOIN sai_konten_ktg ON sai_konten.kode_kategori = sai_konten_ktg.kode_ktg WHERE sai_konten.flag_aktif = 1 AND sai_konten.jenis = 'Video' AND judul LIKE '%".$cari."%' OR sai_konten_ktg.nama LIKE '%".$cari."%' ORDER BY tanggalnya DESC";
        $rs = execute($sql);
        while($row = $rs->FetchNextObject($toupper))
        {
        
            $result['daftar'][] = (array)$row;
        
        }
                            
        $result['status'] = true;
        $result['sql'] = $sql;
        echo json_encode($result);
    }

    function getArtJum(){
        getKoneksi();
        $cari = $_GET['cari'];
        $kode_lokasi = $_GET['kode_lokasi'];  
        $result = array("message" => "", "rows" => 0, "status" => "" );                 
        // $sql = "SELECT COUNT(*) AS urutan FROM sai_konten LEFT JOIN sai_konten_ktg ON sai_konten.kode_kategori = sai_konten_ktg.kode_ktg WHERE sai_konten.flag_aktif = 1 AND judul LIKE '%".$cari."%' OR sai_konten_ktg.nama LIKE '%".$cari."%'";

        // $sql = "SELECT COUNT(*) AS urutan FROM sai_konten LEFT JOIN sai_konten_galeri ON sai_konten.file_gambar = convert(varchar,sai_konten_galeri.id) LEFT JOIN sai_konten_ktg ON sai_konten_galeri.kode_ktg = sai_konten_ktg.kode_ktg WHERE sai_konten.flag_aktif = 1 AND judul LIKE '%".$cari."%' OR sai_konten_ktg.nama LIKE '%".$cari."%'";

        $sql = "SELECT COUNT(*) AS urutan 
        FROM sai_konten 
        
        LEFT JOIN sai_konten_galeri 
        ON sai_konten.file_gambar = convert(varchar,sai_konten_galeri.id) 
        
        LEFT JOIN sai_konten_ktg 
        ON sai_konten.kode_kategori = sai_konten_ktg.kode_ktg 
        
        WHERE sai_konten.flag_aktif = 1 
        
        AND judul LIKE '%".$cari."%' OR sai_konten_ktg.nama LIKE '%".$cari."%'";

        $rs = execute($sql);
        while($row = $rs->FetchNextObject($toupper))
        {
        
            $result['daftar'][] = (array)$row;
        
        }
                            
        $result['status'] = true;
        $result['sql'] = $sql;
        echo json_encode($result);
    }

    function getVid(){
        getKoneksi();
        $kode_lokasi = $_GET['kode_lokasi'];  
        $result = array("message" => "", "rows" => 0, "status" => "" );                 
        $sql = "SELECT *, CONVERT(VARCHAR, tanggal, 110) AS tanggalnya FROM sai_konten WHERE jenis = 'Video' AND flag_aktif = 1";
        $rs = execute($sql);
        while($row = $rs->FetchNextObject($toupper))
        {
        
            $result['daftar'][] = (array)$row;
        
        }
                            
        $result['status'] = true;
        $result['sql'] = $sql;
        echo json_encode($result);
    }

    function getVidUtama(){
        getKoneksi();
        $no_konten = $_GET['no'];
        $kode_lokasi = $_GET['kode_lokasi'];  
        $result = array("message" => "", "rows" => 0, "status" => "" );                 
        $sql = "SELECT *, CONVERT(VARCHAR, tanggal, 110) AS tanggalnya FROM sai_konten WHERE jenis = 'Video' AND flag_aktif = 1 AND no_konten = '$no_konten'";
        $rs = execute($sql);
        while($row = $rs->FetchNextObject($toupper))
        {
        
            $result['daftar'][] = (array)$row;
        
        }
                            
        $result['status'] = true;
        $result['sql'] = $sql;
        echo json_encode($result);
    }

    function getArtVid(){
        getKoneksi();
        $cari = $_GET['cari'];
        $kode_lokasi = $_GET['kode_lokasi'];  
        $result = array("message" => "", "rows" => 0, "status" => "" );                 
        // $sql = "SELECT *, sai_konten.file_gambar AS filenya, sai_konten.jenis AS jenisal, CONVERT(VARCHAR, tanggal, 110) AS tanggalnya FROM sai_konten LEFT JOIN sai_konten_galeri ON sai_konten.file_gambar = convert(varchar,sai_konten_galeri.id) LEFT JOIN sai_konten_ktg ON sai_konten_galeri.kode_ktg = sai_konten_ktg.kode_ktg WHERE sai_konten.flag_aktif = 1 AND judul LIKE '%".$cari."%' OR sai_konten_ktg.nama LIKE '%".$cari."%' ORDER BY tanggal DESC";
        $sql = "SELECT *, sai_konten_ktg.nama AS namakat, sai_konten.file_gambar AS filenya, 
        sai_konten.jenis AS jenisal, 
        CONVERT(VARCHAR, tanggal, 110) AS tanggalnya 
        
        FROM sai_konten 
        
        LEFT JOIN sai_konten_ktg 
        ON sai_konten.kode_kategori = sai_konten_ktg.kode_ktg 
        
        LEFT JOIN sai_konten_galeri 
        ON sai_konten.file_gambar = convert(varchar,sai_konten_galeri.id) 
        
        WHERE sai_konten.flag_aktif = 1  
        
        AND judul LIKE '%".$cari."%' OR sai_konten_ktg.nama LIKE '%".$cari."%' ORDER BY tanggal DESC";
        $rs = execute($sql);
        while($row = $rs->FetchNextObject($toupper))
        {
        
            $result['daftar'][] = (array)$row;
        
        }
                            
        $result['status'] = true;
        $result['sql'] = $sql;
        echo json_encode($result);
    }

    function getKatArt(){
        getKoneksi();
        $kode_lokasi = $_GET['kode_lokasi'];  
        $result = array("message" => "", "rows" => 0, "status" => "" );                 
        $sql = "SELECT DISTINCT nama FROM sai_konten LEFT JOIN sai_konten_ktg ON sai_konten.kode_kategori = sai_konten_ktg.kode_ktg WHERE sai_konten.flag_aktif = 1 AND sai_konten.jenis = 'Artikel'";
        $rs = execute($sql);
        while($row = $rs->FetchNextObject($toupper))
        {
        
            $result['daftar'][] = (array)$row;
        
        }
                            
        $result['status'] = true;
        $result['sql'] = $sql;
        echo json_encode($result);
    }

    function getKatVid(){
        getKoneksi();
        $kode_lokasi = $_GET['kode_lokasi'];  
        $result = array("message" => "", "rows" => 0, "status" => "" );                 
        $sql = "SELECT DISTINCT nama FROM sai_konten LEFT JOIN sai_konten_ktg ON sai_konten.kode_kategori = sai_konten_ktg.kode_ktg WHERE sai_konten.flag_aktif = 1 AND sai_konten.jenis = 'Video'";
        $rs = execute($sql);
        while($row = $rs->FetchNextObject($toupper))
        {
        
            $result['daftar'][] = (array)$row;
        
        }
                            
        $result['status'] = true;
        $result['sql'] = $sql;
        echo json_encode($result);
    }

    // function getGam(){
    //     getKoneksi();
    //     $kode_lokasi = $_GET['kode_lokasi'];  
    //     $result = array("message" => "", "rows" => 0, "status" => "" );                 
    //     $sql = "SELECT * FROM sai_konten_galeri WHERE kode_lokasi = '$kode_lokasi' AND flag_aktif='1'";
    //     $rs = execute($sql);
    //     while($row = $rs->FetchNextObject($toupper))
    //     {
        
    //         $result['daftar'][] = (array)$row;
        
    //     }
                            
    //     $result['status'] = true;
    //     $result['sql'] = $sql;
    //     echo json_encode($result);
    // }
?>