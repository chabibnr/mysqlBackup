<?php
include 'sesi.php';

if (isset($_SESSION['db'])) {
        $connection = mysqli_connect($_SESSION['host'], $_SESSION['user'], $_SESSION['pass']);
        $db = mysqli_select_db($connection, $_SESSION['db']);
        if ($db) {
                define('chActive', 'chAction');
        }
        else
                echo "Opss Terjadi kesalahan pada settingan";
}
if (!defined('chActive'))
        exit();
$table = mysqli_query($connection, "Show tables");
?>
<html>
        <head>
                <style>
                        table td{ border: 1px solid #428bca; border-bottom: none; word-wrap: break-word}
                </style>
                <script src="./jquery.js"></script>

        </head>
        <body>
                <div style="float: left; width: 30%">
                        <form class="pilihbackup">
                                <table style="border: 1px solid #428bca">
                                        <thead>
                                                <tr style="background: #428bca; color: #fff;">
                                                        <th rowspan="2">#</th>
                                                        <th rowspan="2">Table</th>
                                                        <th colspan="2">backup</th>
                                                </tr>
                                                <tr style="background: #428bca; color: #fff;">
                                                        <th>Struktur</th>
                                                        <th>Data</th>
                                                </tr>
                                        </thead>
                                        <tbody>
                                                <?php
                                                $tcreate = '';
                                                while ($tb = mysqli_fetch_row($table)) {
                                                        $tcreate .= "<tr>";
                                                        $tcreate .= "<td><input type='checkbox' name='tbsel[" . $tb[0] . "]' /></td>";
                                                        $tcreate .= "<td>" . $tb[0] . "</td>";
                                                        $tcreate .= "<td><input type='checkbox' name='kolsel[" . $tb[0] . "][structure]' /></td>";
                                                        $tcreate .= "<td><input type='checkbox' name='kolsel[" . $tb[0] . "][data]' /></td>";
                                                        $tcreate .= "</tr>";
                                                }
                                                echo $tcreate;
                                                ?>
                                        </tbody>
                                </table>
                        </form>
                </div>
                <div style="float:left; margin-left: 20px; width: 65%; border: 1px solid #428bca ">
                        <div style="background: #428bca; color: #fff; padding: 10px 15px;" id="statusMessage">Kueri </div>
                        <div style="padding: 5px; overflow: auto; height: 500px; word-wrap: break-word" id="queryview">Pilih Table yang akan di backup, kemudian pilih juga yang mau di backup, bisa salah satu atau kedunya (struktur dan data)</div>
                </div>
                <script type="text/javascript">
                        function getBackupText(){
                                $("#statusMessage").html("Sedang melakukan perintah, Silahkan tunggu. . . .")
                                $.ajax({
                                        url : 'bcp.php',
                                        type : 'post',
                                        dataType : 'json',
                                        data: $("form.pilihbackup").serialize(),
                                        success : function(msg){
                                                $("#queryview").html(msg.view)
                                                $("#statusMessage").html("Hasil")
                                        },
                                        error: function(){
                                                $("#statusMessage").html("Terjadi kesalahan.")
                                                alert('Error, cek koneksi atau kontak pembuat di : chabibnurozak@gmail.com | 085643281543')
                                        }
                                })
                        }
                        $(function() {
                                $("form.pilihbackup input[type=checkbox]").change(function() {
                                        getBackupText()
                                })
                        })
                </script>
        </body>
</html>
