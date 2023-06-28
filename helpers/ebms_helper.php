<?php

function CekLogin()
{
    $ci = get_instance();

    if (!$ci->session->userdata('user_id')) {
        redirect('auth');
    }
}

function CekLoginAkses()
{
    $ci = get_instance();

    $id = $ci->session->userdata('user_id');
    $role = $ci->session->userdata('role');

    if (!$id) {
        redirect('auth');
    } else {
        $ci->load->model('MenuModel', 'mm');
        $url      = $ci->uri->segment(1);
        $idMenu = $ci->mm->getURL($url);

        $cekAkses = $ci->mm->cekUserMenu($idMenu, $role);

        if ($cekAkses <= 0 && $role != 'DEV') {
            redirect('block');
        }
    }
}

function getMenu()
{
    $ci = get_instance();
    $ci->load->model('menuModel');
    $role = $ci->session->userdata('role');

    return $ci->menuModel->getMenu($role);
}

function periodDisplay()
{
    $ci = get_instance();
    $ci->load->model('DataModel');

    return $ci->DataModel->periodDisplay();
}

function getHijri()
{
    $ci = get_instance();
    $ci->load->model('DataModel');

    return $ci->DataModel->getHijri();
}

function getHijriManual($date)
{
    $ci = get_instance();
    $ci->load->model('DataModel');

    return $ci->DataModel->getHijriManual($date);
}

function getHijriExplode()
{
    $ci = get_instance();
    $ci->load->model('DataModel');

    $result = $ci->DataModel->getHijri();
    return explode('-', $result);
}

function getMasehiExplode()
{
    $date = date('Y-m-d');
    return explode('-', $date);
}

function setDiff($date)
{
	$now = date('Y-m-d H:i:s');
	$diff = strtotime($now) - strtotime($date);

	if ($diff >= 1) {
		return ['LATE', $now];
	}

	return ['DISCIPLINE', $now];
}

function showDiff($date, $back)
{
	$date = date_create($date);
	$back = date_create($back); // waktu sekarang
	$diff  = date_diff($date, $back);

	$year = $diff->y;
	$month = $diff->m;
	$day = $diff->d;
	$hour = $diff->h;
	$minute = $diff->i;

	if ($year != 0) {
		$t = $year . ' tahun';
	} else {
		$t = '';
	}

	if ($month != 0) {
		$b = $month . ' bulan';
	} else {
		$b = '';
	}

	if ($day != 0) {
		$h = $day . ' hari';
	} else {
		$h = '';
	}

	if ($hour != 0) {
		$j = $hour . ' jam';
	} else {
		$j = '';
	}

	if ($minute != 0) {
		$m = $minute . ' menit';
	} else {
		$m = '';
	}

	return $t . ' ' . $b . ' ' . $h . ' ' . $j . ' ' . $m;
}

function showDiffSuspension($date, $back)
{
	$date = date_create($date);
	$back = date_create($back); // waktu sekarang
	$diff  = date_diff($date, $back);

	$year = $diff->y;
	$month = $diff->m;
	$day = $diff->d;
	$hour = $diff->h;
	$minute = $diff->i;

	if ($back > $date) {
		return 'Waktunya dinon-aktifkan';
	}

	if ($year != 0) {
		$t = $year . ' tahun';
	} else {
		$t = '';
	}

	if ($month != 0) {
		$b = $month . ' bulan';
	} else {
		$b = '';
	}

	if ($day != 0) {
		$h = $day . ' hari';
	} else {
		$h = '';
	}

	if ($hour != 0) {
		$j = $hour . ' jam';
	} else {
		$j = '';
	}

	if ($minute != 0) {
		$m = $minute . ' menit';
	} else {
		$m = '';
	}

	return 'Non-aktif pada : '.$t . ' ' . $b . ' ' . $h . ' ' . $j . ' ' . $m;
}

function grade($level)
{
	if ($level === 'I\'dadiyah') {
		$kelas = [
			'Pra Jilid',
			'Jilid 1',
			'Jilid 2',
			'Jilid 3',
			'Jilid 4',
			'Pra Paktik',
			'Praktik',
			'Takhossus'
		];
	}elseif ($level === 'Ula') {
		$kelas = [
			1,
			2,
			3,
			4
		];
	}elseif ($level === 'Wustho' || $level === 'Ulya'){
		$kelas = [
			1,
			2,
			3
		];
	}else{
		$kelas = [];
	}

	return $kelas;
}
