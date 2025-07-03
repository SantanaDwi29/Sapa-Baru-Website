<?php
$JenisAkun = $this->session->userdata('JenisAkun');
$NamaLengkap = $this->session->userdata('NamaLengkap');

// Placeholder data for environmental locations
// In a real application, this data would come from your database
$lokasi_lingkungan = [
    ['name' => 'Kantor Lingkungan 1', 'lat' => -8.7615, 'lng' => 115.1700, 'description' => 'Pusat administrasi lingkungan 1.'],
    ['name' => 'Pos Keamanan Utama', 'lat' => -8.7580, 'lng' => 115.1750, 'description' => 'Pos keamanan 24 jam.'],
    ['name' => 'Taman Kota Hijau', 'lat' => -8.7650, 'lng' => 115.1680, 'description' => 'Area hijau untuk rekreasi warga.'],
    ['name' => 'Balai Warga', 'lat' => -8.7600, 'lng' => 115.1725, 'description' => 'Tempat pertemuan dan kegiatan komunitas.'],
];
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
    <style>
        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .nav-link {
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            padding-left: 1.5rem;
        }

        .nav-link.active {
            background: rgba(59, 130, 246, 0.2);
            border-left: 3px solid #3b82f6;
        }

        .dropdown-menu {
            transition: all 0.3s ease;
        }

        .profile-avatar {
            width: 40px;
            height: 40px;
            background: #3b82f6;
            /* Blue-500 */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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

        .profile-section {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            transition: all 0.3s ease;
        }

        .profile-section:hover {
            background: rgba(255, 255, 255, 0.12);
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
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

        /* Custom scrollbar */
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

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
            }

        /* Map specific styles */
        #mapid {
            height: 400px; /* Set a fixed height for the map container */
            width: 100%;
            border-radius: 0.5rem; /* rounded-lg */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); /* shadow-lg */
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <div id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-gradient-to-br from-slate-950 to-indigo-950 text-white transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-50 flex flex-col">
            
            <a href="<?php echo base_url('dashboard/admin'); ?>" class="flex items-center gap-3 p-4 hover:bg-slate-800/50 transition-colors duration-300">
                <img src="<?php echo base_url('assets/img/logo1.png'); ?>" alt="Logo Sapa Baru" class="h-12 w-12">
                <div>
                    <h1 class="text-xl font-bold text-white">Sapa Baru</h1>
                    <p class="text-xs text-blue-200/80">Sistem Pendataan Pendatang</p>
                </div>
            </a>

            <div class="flex-1 overflow-y-auto px-4 pb-4">
                <nav class="space-y-1">

                    <div class="menu-header">Menu Utama</div>

                    <a href="<?php echo base_url('dashboard/admin'); ?>"
                        class="nav-link flex items-center px-4 py-3 rounded-xl text-lg font-medium <?php echo $this->uri->segment(1) == 'dashboard' ? 'active' : ''; ?>">
                        <i class="fas fa-home nav-icon mr-3"></i>
                        <span>Beranda</span>
                    </a>
                    <?php
                    $master_segments = ['kaling', 'penanggungJawab', 'pendatang'];
                    $is_master_active = in_array($this->uri->segment(1), $master_segments);
                    ?>
                    <?php if ($JenisAkun == "Admin") { ?>
                        <a href="<?php echo base_url('verifikasi'); ?>"
                            class="nav-link text-lg flex items-center p-3 rounded-lg <?php echo $this->uri->segment(1) == 'verifikasi' ? 'active' : ''; ?>">
                            <i class="fa-regular fa-address-card w-6 mr-3"></i>
                            <span>Verikasi Akun</span>
                        </a>


                        <div class="relative dropdown" data-dropdown-id="master">
                            <button class="nav-link flex items-center p-3 rounded-lg transition-all duration-300 w-full dropdown-toggle <?php echo $is_master_active ? 'active' : ''; ?>">
                                <i class="fas fa-database w-6 mr-3"></i>
                                <span>Master Data</span>
                                <i class="fas fa-chevron-down ml-auto transition-transform"></i>
                            </button>
                            <div class="pl-11 space-y-2 mt-1 dropdown-menu hidden">
                                <a href="<?php echo base_url('kaling'); ?>"
                                    class="nav-link text-lg flex items-center p-3 rounded-lg <?php echo $this->uri->segment(1) == 'kaling' ? 'active' : ''; ?>">
                                    <i class="fas fa-user-tie w-6 mr-3"></i>
                                    <span>Data Kaling</span>
                                </a>
                                <a href="<?php echo base_url('penanggungJawab'); ?>"
                                    class="nav-link text-lg flex items-center p-3 rounded-lg <?php echo $this->uri->segment(1) == 'penanggungJawab' ? 'active' : ''; ?>">
                                    <i class="fas fa-user-shield w-6 mr-3"></i>
                                    <span>Data PJ</span>
                                </a>
                                <a href="<?php echo base_url('pendatang'); ?>"
                                    class="nav-link  text-lg flex items-center p-3 rounded-lg <?php echo $this->uri->segment(1) == 'pendatang' ? 'active' : ''; ?>">
                                    <i class="fa-solid fa-user-group w-6 mr-3"></i>
                                    <span>Data Pendatang</span>
                                </a>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ($JenisAkun == "Kepala Lingkungan") { ?>
                        <div class="relative dropdown" data-dropdown-id="master">
                            <button class="nav-link flex items-center p-3 rounded-lg transition-all duration-300 w-full dropdown-toggle <?php echo $is_master_active ? 'active' : ''; ?>">
                                <i class="fas fa-database w-6 mr-3"></i>
                                <span>Master Data</span>
                                <i class="fas fa-chevron-down ml-auto transition-transform"></i>
                            </button>
                            <div class="pl-11 space-y-2 mt-1 dropdown-menu hidden">
                                <a href="<?php echo base_url('penanggungJawab'); ?>"
                                    class="nav-link text-lg flex items-center p-3 rounded-lg <?php echo $this->uri->segment(1) == 'penanggungJawab' ? 'active' : ''; ?>">
                                    <i class="fas fa-user-shield w-6 mr-3"></i>
                                    <span>Data PJ</span>
                                </a>
                                <a href="<?php echo base_url('pendatang'); ?>"
                                    class="nav-link  text-lg flex items-center p-3 rounded-lg <?php echo $this->uri->segment(1) == 'pendatang' ? 'active' : ''; ?>">
                                    <i class="fa-solid fa-user-group w-6 mr-3"></i>
                                    <span>Data Pendatang</span>
                                </a>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ($JenisAkun == "Penanggung Jawab") { ?>
                        <a href="<?php echo base_url('pendatang'); ?>" class="nav-link flex items-center p-3 rounded-lg transition-all duration-300 w-full <?php echo $this->uri->segment(1) == 'pendatang' ? 'active' : ''; ?>">
                            <i class="fa-solid fa-user-group w-6 mr-3"></i>
                            <span>Data Pendatang</span>
                        </a>
                    <?php } ?>
                    <div class="menu-header">Laporan & Dokumen</div>

                    <?php if ($JenisAkun == "Kepala Lingkungan" || $JenisAkun == "Admin") { ?>

                        <a href="<?php echo base_url('laporan'); ?>"
                            class="nav-link flex items-center px-4 py-3 rounded-xl text-lg font-medium <?php echo $this->uri->segment(1) == 'laporan' ? 'active' : ''; ?>">
                            <i class="fas fa-chart-bar nav-icon mr-3"></i>
                            <span>Laporan</span>
                        </a>
                    <?php } ?>

                    <a href="<?php echo base_url('surat'); ?>"
                        class="nav-link flex items-center px-4 py-3 rounded-xl text-lg font-medium <?php echo $this->uri->segment(1) == 'surat' ? 'active' : ''; ?>">
                        <i class="fa-solid fa-envelopes-bulk nav-icon mr-3"></i>
                        <span>Surat Pengantar</span>
                    </a>

                </nav>
            </div>



        </div>

        <main class="flex-1 md:ml-64">
            <div class="bg-white shadow-lg flex items-center justify-between p-4 sticky top-0 z-40 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggle" class="md:hidden p-2 rounded-lg hover:bg-gray-100 focus:outline-none transition-colors">
                        <i class="fas fa-bars text-gray-700"></i>
                    </button>

                    <nav class="hidden lg:flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="<?php echo base_url('dashboard/admin'); ?>" class="text-gray-600 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-home mr-2"></i>Beranda
                                </a>
                            </li>
                            <?php if ($this->uri->segment(1) != 'dashboard'): ?>
                                <li>
                                    <div class="flex items-center">
                                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                        <span class="text-gray-500 font-medium"><?php echo ucfirst($this->uri->segment(1)); ?></span>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ol>
                    </nav>
                </div>

                <div class="flex items-center gap-3">
                    <button class="relative p-2 text-gray-600 hover:text-blue-600 focus:outline-none transition-colors">
                        <i class="fas fa-bell text-lg"></i>
                        <span class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full animate-pulse">3</span>
                    </button>

                    <!-- Profile Dropdown - Fixed Version -->
                    <div class="relative ml-2" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                            <div class="profile-avatar">
                                <?php if (isset($hasProfilePhoto) && $hasProfilePhoto): ?>
                                    <img src="<?php echo isset($FotoProfil) ? $FotoProfil : base_url('assets/img/default-profile.jpg'); ?>"
                                        alt="Profile Photo"
                                        class="w-full h-full object-cover rounded-full"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div style="display: none;" class="w-full h-full flex items-center justify-center">
                                        <?php
                                        if (isset($NamaLengkap)) {
                                            $words = explode(' ', $NamaLengkap);
                                            $initials = '';
                                            foreach ($words as $word) {
                                                $initials .= substr($word, 0, 1);
                                            }
                                            echo substr($initials, 0, 2);
                                        } else {
                                            echo 'U';
                                        }
                                        ?>
                                    </div>
                                <?php else: ?>
                                    <?php
                                    $namaLengkap = isset($NamaLengkap) ? $NamaLengkap : $this->session->userdata('NamaLengkap');
                                    if ($namaLengkap) {
                                        $words = explode(' ', $namaLengkap);
                                        $initials = '';
                                        foreach ($words as $word) {
                                            $initials .= substr($word, 0, 1);
                                        }
                                        echo substr($initials, 0, 2);
                                    } else {
                                        echo 'U';
                                    }
                                    ?>
                                <?php endif; ?>
                            </div>
                            <span class="hidden md:inline-block font-medium text-gray-700">
                                <?php echo isset($NamaLengkap) ? $NamaLengkap : $this->session->userdata('NamaLengkap'); ?>
                            </span>
                            <i class="fas fa-chevron-down text-xs text-gray-500 transition-transform duration-200"
                                :class="{'transform rotate-180': open}"></i>
                        </button>

                        <div x-show="open"
                            @click.away="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                            <a href="<?php echo base_url('profile'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user-circle mr-2"></i> Your Profile
                            </a>
                            <div class="border-t border-gray-200"></div>
                            <a onclick="confirmLogout('<?php echo base_url('profile/logout'); ?>')" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 cursor-pointer">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 md:p-6">
                <?php if ($this->uri->segment(1) == 'dashboard' || empty($this->uri->segment(1))): ?>
                    <div class="mb-6">
                        <h2 class="text-3xl font-normal text-gray-800 mb-6">Selamat datang di Sistem Pendataan Penduduk Pendatang, <span class="font-bold"><?php echo $NamaLengkap; ?></span></h2>
                        <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                            <div class="stat-card bg-white p-6 rounded-lg shadow-lg border-l-4 border-blue-500">
                                <div class="flex justify-between">
                                    <div>
                                        <p class="text-lg text-gray-500 mb-1">Total Pendatang</p>
                                        <h3 class="text-2xl font-bold text-gray-800"><?php echo $total_pendatang ?? 0; ?></h3>                                        
                                    </div>
                                    <div class="bg-blue-100 rounded-full w-12 h-12 flex items-center justify-center">
                                        <i class="fas fa-users text-blue-500 text-xl"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="stat-card bg-white p-6 rounded-lg shadow-lg border-l-4 border-green-500">
                                <div class="flex justify-between">
                                    <div>
                                        <p class="text-lg text-gray-500 mb-1">Total Kaling</p>
                                        <h3 class="text-2xl font-bold text-gray-800"><?php echo $total_kaling ?? 0; ?></h3>
                                        
                                    </div>
                                    <div class="bg-green-100 rounded-full w-12 h-12 flex items-center justify-center">
                                        <i class="fas fa-user-tie text-green-500 text-xl"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="stat-card bg-white p-6 rounded-lg shadow-lg border-l-4 border-purple-500">
                                <div class="flex justify-between">
                                    <div>
                                        <p class="text-lg text-gray-500 mb-1">Total PJ</p>
                                        <h3 class="text-2xl font-bold text-gray-800"><?php echo $total_pj ?? 0; ?></h3>
                                        
                                    </div>
                                    <div class="bg-purple-100 rounded-full w-12 h-12 flex items-center justify-center">
                                        <i class="fas fa-user-shield text-purple-500 text-xl"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="stat-card bg-white p-6 rounded-lg shadow-lg border-l-4 border-orange-500">
                                <div class="flex justify-between">
                                    <div>
                                        <p class="text-lg text-gray-500 mb-1">Pendaftar Baru</p>
                                        <h3 class="text-2xl font-bold text-gray-800"><?php echo $total_pendaftar_baru ?? 0; ?></h3>
                                        
                                    </div>
                                    <div class="bg-orange-100 rounded-full w-12 h-12 flex items-center justify-center">
                                        <i class="fa-solid fa-user-group text-orange-500 text-xl"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div class="bg-white p-6 rounded-lg shadow-lg lg:col-span-2">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="font-semibold text-gray-700">Statistik Pendatang</h3>
                                    <div class="flex space-x-2">
                                        <button id="btn-bulan" class="px-3 py-1 text-xs bg-blue-500 text-white rounded-md transition-colors duration-300">Bulanan</button>
                                        <button id="btn-tahun" class="px-3 py-1 text-xs bg-gray-200 text-gray-700 rounded-md transition-colors duration-300">Tahunan</button>
                                    </div>
                                </div>
                                <div class="h-64">
                                    <canvas id="statisticsChart"></canvas>
                                </div>
                            </div>

                            <div class="bg-white p-6 rounded-lg shadow-lg">
                                <h3 class="font-semibold text-gray-700 mb-4">Aktivitas Terbaru</h3>
                                <div class="space-y-4">
                                    <div class="flex items-start space-x-3">
                                        <div class="bg-blue-100 rounded-full p-2">
                                            <i class="fa-solid fa-user-group text-blue-500"></i>
                                        </div>
                                        <div>
                                            <p class="text-lg font-medium">Pendatang baru terdaftar</p>
                                            <p class="text-xs text-gray-500">Ahmad Rizky - 30 menit yang lalu</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-3">
                                        <div class="bg-green-100 rounded-full p-2">
                                            <i class="fas fa-file-alt text-green-500"></i>
                                        </div>
                                        <div>
                                            <p class="text-lg font-medium">Laporan bulanan dihasilkan</p>
                                            <p class="text-xs text-gray-500">Admin - 2 jam yang lalu</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-3">
                                        <div class="bg-orange-100 rounded-full p-2">
                                            <i class="fas fa-edit text-orange-500"></i>
                                        </div>
                                        <div>
                                            <p class="text-lg font-medium">Data PJ diperbarui</p>
                                            <p class="text-xs text-gray-500">Kaling Sukamaju - 5 jam yang lalu</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-3">
                                        <div class="bg-purple-100 rounded-full p-2">
                                            <i class="fas fa-print text-purple-500"></i>
                                        </div>
                                        <div>
                                            <p class="text-lg font-medium">Dokumen dicetak</p>
                                            <p class="text-xs text-gray-500">PJ Blok C - 1 hari yang lalu</p>
                                        </div>
                                    </div>
                                </div>
                                <a href="#" class="block text-center text-blue-500 hover:text-blue-700 text-lg mt-4">Lihat semua aktivitas</a>
                            </div>
                        </div>

                        <!-- New Map Section -->
                        <div class="mt-8 bg-white p-6 rounded-lg shadow-lg">
                            <h3 class="font-semibold text-gray-700 mb-4">Peta Lokasi Lingkungan</h3>
                            <div id="mapid"></div>
                        </div>
                        <!-- End New Map Section -->

                    </div>
                <?php endif; ?>

                <?php
                if (empty($konten)) {
                    echo "";
                } else {
                    echo $konten;
                }
                ?>

                <?php
                if (empty($table)) {
                    echo "";
                } else {
                    echo $table;
                }
                ?>
            </div>


        </main>
    </div>


    <div id="sidebar-overlay" class="fixed inset-0 bg-black opacity-50 z-40 hidden md:hidden"></div>

    <script src="<?php echo base_url(); ?>/jquery/app.js"></script>
    <script language="javascript">
        var site = "<?php echo base_url() ?>index.php/";
        var loading_image_large = "<?php echo base_url() ?>gambar/loading_large.gif";
    </script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="<?php echo base_url('assets/js/sweetalert2.min.js'); ?>"></script>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/sweetalert2.min.css'); ?>">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Leaflet JS should be loaded after Leaflet CSS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>


    <?php if (!empty($success)) : ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '<?= $success ?>',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    <?php endif; ?>

    <?php if (!empty($error)) : ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '<?= $error ?>',
                timer: 3000,
                showConfirmButton: true
            });
        </script>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // --- Sidebar & Dropdown Logic ---
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('-translate-x-full');
                    sidebarOverlay.classList.toggle('hidden');
                });
                sidebarOverlay.addEventListener('click', () => {
                    sidebar.classList.add('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                });
            }

            document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
                toggle.addEventListener('click', e => {
                    e.preventDefault();
                    const dropdownMenu = toggle.nextElementSibling;
                    const chevronIcon = toggle.querySelector('.fa-chevron-down');
                    dropdownMenu.classList.toggle('hidden');
                    chevronIcon.classList.toggle('rotate-180');
                });
            });

            // --- Chart Logic ---
            let statisticsChart;
            const canvasElement = document.getElementById('statisticsChart');

            function updateChart(type) {
                $.ajax({
                    url: "<?php echo base_url('dashboard/get_chart_data'); ?>",
                    type: 'GET',
                    data: { type: type },
                    dataType: 'json',
                    success: function(response) {
                        if (statisticsChart) {
                            statisticsChart.data.labels = response.labels;
                            statisticsChart.data.datasets[0].data = response.data;
                            statisticsChart.data.datasets[0].label = (type === 'tahun') ? 'Jumlah Pendatang per Tahun' : 'Jumlah Pendatang per Bulan';
                            statisticsChart.update();
                        }
                    },
                    error: function() {
                        Swal.fire({ icon: 'error', title: 'Oops...', text: 'Gagal memuat data grafik!' });
                    }
                });
            }

            if (canvasElement) {
                const ctx = canvasElement.getContext('2d');
                const initialLabels = <?php echo json_encode($chart_labels ?? []); ?>;
                const initialData = <?php echo json_encode($chart_data ?? []); ?>;

                statisticsChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: initialLabels,
                        datasets: [{
                            label: 'Jumlah Pendatang per Bulan',
                            data: initialData,
                            backgroundColor: 'rgba(59, 130, 246, 0.2)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 2,
                            pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                            pointBorderColor: '#fff',
                            pointHoverRadius: 6,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: context => `${context.dataset.label || ''}: ${context.parsed.y} orang`
                                }
                            }
                        }
                    }
                });
            }

            $('#btn-bulan').on('click', function() {
                $(this).removeClass('bg-gray-200 text-gray-700').addClass('bg-blue-500 text-white');
                $('#btn-tahun').removeClass('bg-blue-500 text-white').addClass('bg-gray-200 text-gray-700');
                updateChart('bulan');
            });

            $('#btn-tahun').on('click', function() {
                $(this).removeClass('bg-gray-200 text-gray-700').addClass('bg-blue-500 text-white');
                $('#btn-bulan').removeClass('bg-blue-500 text-white').addClass('bg-gray-200 text-gray-700');
                updateChart('tahun');
            });

            // --- Map Logic ---
            const mapElement = document.getElementById('mapid');
            if (mapElement) {
                // Initialize the map, centered around a general location for Bali (Kuta Selatan)
                // You might want to adjust this center and zoom level based on your specific "lingkungan" locations
                const map = L.map('mapid').setView([-8.7615, 115.1700], 13); // Centered near Kuta Selatan

                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                // Get the environmental locations data from PHP
                const lokasiLingkungan = <?php echo json_encode($lokasi_lingkungan); ?>;

                // Add markers for each location
                lokasiLingkungan.forEach(location => {
                    L.marker([location.lat, location.lng])
                        .addTo(map)
                        .bindPopup(`<b>${location.name}</b><br>${location.description}`);
                });

                // Optional: Adjust map view to fit all markers if you have many
                if (lokasiLingkungan.length > 0) {
                    const latLngs = lokasiLingkungan.map(loc => [loc.lat, loc.lng]);
                    const bounds = L.latLngBounds(latLngs);
                    map.fitBounds(bounds, { padding: [50, 50] }); // Add some padding
                }
            }
        });

        // Global function for logout confirmation
        function confirmLogout(logoutUrl) {
            Swal.fire({
                title: 'Keluar?',
                text: "Apakah Anda yakin ingin keluar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Keluar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = logoutUrl;
                }
            });
        }
    </script>
</body>

</html>