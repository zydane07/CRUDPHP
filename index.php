<?php

session_start();

if (strcmp($_SERVER["REQUEST_METHOD"], "POST") == 0) {
    try {

        // mulai session untuk error data tidak diisi
        $is_error = false;
        if ($_POST["nilai_uts"] == "") {
            $_SESSION["uts"] = "UTS TIDAK BOLEH KOSONG";
            $is_error = true;
        }
        if ($_POST["nilai_uas"] == "") {
            $_SESSION["uas"] = "UAS TIDAK BOLEH KOSONG";
            $is_error = true;
        }

        if ($is_error) {
            throw "test";
        }

        // panggil fungsi koneksi db
        $pdo = require_once 'connect.php';
        require_once 'function/fungsi_nilai.php';

        // simpan hasil input user
        $uts = $_POST["nilai_uts"];
        $uas = $_POST["nilai_uas"];

        // operasi nilai index dan akhir
        [$nilai_index, $nilai_akhir] = index_nilai($uts, $uas);

        // insert ke sql
        $sql = 'INSERT INTO nilai (nilai_uts, nilai_uas, nilai_akhir,index_nilai) VALUES (:uts, :uas, :nilai_index, :nilai_akhir)';

        $statement = $pdo->prepare($sql);

        $statement->execute([
            ':uts' => $uts,
            ':uas' => $uas,
            ':nilai_index' => $nilai_index,
            ':nilai_akhir' => $nilai_akhir,
        ]);
        $id_nilai = $pdo->lastInsertId();


        // tampilkan data
        $sql = "SELECT * FROM nilai WHERE id = :id_nilai";
        $statement = $pdo->prepare($sql);
        $statement->execute([
            ':id_nilai' => $id_nilai,
        ]);

        $data = $statement->fetch();

        // cek data mask
        echo "<pre>";
        var_dump($data);
        // foreach ($datas as $data) {
        //     var_dump($data["id"]);
        // }
        echo "</pre>";
    } catch (\Throwable $th) {
        // echo "err";
    }
}

?>

<?php require "partial/header.php" ?>

<h1 style="text-align: center">NILAI MAHASISWA</h1>
<form action="" method="post">
    <p>
        Masukkan Nilai Ujian Tengah Semester (UTS):
        <input type="text" name="nilai_uts"> <br>
        <?php if (isset($_SESSION["uts"])) : ?>
            <small><?= $_SESSION["uts"]; ?></small>
        <?php endif ?>
    </p>
    <p>
        Masukkan Nilai Ujian Akhir Semester (UAS):
        <input type="text" name="nilai_uas">
        <?php if (isset($_SESSION["uas"])) : ?> <br>
            <small><?= $_SESSION["uas"]; ?></small>
        <?php endif ?>
    </p>
    <button>input</button>
</form>

<h2>Hasil Nilai</h2>
<?php if (strcmp($_SERVER["REQUEST_METHOD"], "POST") == 0 && isset($data)) : ?>
    <table border="1" border-collapse: collapse;>
        <tr>
            <th>uts</th>
            <th>uas</th>
            <th>nilai akhir</th>
            <th>nilai index</th>
        </tr>
        <tr>
            <td><?= $data["nilai_uts"]; ?></td>
            <td><?= $data["nilai_uas"]; ?></td>
            <td><?= $data["nilai_akhir"]; ?></td>
            <td><?= $data["index_nilai"]; ?></td>

        </tr>
    </table>
<?php endif ?>

<?php
// menghapus session
if (isset($_SESSION["uts"])) {
    unset($_SESSION['uts']);
}
if (isset($_SESSION["uas"])) {
    unset($_SESSION['uas']);
}

// session_destroy()
?>


<?php require "partial/footer.php" ?>