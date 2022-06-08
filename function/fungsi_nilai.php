<?php

function index_nilai($nilai_uts, $nilai_uas) {
    $nilai_index = ($nilai_uts + $nilai_uas)/2;

    if ($nilai_index >= 80)  return [$nilai_index,"A"];
    if ($nilai_index >= 70)  return [$nilai_index,"B"];
    if ($nilai_index >= 50)  return [$nilai_index,"C"];
    if ($nilai_index >= 40)  return [$nilai_index,"D"];
    if ($nilai_index >= 30)  return [$nilai_index,"Gagal"];
}


