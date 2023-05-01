<?php $this->load->view('partials/header'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content p-3">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header py-2 pr-2">
                        <h6 class="card-title mt-1">Data Santri Per Kelas</h6>
                        <button type="button" class="btn btn-sm btn-primary float-right" id="addstudent">
                            <i class="fa fa-plus-circle"></i>
                            Atur Kelas
                        </button>
						<select id="changeLevel" onchange="loadData()" class="form-control form-control-sm float-right mr-2" style="width: 150px">
							<option value="">..:Semua Tingkat:..</option>
							<option value="I'dadiyah">I'dadiyah</option>
							<option value="Ibtidaiyah">Ibtidaiyah</option>
							<option value="Tsanawiyah">Tsanawiyah</option>
							<option value="Aliyah">Aliyah</option>
						</select>
						<select id="changeRombel" onchange="loadData()" class="form-control form-control-sm float-right mr-2" style="width: 100px">
							<option value="">..:Rombel:..</option>
							<option value="A">A</option>
							<option value="B">B</option>
							<option value="C">C</option>
							<option value="D">D</option>
							<option value="E">E</option>
							<option value="F">F</option>
							<option value="G">G</option>
							<option value="H">H</option>
							<option value="I">I</option>
							<option value="J">J</option>
						</select>
                        <select id="changeClass" onchange="loadData()" class="form-control form-control-sm float-right mr-2" style="width: 150px">
                            <option value="">..:Semua Kelas:..</option>
                            <option value="Pra Metode">Pra Metode</option>
                            <option value="Jilid 1">Jilid 1</option>
                            <option value="Jilid 2">Jilid 2</option>
                            <option value="Jilid 3">Jilid 3</option>
                            <option value="Jilid 4">Jilid 4</option>
                            <option value="Pra Praktik">Pra Praktik</option>
                            <option value="Praktik">Praktik</option>
                            <option value="Takhossus">Takhossus</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                        <input autocomplete="off" type="text" id="changeName" class="form-control form-control-sm float-right mr-2" style="width: 250px" placeholder="Tekan F2 lalu masukkan nama" autofocus onkeyup="loadData()" />
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="showStudent"></div>
    </section>
    <!-- /.content -->
</div>
<!-- Modal Entri Data Santri -->
<div class="modal fade" id="modal-student" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">Data Calon Santri/Murid</h6>
            </div>
            <div class="modal-body ui-front">
                <form id="formstudent" autocomplete="off">
                    <input type="hidden" name="id" id="id" value="0">
                    <input type="hidden" name="period" id="period" value="<?= $period ?>">
                    <div class="row">
                        <div class="col-2">
                            <div class="form-group">
                                <label for="nik">NIK Santri</label>
                                <input data-inputmask="'mask' : '9999999999999999'" data-mask="" type="text" name="nik" class="form-control form-control-border" tabindex="1" id="nik">
                                <small class="text-danger messages" id="errornik"></small>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="kk">KK Santri</label>
                                <input data-inputmask="'mask' : '9999999999999999'" data-mask="" type="text" name="kk" class="form-control form-control-border" tabindex="2" id="kk">
                                <small class="text-danger messages" id="errorkk"></small>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label for="name">Nama Santri</label>
                                <input type="text" name="name" class="form-control form-control-border text-uppercase" id="name" tabindex="3">
                                <small class="text-danger messages" id="errorname"></small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="last_education">Pendidikan Terakhir</label>
                                <select class="custom-select form-control-border" id="last_education" name="last_education" tabindex="4">
                                    <option value="">..:Pilih:..</option>
                                    <option value="Belum/Tidak Sekolah">Belum/Tidak Sekolah</option>
                                    <option value="Tamat SD Sederajat">Tamat SD Sederajat</option>
                                    <option value="Tamat SLTP Sederajat">Tamat SLTP Sederajat</option>
                                    <option value="Tamat SLTA Sederajat">Tamat SLTA Sederajat</option>
                                    <option value="Tamat D3/Sarjana Sederajat">Tamat D3/Sarjana Sederajat</option>
                                    <option value="Tamat Pascasarjana Sederajat">Tamat Pascasarjana Sederajat</option>
                                </select>
                                <small class="text-danger messages" id="errorlast_education"></small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="place_of_birth">Tempat Lahir</label>
                                <input type="text" name="place_of_birth" class="form-control form-control-border text-capitalize" id="place_of_birth" tabindex="5">
                                <small class="text-danger messages" id="errorplace_of_birth"></small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="date_of_birth">Tanggal Lahir</label>
                                <div class="row">
                                    <div class="col-3">
                                        <select class="custom-select form-control-border" id="date_of_birth" name="date_of_birth" tabindex="6">
                                            <option value="">00</option>
                                            <?php
                                            $l = 1;
                                            for ($i = 1; $i <= 31; $i++) {
                                            ?>
                                                <option value="<?= sprintf('%02d', $i); ?>">
                                                    <?= sprintf('%02d', $i); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-5">
                                        <select class="custom-select form-control-border" id="month_of_birth" name="month_of_birth" tabindex="7">
                                            <option value="">00</option>
                                            <?php
                                            $bulan = [
                                                1 =>
                                                'Januari',
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
                                            $k = 1;
                                            for ($p = 1; $p <= 12; $p++) {
                                            ?>
                                                <option value="<?= sprintf('%02d', $p); ?>">
                                                    <?= $bulan[$p]; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <select class="custom-select form-control-border" id="year_of_birth" name="year_of_birth" tabindex="8">
                                            <option value="">0000</option>
                                            <?php
                                            $now = date('Y');
                                            for ($b = 1990; $b <= $now; $b++) {
                                            ?>
                                                <option value="<?= $b; ?>">
                                                    <?= $b; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <small class="text-danger messages" id="errordate_of_birth"></small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="address">Alamat</label>
                                <input type="text" name="address" class="form-control form-control-border text-capitalize" id="address" tabindex="9" placeholder="Dusun, RT dan RW">
                                <small class="text-danger messages" id="erroraddress"></small>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="postal_code">Kode Pos</label>
                                <input type="text" name="postal_code" class="form-control form-control-border text-capitalize" id="postal_code" readonly>
                                <small class="text-danger messages" id="errorpostal_code"></small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="province">Provinsi</label>
                                <input type="text" name="province" class="form-control form-control-border text-capitalize" id="province" tabindex="10">
                                <small class="text-danger messages" id="errorprovince"></small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="city">Kabupaten/Kota</label>
                                <input type="text" name="city" class="form-control form-control-border text-capitalize" id="city" tabindex="11" readonly>
                                <small class="text-danger messages" id="errorcity"></small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="district">Kecamatan</label>
                                <input type="text" name="district" class="form-control form-control-border text-capitalize" id="district" tabindex="12" readonly>
                                <small class="text-danger messages" id="errordistrict"></small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="village">Desa/Kelurahan</label>
                                <input type="text" name="village" class="form-control form-control-border text-capitalize" id="village" tabindex="13" readonly>
                                <small class="text-danger messages" id="errorvillage"></small>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="father_nik">NIK Ayah</label>
                                <input data-inputmask="'mask' : '9999999999999999'" data-mask="" type="text" name="father_nik" class="form-control form-control-border" tabindex="14" id="father_nik">
                                <small class="text-danger messages" id="errorfather_nik"></small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="father">Nama Ayah</label>
                                <input type="text" name="father" class="form-control form-control-border text-uppercase" id="father" tabindex="15">
                                <small class="text-danger messages" id="errorfather"></small>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="mother_nik">NIK Ibu</label>
                                <input data-inputmask="'mask' : '9999999999999999'" data-mask="" type="text" name="mother_nik" class="form-control form-control-border" tabindex="16" id="mother_nik">
                                <small class="text-danger messages" id="errormother_nik"></small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="mother">Nama Ibu</label>
                                <input type="text" name="mother" class="form-control form-control-border text-uppercase" id="mother" tabindex="17">
                                <small class="text-danger messages" id="errormother"></small>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="phone">Nomor Handphone</label>
                                <input data-inputmask="'mask' : '999-999-999-999'" data-mask="" type="text" name="phone" class="form-control form-control-border" tabindex="18" id="phone">
                                <small class="text-danger messages" id="errorphone"></small>
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="form-group">
                                <label for="status_of_domicile">Status Domisili</label>
                                <select class="custom-select form-control-border" id="status_of_domicile" name="status_of_domicile" tabindex="19">
                                    <option value="Asrama">Asrama</option>
                                    <option value="Non-Asrama">Non-Asrama</option>
                                </select>
                                <small class="text-danger messages" id="errorstatus_of_domicile"></small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="domicile">Domisili</label>
                                <select class="custom-select form-control-border" id="domicile" name="domicile" tabindex="20">
                                    <option value="">..:Pilih:..</option>
                                    <option value="Imam Ghazali">Imam Ghazali</option>
                                    <option value="Imam Maliki">Imam Maliki</option>
                                    <option value="Imam Hanafi">Imam Hanafi</option>
                                    <option value="Imam Hambali">Imam Hambali</option>
                                    <option value="Imam Sibaweh">Imam Sibaweh</option>
                                    <option value="Imam Syafi'i">Imam Syafi'i</option>
                                    <option value="Imam Ibnu Hajar Al-Haitami">Imam Ibnu Hajar Al-Haitami</option>
                                    <option value="Imam An-Nawawi">Imam An-Nawawi</option>
                                    <option value="Imam Ar-Rofi'i">Imam Ar-Rofi'i</option>
                                    <option value="Imam Haramain">Imam Haramain</option>
                                    <option value="Sayyidina Abu Bakar">Sayyidina Abu Bakar</option>
                                    <option value="Sayyidina Umar">Sayyidina Umar</option>
                                    <option value="Sayyidina Utsman">Sayyidina Utsman</option>
                                    <option value="Sayyidina Ali">Sayyidina Ali</option>
                                    <option value="Imam As-Suyuthi">Imam As-Suyuthi</option>
                                </select>
                                <small class="text-danger messages" id="errordomicile"></small>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="class">Kelas Diniyah</label>
                                <select class="custom-select form-control-border" id="class" name="class" tabindex="21">
                                    <option value="">.:Pilih:.</option>
                                    <option value="Pra Metode">Pra Metode</option>
                                    <option value="Jilid 1">Jilid 1</option>
                                    <option value="Jilid 2">Jilid 2</option>
                                    <option value="Jilid 3">Jilid 3</option>
                                    <option value="Jilid 4">Jilid 4</option>
                                    <option value="Pra Praktik">Pra Praktik</option>
                                    <option value="Praktik">Praktik</option>
                                    <option value="Takhossus">Takhossus</option>
                                </select>
                                <small class="text-danger messages" id="errorclass"></small>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="class_of_formal">Kelas Formal</label>
                                <select class="custom-select form-control-border" id="class_of_formal" name="class_of_formal" tabindex="22">
                                    <option value="">.:Pilih:.</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                </select>
                                <small class="text-danger messages" id="errorclass_of_formal"></small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="level_of_formal">Kelas Formal</label>
                                <select class="custom-select form-control-border" id="level_of_formal" name="level_of_formal" tabindex="23">
                                    <option value="">.:Pilih:.</option>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA">SMA</option>
                                    <option value="SMK">SMK</option>
                                </select>
                                <small class="text-danger messages" id="errorlevel_of_formal"></small>
                            </div>
                        </div>
                        <div class="col-4">
                            <input type="hidden" id="current_current" value="<?= $period ?>">
                            <div class="form-group">
                                <label for="date_of_entry">Tanggal Masuk (Masehi)</label>
                                <div class="row">
                                    <div class="col-3">
                                        <select class="custom-select form-control-border date-administration" id="date_of_entry" name="date_of_entry" tabindex="24">
                                            <option value="">00</option>
                                            <?php
                                            $l = 1;
                                            for ($i = 1; $i <= 31; $i++) {
                                            ?>
                                                <option value="<?= sprintf('%02d', $i); ?>">
                                                    <?= sprintf('%02d', $i); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-5">
                                        <select class="custom-select form-control-border date-administration" id="month_of_entry" name="month_of_entry" tabindex="25">
                                            <option value="">00</option>
                                            <?php
                                            $bulan = [
                                                1 =>
                                                'Januari',
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
                                            $k = 1;
                                            for ($p = 1; $p <= 12; $p++) {
                                            ?>
                                                <option value="<?= sprintf('%02d', $p); ?>">
                                                    <?= $bulan[$p]; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <select class="custom-select form-control-border date-administration" id="year_of_entry" name="year_of_entry" tabindex="26">
                                            <option value="">0000</option>
                                            <?php
                                            $now = date('Y');
                                            for ($b = 2015; $b <= $now; $b++) {
                                            ?>
                                                <option value="<?= $b; ?>">
                                                    <?= $b; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <small class="text-danger messages" id="errordate_of_entry"></small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="date_of_entry_hijriah">Tanggal Masuk (Hijriah)</label>
                                <div class="row">
                                    <div class="col-3">
                                        <select class="custom-select form-control-border date-administration" id="date_of_entry_hijriah" name="date_of_entry_hijriah" tabindex="27">
                                            <option value="">00</option>
                                            <?php
                                            $l = 1;
                                            for ($i = 1; $i <= 30; $i++) {
                                            ?>
                                                <option value="<?= sprintf('%02d', $i); ?>">
                                                    <?= sprintf('%02d', $i); ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-5">
                                        <select class="custom-select form-control-border date-administration" id="month_of_entry_hijriah" name="month_of_entry_hijriah" tabindex="28">
                                            <option value="">00</option>
                                            <?php
                                            $bulan = [
                                                1 =>
                                                'Muharram',
                                                'Shafar',
                                                'Rabi\'ul Awal',
                                                'Rabi\'ul Tsani',
                                                'Jumadal Ula',
                                                'Jumadal Akhirah',
                                                'Rajab',
                                                'Sya\'ban',
                                                'Ramadhan',
                                                'Syawal',
                                                'Dzul Qo\'dah',
                                                'Dzul Hijjah'
                                            ];
                                            $k = 1;
                                            for ($p = 1; $p <= 12; $p++) {
                                            ?>
                                                <option value="<?= sprintf('%02d', $p); ?>">
                                                    <?= $bulan[$p]; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <select class="custom-select form-control-border date-administration" id="year_of_entry_hijriah" name="year_of_entry_hijriah" tabindex="29">
                                            <option value="">0000</option>
                                            <?php
                                            $now = 1444;
                                            for ($b = 1437; $b <= $now; $b++) {
                                            ?>
                                                <option value="<?= $b; ?>">
                                                    <?= $b; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <small class="text-danger messages" id="errordate_of_entry_hijriah"></small>
                            </div>
                        </div>
                        <div class="col-4" id="alert-administration">
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>Perhatian!</strong> Jika Anda tidak memilih tahun periode, maka sistem berasumsi periode saat ini berikut tanggalnya.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between py-2">
                <button type="button" class="btn btn-danger btn-sm" id="cancel">Tutup</button>
                <button type="button" class="btn btn-primary btn-sm" id="save">Simpan</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-detail" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">Detail Data</h6>
                <div class="align-middle" data-dismiss="modal" title="Tutup" style="cursor: pointer">
                    <i class="fas fa-times-circle text-danger"></i>
                </div>
            </div>
            <div class="modal-body" id="showDetail" style="background-color: #f4f6f9">

            </div>
            <div class="modal-footer justify-content-end p-2"></div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<?php $this->load->view('partials/footer'); ?>
<?php $this->load->view('studentclass/js-studentclass'); ?>
