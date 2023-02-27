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


function ageCounter($birth)
{
	$birthDate = new DateTime($birth);
	$today = new DateTime("today");
	if ($birthDate > $today) {
		exit("Tanggal lahir tidak valid");
	}
	$y = $today->diff($birthDate)->y;
	$m = $today->diff($birthDate)->m;
	$d = $today->diff($birthDate)->d;
	if ($y > 0) {
		$y = $y.' tahun';
	}else{
		$y = '';
	}

	return $y;
}

function diffDayCounter($from, $finish) {
	$tgl1 = strtotime($from);
	$tgl2 = strtotime($finish);

	$jarak = $tgl2 - $tgl1;

	$hari = $jarak / 60 / 60 / 24;
	return $hari;
}

function dateDisplayWithDay($date) {
	$tgl = date('Y-m-d', strtotime($date));
	$jam = date('H:i', strtotime($date));

	$days = [1 =>    'Senin',
		'Selasa',
		'Rabu',
		'Kamis',
		'Jum\'at',
		'Sabtu',
		'Minggu'
	];

	$months = [
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
	];
	$explodeDate = explode('-', $tgl);
	$dayNum = date('N', strtotime($date));

	return 'hari <b>'.$days[$dayNum].'</b> tanggal <b>'.$explodeDate[2].' '.$months[(int)$explodeDate[1]].' '.$explodeDate[0].'</b> pukul <b>'.$jam .' WIB</b>';

}

