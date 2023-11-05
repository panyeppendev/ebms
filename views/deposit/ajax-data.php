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
                        <th>TABUNGAN</th>
                        <th>OPSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($datas[0]) {
                        $no = 1;
                        foreach ($datas[0] as $data) {
                            $avatarPath = FCPATH . 'assets/avatars/' . $data->id . '.jpg';

                            if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
                                $avatar = base_url('assets/avatars/default.jpg');
                            } else {
                                $avatar = base_url('assets/avatars/' . $data->id . '.jpg');
                            }

                            $city = str_replace(['Kabupaten', 'Kota'], '', $data->city);
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
                                    </small>
                                </td>
                                <td class="align-middle text-sm">
                                    <?= $data->village . ', ' . $city ?> <br>
                                    <?= $data->domicile ?>
                                </td>
                                <td class="align-middle">
                                    <small class="text-success">
                                        - <?= $data->class . ' - ' . $data->level ?> <br>
                                        - <?= $data->class_of_formal . ' - ' . $data->level_of_formal ?>
                                    </small>
                                </td>
                                <td class="align-middle">
                                    <?= 'Rp. ' . number_format($data->deposit, 0, ',', '.') ?>
                                </td>
                                <td class="align-middle">
                                    <button type="button" class="btn btn-default btn-sm" title="Detail Tabungan" onclick="detailDeposit('<?= $data->id ?>')">
                                        <i class="fas fa-list"></i>
                                    </button>
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
            Total : <b><?= $datas[1] ?> Orang<b>
        </div>
    </div>
</div>
