<?php
$JenisAkun = $this->session->userdata('JenisAkun');
$NamaLengkap = $this->session->userdata('NamaLengkap');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" href="<?php echo base_url('assets/img/logo1.png'); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
    <style>
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            padding-left: 1.5rem;
        }

        .nav-link.active {
            background: rgba(59, 130, 246, 0.2);
            border-left: 3px solid #3b82f6;
        }

        .profile-avatar {
            width: 40px;
            height: 40px;
            background: #3b82f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .menu-header {
            font-size: 11px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 16px 0 8px 0;
            padding: 0 12px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }

        #mapid {
            height: 400px;
            width: 100%;
            border-radius: 0.5rem;
        }
    </style>
</head>

<body class="bg-gray-50">

    <?php if (isset($wajibGantiPw) && $wajibGantiPw == 1): ?>
        <div id="first-time-modal" class="fixed inset-0 bg-gray-900 bg-opacity-80 z-[100] flex items-center justify-center p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl p-6 md:p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Pembaruan Akun Diperlukan</h2>
                <p class="text-gray-600 mb-6">Untuk keamanan, Anda wajib mengubah password dan mengatur lokasi Anda pada saat login pertama kali.</p>
                <form action="<?php echo base_url('dashboard/first_time_setup'); ?>" method="post" id="first-time-form">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                                <input type="password" name="password" id="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Minimal 6 karakter">
                            </div>
                            <div>
                                <label for="confirm_password" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                                <input type="password" name="confirm_password" id="confirm_password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Ulangi password baru">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tentukan Lokasi Anda</label>
                            <p class="text-xs text-gray-500 mb-2">Klik dan geser pin atau gunakan lokasi Anda saat ini.</p>

                            <button type="button" id="use-current-location-btn" class="mb-2 px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded-full hover:bg-blue-200 transition-colors">
                                <i class="fas fa-location-arrow mr-1"></i> Gunakan Lokasi Saya
                            </button>

                            <div id="setupMap" class="w-full h-56 rounded-md border border-gray-300"></div>

                            <input type="hidden" name="latitude" id="latitude" value="<?php echo htmlspecialchars($latitude_daftar); ?>">
                            <input type="hidden" name="longitude" id="longitude" value="<?php echo htmlspecialchars($longitude_daftar); ?>">
                        </div>
                    </div>
                    <div class="mt-8 text-right">
                        <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Simpan dan Lanjutkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <div class="min-h-screen flex">
        <div id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-gradient-to-br from-slate-950 to-indigo-950 text-white transform -translate-x-full md:translate-x-0 transition-transform duration-300 z-50 flex flex-col">
            <a href="<?php echo base_url('dashboard'); ?>" class="flex items-center gap-3 p-4 hover:bg-slate-800/50">
                <img src="<?php echo base_url('assets/img/logo1.png'); ?>" alt="Logo Sapa Baru" class="h-12 w-12">
                <div>
                    <h1 class="text-xl font-bold text-white">Sapa Baru</h1>
                    <p class="text-xs text-blue-200/80">Sistem Pendataan Pendatang</p>
                </div>
            </a>
            <div class="flex-1 overflow-y-auto px-4 pb-4">
                <nav class="space-y-1">
                    <div class="menu-header">Menu Utama</div>
                    <a href="<?php echo base_url('dashboard'); ?>" class="nav-link flex items-center px-4 py-3 rounded-xl font-medium <?php echo $this->uri->segment(1) == 'dashboard' || $this->uri->segment(1) == '' ? 'active' : ''; ?>">
                        <i class="fas fa-home nav-icon mr-3"></i><span>Beranda</span>
                    </a>
                    <?php if ($JenisAkun == "Admin" || $JenisAkun == "Kepala Lingkungan"): ?>
                        <?php if ($JenisAkun == "Admin"): ?>
                            <a href="<?php echo base_url('verifikasi'); ?>" class="nav-link flex items-center p-3 rounded-lg <?php echo $this->uri->segment(1) == 'verifikasi' ? 'active' : ''; ?>"><i class="fa-regular fa-address-card w-6 mr-3"></i><span>Verikasi Akun</span></a>
                        <?php endif; ?>
                        <div class="relative dropdown">
                            <button class="nav-link flex items-center p-3 rounded-lg w-full dropdown-toggle"><i class="fas fa-database w-6 mr-3"></i><span>Master Data</span><i class="fas fa-chevron-down ml-auto"></i></button>
                            <div class="pl-11 space-y-2 mt-1 dropdown-menu hidden">
                                <?php if ($JenisAkun == "Admin"): ?><a href="<?php echo base_url('kaling'); ?>" class="nav-link flex items-center p-3 rounded-lg <?php echo $this->uri->segment(1) == 'kaling' ? 'active' : ''; ?>"><i class="fas fa-user-tie w-6 mr-3"></i><span>Data Kaling</span></a><?php endif; ?>
                                <a href="<?php echo base_url('penanggungJawab'); ?>" class="nav-link flex items-center p-3 rounded-lg <?php echo $this->uri->segment(1) == 'penanggungJawab' ? 'active' : ''; ?>"><i class="fas fa-user-shield w-6 mr-3"></i><span>Data PJ</span></a>
                                <a href="<?php echo base_url('pendatang'); ?>" class="nav-link flex items-center p-3 rounded-lg <?php echo $this->uri->segment(1) == 'pendatang' ? 'active' : ''; ?>"><i class="fa-solid fa-user-group w-6 mr-3"></i><span>Data Pendatang</span></a>
                            </div>
                        </div>
                    <?php elseif ($JenisAkun == "Penanggung Jawab"): ?>
                        <a href="<?php echo base_url('pendatang'); ?>" class="nav-link flex items-center p-3 rounded-lg w-full <?php echo $this->uri->segment(1) == 'pendatang' ? 'active' : ''; ?>"><i class="fa-solid fa-user-group w-6 mr-3"></i><span>Data Pendatang</span></a>
                    <?php endif; ?>
                    <div class="menu-header">Laporan & Dokumen</div>
                    <?php if ($JenisAkun == "Kepala Lingkungan" || $JenisAkun == "Admin"): ?>
                        <a href="<?php echo base_url('laporan'); ?>" class="nav-link flex items-center px-4 py-3 rounded-xl font-medium <?php echo $this->uri->segment(1) == 'laporan' ? 'active' : ''; ?>"><i class="fas fa-chart-bar nav-icon mr-3"></i><span>Laporan</span></a>
                    <?php endif; ?>
                    <a href="<?php echo base_url('surat'); ?>" class="nav-link flex items-center px-4 py-3 rounded-xl font-medium <?php echo $this->uri->segment(1) == 'surat' ? 'active' : ''; ?>"><i class="fa-solid fa-envelopes-bulk nav-icon mr-3"></i><span>Surat Pengantar</span></a>
                </nav>
            </div>
        </div>

        <main class="flex-1 md:ml-64">
            <div class="bg-white shadow-lg flex items-center justify-between p-4 sticky top-0 z-40 border-b">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggle" class="md:hidden p-2 rounded-lg hover:bg-gray-100"><i class="fas fa-bars text-gray-700"></i></button>
                    <nav class="hidden lg:flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center"><a href="<?php echo base_url('dashboard'); ?>" class="text-gray-600 hover:text-blue-600"><i class="fas fa-home mr-2"></i>Beranda</a></li>
                            <?php if ($this->uri->segment(1) && $this->uri->segment(1) != 'dashboard'): ?>
                                <li>
                                    <div class="flex items-center"><i class="fas fa-chevron-right text-gray-400 mx-2"></i><span class="font-medium text-gray-500"><?php echo ucfirst($this->uri->segment(1)); ?></span></div>
                                </li>
                            <?php endif; ?>
                        </ol>
                    </nav>
                </div>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">

                        <div class="profile-avatar text-sm">
                            <?php if (isset($hasProfilePhoto) && $hasProfilePhoto === true): ?>

                                <img src="<?php echo $FotoProfil; ?>"
                                    alt="Foto Profil"
                                    class="w-full h-full object-cover rounded-full">

                            <?php else: ?>

                                <?php
                                if (!empty($NamaLengkap)) {
                                    $words = explode(' ', $NamaLengkap);
                                    $initials = '';
                                    foreach ($words as $word) {
                                        $initials .= substr($word, 0, 1);
                                    }
                                    echo strtoupper(substr($initials, 0, 2));
                                } else {
                                    echo 'U'; // Default jika nama kosong
                                }
                                ?>

                            <?php endif; ?>
                        </div>

                        <span class="hidden md:inline-block font-medium text-gray-700"><?php echo $NamaLengkap; ?></span>
                        <i class="fas fa-chevron-down text-xs text-gray-500" :class="{'transform rotate-180': open}"></i>

                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border">
                        <a href="<?php echo base_url('profile'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"><i class="fas fa-user-circle mr-2"></i>Profil Anda</a>
                        <div class="border-t"></div>
                        <a onclick="confirmLogout('<?php echo base_url('dashboard/logout'); ?>')" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 cursor-pointer"><i class="fas fa-sign-out-alt mr-2"></i>Keluar</a>
                    </div>
                </div>
            </div>

            <div class="p-4 md:p-6">
                <?php if ($this->uri->segment(1) == 'dashboard' || empty($this->uri->segment(1))): ?>
                    <div class="mb-6">
                        <h2 class="text-3xl font-normal text-gray-800 mb-6">Selamat datang, <span class="font-bold"><?php echo $NamaLengkap; ?></span></h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <div class="stat-card fade-in bg-white p-6 rounded-xl shadow-md border-l-4 border-blue-500 flex justify-between items-center">
                            <div>
                                <p class="text-gray-500">Total Pendatang</p>
                                <h3 class="text-3xl font-bold text-gray-800"><?php echo $total_pendatang ?? 0; ?></h3>
                            </div>
                            <div class="bg-blue-100 rounded-full w-12 h-12 flex items-center justify-center"><i class="fas fa-users text-blue-500 text-xl"></i></div>
                        </div>
                        <?php if ($JenisAkun == "Admin" || $JenisAkun == "Kepala Lingkungan"): ?>


                            <div class="stat-card fade-in bg-white p-6 rounded-xl shadow-md border-l-4 border-purple-500 flex justify-between items-center" style="animation-delay: 200ms;">
                                <div>
                                    <p class="text-gray-500">Total PJ</p>
                                    <h3 class="text-3xl font-bold text-gray-800"><?php echo $total_pj ?? 0; ?></h3>
                                </div>
                                <div class="bg-purple-100 rounded-full w-12 h-12 flex items-center justify-center"><i class="fas fa-user-shield text-purple-500 text-xl"></i></div>
                            </div>
                          <a href="<?= base_url('surat') ?>" class="block stat-card fade-in bg-white p-6 rounded-xl shadow-md border-l-4 border-teal-500" style="animation-delay: 400ms;">
    <div class="flex flex-col justify-between h-full">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-500">
                    <?php
                    if ($JenisAkun == 'Kepala Lingkungan') {
                        echo 'Surat Perlu Diverifikasi';
                    } else {
                        echo 'Total Surat Dibuat';
                    }
                    ?>
                </p>
                <h3 class="text-3xl font-bold text-gray-800"><?php echo $total_surat ?? 0; ?></h3>
            </div>
            <div class="bg-teal-100 rounded-full w-12 h-12 flex items-center justify-center">
                <i class="fas fa-envelope-open-text text-teal-500 text-xl"></i>
            </div>
        </div>
        <?php if (!empty($total_surat) && $total_surat > 0): ?>
        <div class="mt-2">
            <span class="inline-block bg-teal-100 text-teal-800 text-xs font-semibold px-3 py-1 rounded-full">
                <i class="fas fa-arrow-right mr-1"></i>Lihat Detail
            </span>
        </div>
        <?php endif; ?>
    </div>
