<div class="col-12">
    <div class="card" style="height: 71.8vh;">
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0" style="height: 100%;" id="cardScroll">
            <table class="table table-head-fixed table-hover">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th colspan="2" class="text-center">NAMA</th>
                        <th>ALAMAT</th>
                        <th>ADMINISTRASI</th>
                        <th>PAKET</th>
                        <th>INFORMASI</th>
                        <th>OPSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($datas) {
                        $no = 1;
                        foreach ($datas as $data) {
                            $avatarPath = FCPATH . 'assets/avatars/' . $data->id . '.jpg';

                            if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
                                $avatar = base_url('assets/avatars/default.jpg');
                            } else {
                                $avatar = base_url('assets/avatars/' . $data->id . '.jpg');
                            }

                            $city = str_replace(['Kabupaten', 'Kota'], '', $data->city);
                            $status = $data->status;
                            $transport = ['', ' + Transport'];
                    ?>
                            <tr>
                                <td class="align-middle"><?= $no++ ?></td>
                                <td>
                                    <img style="border-radius: 5px;" alt="Foto <?= $data->name ?>" width="45px" class="table-avatar" src="<?= $avatar ?>">
                                </td>
                                <td class="align-middle">
                                    <b><?= $data->name ?></b>
                                    <br>
                                    <small class="text-success">
                                        NIS : <?= $data->id ?>
                                        <br>
                                        Kuitansi : <?= $data->id_package ?>
                                    </small>
                                </td>
                                <td class="align-middle"><?= $data->village . '<br>' . $city ?></td>
                                <td class="align-middle">
                                    <?= $data->domicile ?> <br>
                                    <small class="text-success">
                                        - <?= $data->class . ' - ' . $data->level ?> <br>
                                        - <?= $data->class_of_formal . ' - ' . $data->level_of_formal ?>
                                    </small>
                                </td>
                                <td class="align-middle">
                                    <small class="text-success">
                                        <?= 'Paket ' . $data->package . $transport[$data->transport] ?>
                                    </small>
                                    <br>
                                    <?= 'Rp. ' . number_format($data->amount, 0, ',', '.') ?>
                                </td>
                                <td class="align-middle text-xs">
                                    <?php
                                    if ($status == 'INORDER') {
                                        echo '<span class="text-danger">Belum beli paket</span>';
                                    } else {
                                        if ($data->activated_at) {
                                    ?>
                                            <span class="text-success">Aktif : <?= dateIDFormatShort($data->activated_at) ?></span> <br>
                                            <span class="text-danger">Expired : <?= dateIDFormatShort($data->expired_at) ?></span>
                                    <?php
                                        } else {
                                            echo '<span class="text-danger">Belum diaktivasi</span>';
                                        }
                                    }
                                    ?>
                                </td>
                                <td class="align-middle">
                                    <div class="btn-group">
                                        <?php if ($status != 'INORDER') { ?>
                                            <form target="_blank" action="<?= base_url() ?>package/printdata" method="post">
                                                <input type="hidden" name="id_package" id="id-package" value="<?= $data->id_package ?>">
                                                <button type="submit" class="btn btn-default btn-sm" title="Print Kuitansi">
                                                    <i class="fas fa-print"></i>
                                                </button>
                                            </form>
                                        <?php } ?>
                                        <?php
                                        if ($status == 'INACTIVE') {
                                        ?>
                                            <button type="button" class="btn btn-default btn-sm" title="Aktifkan Paket" onclick="activePackage('<?= $data->id_package ?>')">
                                                <i class="fas fa-check-double"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" title="Hapus Paket" onclick="deletePackage('<?= $data->id_package ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo '<tr class="text-center"><td colspan="8"><h6 class="text-danger">Tak ada data untuk ditampilkan</h6></td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <b><b>
        </div>
    </div>
</div>