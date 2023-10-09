<div class="col-12">
    <div class="card" style="height: 71.8vh;">
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0" style="height: 100%;" id="cardScroll">
            <table class="table table-head-fixed table-hover">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NAMA</th>
                        <th>AKSES</th>
						<th>URL</th>
                        <th>STATUS</th>
                        <th>OPSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($datas) {
                        $no = 1;
                        foreach ($datas as $data) {

                            $status = $data->status;
                            if ($status == 'ACTIVE') {
                                $classStatus = 'success';
                                $text = 'AKTIF';
                            } else {
                                $classStatus = 'danger';
                                $text = 'NON-AKTIF';
                            }

                    ?>
                            <tr>
                                <td class="align-middle"><?= $no++ ?></td>
                                <td class="align-middle">
                                    <?= $data->name ?>
                                </td>
                                <td class="align-middle">
                                    <ul>
                                        <?php
										$roleMenu = $this->mm->roleMenu($data->id);
                                        if ($roleMenu) {
                                            foreach ($roleMenu as $d) {
                                        ?>
                                                <li>
                                                    <?= $d->name ?>
                                                    <a href="javascript:" onclick="deleteRoleMenu(<?= $d->id ?>)">
                                                        <small>Hentikan Akses</small>
                                                    </a>
                                                </li>
                                        <?php
                                            }
                                        } else {
                                            echo '<li class="text-danger">Akses menu belum diatur</li>';
                                        }
                                        ?>
                                    </ul>
                                </td>
								<td><?= $data->url ?></td>
                                <td class="align-middle">
                                    <span class="badge badge-<?= $classStatus ?>"><?= $text ?></span>
                                    <?php
                                    if ($status == 'ACTIVE') {
                                    ?>
                                        <a href="javascript:" onclick="updateStatus(<?= $data->id ?>, 'INACTIVE')">
                                            <small>Non-Aktifkan</small>
                                        </a>
                                    <?php
                                    }
                                    ?>
                                    <?php
                                    if ($status == 'INACTIVE') {
                                    ?>
                                        <a href="javascript:" onclick="updateStatus(<?= $data->id ?>, 'ACTIVE')">
                                            <small>Aktifkan</small>
                                        </a>
                                    <?php
                                    }
                                    ?>
                                </td>
                                <td class="align-middle">
									<button onclick="getID('<?= $data->id ?>')" data-toggle="modal" data-target="#modal-set" type="button" class="btn btn-default btn-sm">
										<i class="fas fa-cogs"></i>
									</button>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo '<tr class="text-center"><td colspan="5"><h6 class="text-danger">Tak ada data untuk ditampilkan</h6></td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer"></div>
    </div>
</div>
