<?php
if ($data['status'] == 200) {
    $avatarPath = FCPATH . 'assets/avatars/' . $data['student']->id . '.jpg';

    if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
        $avatar = base_url('assets/avatars/default.jpg');
    } else {
        $avatar = base_url('assets/avatars/' . $data['student']->id . '.jpg');
    }

    $city = str_replace(['Kabupaten', 'Kota'], '', $data['student']->city);
?>
    <div class="row">
        <div class="col-4">
            <div class="box-profile">
                <div class="text-center">
                    <img src="<?= $avatar ?>" alt="IMAGE OF <?= $data['student']->name ?>" style="width: 100%; border-radius: 3px;">
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="row">
                <div class="col-12">
                    <dl class="row">
                        <dt class="col-sm-3 font-weight-normal mb-1">Nama</dt>
                        <dd class="col-sm-9 mb-1 font-weight-bold"><?= $data['student']->name ?></dd>
                        <dt class="col-sm-3 font-weight-normal mb-1">Alamat</dt>
                        <dd class="col-sm-9 mb-1">
                            <?= $data['student']->village ?>, <?= $city ?>
                        </dd>
                        <dt class="col-sm-3 font-weight-normal mb-1">Domisili</dt>
                        <dd class="col-sm-9 mb-1">
                            <?= $data['student']->domicile ?>
                        </dd>
                        <dt class="col-sm-3 font-weight-normal mb-1">Diniyah</dt>
                        <dd class="col-sm-9 mb-1"><?= $data['student']->class ?> - <?= $data['student']->level ?></dd>
                        <dt class="col-sm-3 font-weight-normal mb-1">Formal</dt>
                        <dd class="col-sm-9 mb-1"><?= $data['student']->class_of_formal ?> - <?= $data['student']->level_of_formal ?></dd>
                    </dl>
                </div>
                <div class="col-8 text-xs">
                    <?php
                    if ($data['message'] != 'BLOCKED') {
                    ?>
                        <div class="callout callout-danger">
                            <?php if ($data['message'] == 'ACTIVE') {
                                echo '<h6>Kartu ini sedang aktif</h6>';
                            } elseif ($data['message'] == 'INACTIVE') {
                                echo '<h6>Kartu ini belum diaktivasi</h6>';
                            }
                            ?>
                        </div>
                    <?php } else { ?>
                        <button class="btn btn-sm btn-primary btn-block" onclick="save(<?= $data['id'] ?>, <?= $data['nis'] ?>)">
                            Aktifkan Kartu
                        </button>
                    <?php
                    } ?>
                </div>
            </div>
        </div>
    </div>
<?php
} else {
    echo 'Gagal memuat data. Segera hubungi Developer';
}
?>