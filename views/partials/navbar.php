<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a class="nav-link"><b>Tahun Periode : <?= periodDisplay() ?></b></a>

        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a class="nav-link"><b><?= dateHijriFormat(getHijri()) ?> H</b></a>
        </li>
    </ul>
    <?php
    $avatarPath = FCPATH . 'assets/images/users/' . $this->session->userdata('user_id') . '.png';

    if (file_exists($avatarPath) === FALSE || $avatarPath == NULL) {
        $avatar = base_url('assets/images/users/default.png');
    } else {
        $avatar = base_url('assets/images/users/' . $this->session->userdata('user_id') . '.png');
    }
    ?>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link pt-0" data-toggle="dropdown" href="#">
				<span><?= $this->session->userdata('role') ?></span>
				<span style="font-size: 8px" class="mx-2 text-success"><i class="fas fa-circle fa-xs"></i></span>
                <b class="text-dark"><?= $this->session->userdata('name') ?></b>
                <img id="avatar-navbar" style="width: 30px;" src="<?= $avatar ?>" class="brand-image img-circle elevation-2 ml-2" style="opacity: .8">
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <div class="dropdown-divider"></div>
				<?php
				$roles = roles();
				if ($roles) {
					foreach ($roles as $role) {
						?>
						<span class="dropdown-item" style="cursor: pointer" onclick="switchRole('<?= $role->id ?>')">
							<i class="fas fa-angle-right"></i> Switch as <b><?= $role->name ?></b>
						</span>
						<?php
					}
				}
				?>
				<div class="dropdown-divider"></div>
                <a href="<?= base_url() ?>profile" class="dropdown-item">
                    <i class="fas fa-user-circle mr-2"></i> Lihat Akun
                </a>
                <div class="dropdown-divider"></div>
                <a href="<?= base_url() ?>auth/logout" class="dropdown-item tombollogout">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
                <div class="dropdown-divider"></div>
            </div>
        </li>
    </ul>
</nav>