</a>

       <a href="<?= base_url('pendatang') ?>" class="block stat-card fade-in bg-white p-6 rounded-xl shadow-md border-l-4 border-amber-500" style="animation-delay: 500ms;">
    <div class="flex flex-col justify-between h-full">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-500">Pendatang Pending</p>
                <h3 class="text-3xl font-bold text-gray-800"><?php echo $total_pending ?? 0; ?></h3>
            </div>
            <div class="bg-amber-100 rounded-full w-12 h-12 flex items-center justify-center">
                <i class="fas fa-user-clock text-amber-500 text-xl"></i>
            </div>
        </div>
        <?php if (!empty($total_pending) && $total_pending > 0): ?>
        <div class="mt-2">
            <span class="inline-block bg-amber-100 text-amber-800 text-xs font-semibold px-3 py-1 rounded-full">
                <i class="fas fa-arrow-right mr-1"></i>Lakukan Verifikasi
            </span>
        </div>
        <?php endif; ?>
    </div>
</a>
                        <?php endif; ?>
                        <?php if ($JenisAkun == "Admin"): ?>
                            <div class="stat-card fade-in bg-white p-6 rounded-xl shadow-md border-l-4 border-green-500 flex justify-between items-center" style="animation-delay: 100ms;">
                                <div>
                                    <p class="text-gray-500">Total Kaling</p>
                                    <h3 class="text-3xl font-bold text-gray-800"><?php echo $total_kaling ?? 0; ?></h3>
                                </div>
                                <div class="bg-green-100 rounded-full w-12 h-12 flex items-center justify-center"><i class="fas fa-user-tie text-green-500 text-xl"></i></div>
                            </div>
                            <div class="stat-card fade-in bg-white p-6 rounded-xl shadow-md border-l-4 border-orange-500" style="animation-delay: 300ms;">
                                <div>
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-gray-500">Pendaftar Baru</p>
                                            <h3 class="text-3xl font-bold text-gray-800"><?php echo $total_pendaftar_baru ?? 0; ?></h3>
                                        </div>
                                        <div class="bg-orange-100 rounded-full w-12 h-12 flex items-center justify-center"><i class="fas fa-user-plus text-orange-500 text-xl"></i></div>
                                    </div><?php if (!empty($total_pendaftar_baru)): ?><div class="mt-2"><a href="<?php echo base_url('verifikasi'); ?>" class="inline-block bg-orange-100 text-orange-800 text-xs font-semibold px-3 py-1 rounded-full hover:bg-orange-200"><i class="fas fa-arrow-right mr-1"></i>Lakukan Verifikasi</a></div><?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                    <div class="grid lg:grid-cols-5 gap-6">
                        <div class="lg:col-span-3 bg-white p-6 rounded-xl shadow-md">
                            <h3 class="font-bold text-lg text-gray-800 mb-4">Peta Wilayah</h3>
                            <div id="mapid" class="shadow-md"></div>
                        </div>
                        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-md">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-bold text-lg text-gray-800">Statistik Pendatang</h3>
                                <div class="flex space-x-1"><button id="btn-bulan" class="px-3 py-1 text-xs font-semibold bg-blue-500 text-white rounded-md">Bulanan</button><button id="btn-tahun" class="px-3 py-1 text-xs font-semibold bg-gray-200 text-gray-700 rounded-md">Tahunan</button></div>
                            </div>
                            <div class="h-96"><canvas id="statisticsChart"></canvas></div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (!empty($konten)) echo $konten;
                if (!empty($table)) echo $table; ?>
            </div>
        </main>
    </div>

    <div id="sidebar-overlay" class="fixed inset-0 bg-black opacity-50 z-40 hidden md:hidden"></div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="<?php echo base_url('assets/js/sweetalert2.min.js'); ?>"></script>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/sweetalert2.min.css'); ?>">

    <?php if (!empty($success)) : ?><script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '<?= addslashes($success) ?>',
                timer: 2000,
                showConfirmButton: false
            });
        </script><?php endif; ?>
    <?php if (!empty($error)) : ?><script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                html: '<?= addslashes($error) ?>',
                timer: 3000,
                showConfirmButton: true
            });
        </script><?php endif; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const sidebarToggle = document.getElementById('sidebarToggle');
            if (sidebarToggle) {
                const sidebar = document.getElementById('sidebar'),
                    overlay = document.getElementById('sidebar-overlay');
                sidebarToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                });
                overlay.addEventListener('click', () => {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                });
            }
            document.querySelectorAll('.dropdown-toggle').forEach(t => t.addEventListener('click', e => {
                e.preventDefault();
                t.nextElementSibling.classList.toggle('hidden');
                t.querySelector('.fa-chevron-down').classList.toggle('rotate-180');
            }));


            // --- LOGIKA KONDISIONAL UNTUK PETA & GRAFIK ---
            if (document.getElementById('first-time-modal')) {

                const latInput = document.getElementById('latitude');
                const lonInput = document.getElementById('longitude');
                const useCurrentLocationBtn = document.getElementById('use-current-location-btn');

                const setupMap = L.map('setupMap').setView([latInput.value, lonInput.value], 15);
                setTimeout(() => setupMap.invalidateSize(), 10);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(setupMap);

                const marker = L.marker([latInput.value, lonInput.value], {
                    draggable: true
                }).addTo(setupMap);
                marker.on('dragend', e => {
                    latInput.value = e.target.getLatLng().lat;
                    lonInput.value = e.target.getLatLng().lng;
                });

                useCurrentLocationBtn.addEventListener('click', function() {
                    if ('geolocation' in navigator) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            setupMap.flyTo([lat, lng], 16);
                            marker.setLatLng([lat, lng]);
                            latInput.value = lat;
                            lonInput.value = lng;
                            Swal.fire({
                                icon: 'success',
                                title: 'Lokasi Ditemukan!',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }, function(error) {
                            let message = 'Gagal mendapatkan lokasi. ';
                            switch (error.code) {
                                case error.PERMISSION_DENIED:
                                    message += "Anda menolak permintaan untuk akses lokasi.";
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    message += "Informasi lokasi tidak tersedia.";
                                    break;
                                case error.TIMEOUT:
                                    message += "Permintaan lokasi timeout.";
                                    break;
                                default:
                                    message += "Terjadi kesalahan yang tidak diketahui.";
                                    break;
                            }
                            Swal.fire('Error', message, 'error');
                        });
                    } else {
                        Swal.fire('Tidak Didukung', 'Browser Anda tidak mendukung Geolocation.', 'error');
                    }
                });

                document.getElementById('first-time-form').addEventListener('submit', e => {
                    if (document.getElementById('password').value !== document.getElementById('confirm_password').value) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Password Tidak Cocok',
                            text: 'Pastikan password baru dan konfirmasinya sama.'
                        });
                    }
                });

            } else {


                if (document.getElementById('statisticsChart')) {
                    let chart = new Chart(document.getElementById('statisticsChart').getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: <?php echo $chart_labels ?? '[]'; ?>,
                            datasets: [{
                                label: 'Jumlah Pendatang',
                                data: <?php echo $chart_data ?? '[]'; ?>,
                                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                                borderColor: 'rgba(59, 130, 246, 1)',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                    const updateChart = type => $.getJSON("<?php echo base_url('dashboard/get_chart_data'); ?>", {
                        type: type
                    }, r => {
                        chart.data.labels = r.labels;
                        chart.data.datasets[0].data = r.data;
                        chart.update();
                    });
                    $('#btn-bulan').on('click', function() {
                        $(this).addClass('bg-blue-500 text-white').removeClass('bg-gray-200 text-gray-700');
                        $('#btn-tahun').addClass('bg-gray-200 text-gray-700').removeClass('bg-blue-500 text-white');
                        updateChart('bulan');
                    });
                    $('#btn-tahun').on('click', function() {
                        $(this).addClass('bg-blue-500 text-white').removeClass('bg-gray-200 text-gray-700');
                        $('#btn-bulan').addClass('bg-gray-200 text-gray-700').removeClass('bg-blue-500 text-white');
                        updateChart('tahun');
                    });
                }

                if (document.getElementById('mapid')) {
                    const map = L.map('mapid').setView([-8.7900, 115.1746], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap'
                    }).addTo(map);
                }
            }
        });

        function confirmLogout(url) {
            Swal.fire({
                title: 'Keluar?',
                text: "Apakah Anda yakin ingin keluar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Keluar!',
                cancelButtonText: 'Batal'
            }).then(r => {
                if (r.isConfirmed) window.location.href = url;
            });
        }
    </script>
</body>

</html>