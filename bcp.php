<?php
session_start();
date_default_timezone_set("Asia/Jakarta");
if (isset($_SESSION['db'])) {
        $connection = mysqli_connect($_SESSION['host'], $_SESSION['user'], $_SESSION['pass']);
        $db = mysqli_select_db($connection, $_SESSION['db']);
        if ($db) {
                define('chActive', 'chAction');
        }
        else
                echo "Opss Terjadi kesalahan pada settingan";
}
include 'geshi/geshi.php';
if($_SERVER['REQUEST_METHOD']=='POST'):
        $qc = "/** \n";
        $qc .= "* Dibuat pada : ".date('Y-m-d H:i:s')."\n";
        $qc .= "* Oleh : Chabib Nurozak | chabibnurozak@gmail.com | www.facebook.com/chabibnurozak\n";
        $qc.= "* Versi Software : 1.0\n";
        $qc .= "/**\n";
        
        //jika ada tabel yang di pilih
        if(isset($_POST['tbsel'])){
                //lakukan perulangan tabel yang sesuai dengan jumlah yang di pilih
                foreach($_POST['tbsel'] as $key => $val){
                        $qc .= "\n/** ----- Memulai untuk Table $key -- **/ \n";
                        
                        //jika table yang di pilih, dengan backup strukture
                        if(isset($_POST['kolsel'][$key]['structure'])){
                                $createtable = mysqli_fetch_row(mysqli_query($connection,"SHOW CREATE TABLE ".$key));
                                $qc .= "\n".$createtable[1].";\n";
                        }
                        
                        //jika table yang di pilih, dengan backup data
                        if(isset($_POST['kolsel'][$key]['data'])){
                                $querytable = mysqli_query($connection,"SELECT * FROM ".$key);
                                $numfield = mysqli_num_fields($querytable);
                                if(mysqli_num_rows($querytable) > 0){
                                        while ($row = mysqli_fetch_row($querytable)){
                                                
                                                for($i=0; $i < $numfield; $i++){
                                                        $insertvalue[]  = "'".str_replace("\n","\\n",addslashes($row[$i]))."'";
                                                }
                                                $qc .= "\n INSERT INTO $key VALUES (".  implode(',',$insertvalue)."); \n";
                                        }
                                }
                        }
                        $qc .= "\n/** --- Akhir tabel $key -- */\n";
                }
        }
endif;
$geshi = new GeSHi($qc,'mysql');
$data['view'] = $geshi->parse_code();

echo json_encode($data);