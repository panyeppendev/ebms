<?php
if ($data) {
    $avatarPath = FCPATH . 'assets/avatars/' . $data->id . '.jpg';

    if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
        $avatar = base_url('assets/avatars/default.jpg');
    } else {
        $avatar = base_url('assets/avatars/' . $data->id . '.jpg');
    }

    $city = str_replace(['Kabupaten', 'Kota'], '', $data->city);
?>
    <div class="col-12 px-4">
        <div class="row">
            <div class="col-3">
                <div class="card mb-0">
                    <div class="card-body box-profile p-0">
                        <div class="text-center">
                            <img src="<?= $avatar ?>" alt="IMAGE OF <?= $data->name ?>" style="width: 100%; border-radius: 3px;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <dl class="row">
                    <dt class="col-sm-7 font-weight-normal mb-1"><?= $data->id ?></dt>
                    <dd class="col-sm-5 mb-1">
                        <span class="badge badge-success"> <?= $data->domicile ?></span>
                    </dd>
                    <dt class="col-sm-7 mb-1"><?= $data->name ?></dt>
                    <dd class="col-sm-5 mb-1"><?= $data->class ?> - <?= $data->level ?></dd>
                    <dt class="col-sm-7 font-weight-normal mb-1"><?= $data->place_of_birth ?>, <?= dateIDFormat($data->date_of_birth) ?></dt>
                    <dd class="col-sm-5 mb-1"><?= $data->class_of_formal ?> - <?= $data->level_of_formal ?></dd>
                    <dt class="col-sm-7 font-weight-normal mb-1"><?= $data->village ?>, <?= $city ?></dt>
                    <dd class="col-sm-5 mb-1"></dd>
                </dl>
                <hr>
                <h6 class="">
                    Daftar Persyaratan Liburan <br>
                    <small class="text-success"><i>Menyelesaikan <?= $yes ?> dari <?= $total ?> item</i></small>
                </h6>
                <table border="0" style="width: 100%">
                    <tbody>
                        <?php
                        if ($requirement) {
                            $kelas = $data->class;
                            $no = 1;
                            foreach ($requirement as $r) {
                                $account = $r->account;
                                $id = $r->id;

                                if ($kelas == 'Takhossus') {
                                    $kelasSatu = 'Minimal 3 kali';
                                    $kelasDua = 'Ajian Kitab Sore dan Ajian Kitab Sabtu Malam';
                                } elseif ($kelas == 'Praktik') {
                                    $kelasSatu = 'Minimal 2 kali';
                                    $kelasDua = 'Ajian Kitab Sabtu Malam';
                                } else {
                                    $kelasSatu = 'Minimal 2 kali';
                                    $kelasDua = 'Ajian Kitab Sabtu Malam';
                                }

                                if ($account == 3) {
                                    $name = $r->name . ' ' . $kelasSatu;
                                } elseif ($account == 6) {
                                    $name = $r->name . ' ' . $kelasDua;
                                } else {
                                    $name = $r->name;
                                }

                                if ($r->status == 'YES') {
                                    $status = '<i class="fas fa-check text-success"></i>';
                                } else {
                                    $status = '<i class="fas fa-times text-danger"></i>';
                                }
                        ?>
                                <tr>
                                    <td class="align-top" style="width: 5%"><?= $no++ ?></td>
                                    <td class="align-top" style="width: 90%"><?= $name ?></td>
                                    <td class="align-top text-right" style="width: 5%"><?= $status ?></td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>