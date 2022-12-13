<?php
function datetimeIDFormat($tanggal)
{
    $tgl = date('Y-m-d', strtotime($tanggal));
    $jam = date('H:i:s', strtotime($tanggal));

    $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tgl);

    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0] . ' ' . $jam;
}

function datetimeIDShirtFormat($tanggal)
{
    $tgl = date('Y-m-d', strtotime($tanggal));
    $jam = date('H:i:s', strtotime($tanggal));
    $pecahkan = explode('-', $tgl);

    return $pecahkan[2] . '/' . $pecahkan[1] . '/' . $pecahkan[0] . ' ' . $jam;
}

function dateIDFormat($tanggal)
{
    $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);

    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

function dateIDFormatShort($tanggal)
{
    $bulan = array(
        1 =>   'Jan',
        'Feb',
        'Mar',
        'Apr',
        'Mei',
        'Jun',
        'Jul',
        'Agu',
        'Sep',
        'Okt',
        'Nov',
        'Des'
    );
    $pecahkan = explode('-', $tanggal);

    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

function dateHijriFormat($tanggal)
{
    $bulan = array(
        '--',
        'Muharram',
        'Shafar',
        'Rabi\'ul Awal',
        'Rabi\'ul Tsani',
        'Jumadal Ula',
        'Jumadal Tsaniyah',
        'Rajab',
        'Sya\'ban',
        'Ramadhan',
        'Syawal',
        'Dzul Qo\'dah',
        'Dzul Hijjah'
    );

    $pecahkan = explode('-', $tanggal);

    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

function step($step)
{
    $steps = [
        'Belum diatur',
        'Tahap I',
        'Tahap II',
        'Tahap III',
        'Tahap IV',
        'Tahap V',
        'Tahap VI',
        'Tahap VII',
        'Tahap VIII',
        'Tahap IX',
        'Tahap X',
        'Tahap XI',
    ];
    return $steps[$step];
}
