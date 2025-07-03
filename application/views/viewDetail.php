<div class="max-w-7xl mx-auto p-6 bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-lg mb-6 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class=" p-3 ">
                    <i class="fas fa-user-plus text-indigo-950 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 border-b-0">Detail Data Pendatang</h2>
                    <p class="text-gray-600 mt-1">Informasi lengkap pendatang baru</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Status Badge -->
                <span class="px-4 py-2 rounded-full text-sm font-semibold
                    <?php
                    switch ($Pendatang->StatusTinggal) {
                        case 'Aktif':
                            echo 'bg-green-100 text-green-800';
                            break;
                        case 'Pending':
                            echo 'bg-yellow-100 text-yellow-800';
                            break;
                        case 'Ditolak':
                            echo 'bg-red-100 text-red-800';
                            break;
                        default:
                            echo 'bg-gray-100 text-gray-800';
                    }
                    ?>
                "><?= htmlspecialchars($Pendatang->StatusTinggal) ?></span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-camera text-blue-600 mr-2"></i>
                    Foto Diri
                </h3>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center bg-gray-50">
                    <?php if (!empty($Pendatang->FotoDiri)): ?>
                        <img src="<?= base_url('uploads/fotodiri/' . $Pendatang->FotoDiri) ?>" alt="Foto Diri" class="max-h-64 mx-auto rounded-lg shadow-md" />
                    <?php else: ?>
                        <div class="text-gray-500 py-8">
                            <i class="fas fa-user-circle text-6xl text-gray-300 mb-2"></i>
                            <p>Tidak ada foto</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-id-card text-green-600 mr-2"></i>
                    Foto KTP
                </h3>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center bg-gray-50">
                    <?php if (!empty($Pendatang->FotoKTP)): ?>
                        <img src="<?= base_url('uploads/fotoktp/' . $Pendatang->FotoKTP) ?>" alt="Foto KTP" class="max-h-48 mx-auto rounded-lg shadow-md" />
                    <?php else: ?>
                        <div class="text-gray-500 py-8">
                            <i class="fas fa-id-card text-6xl text-gray-300 mb-2"></i>
                            <p>Tidak ada foto</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center border-b border-gray-200 pb-3">
                    <i class="fas fa-user text-blue-600 mr-3"></i>
                    Informasi Pribadi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">NIK</span>
                            <span id="view-nik" class="text-lg font-semibold text-gray-900 mt-1"><?= htmlspecialchars($Pendatang->NIK) ?></span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Nama Lengkap</span>
                            <span id="view-nama_lengkap" class="text-lg font-semibold text-gray-900 mt-1"><?= htmlspecialchars($Pendatang->NamaLengkap) ?></span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">No. Handphone</span>
                            <span id="view-telepon" class="text-lg text-gray-900 mt-1"><?= htmlspecialchars($Pendatang->Telepon) ?></span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Agama</span>
                            <span id="view-agama" class="text-lg text-gray-900 mt-1"><?= htmlspecialchars($Pendatang->Agama) ?></span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Tempat Lahir</span>
                            <span id="view-tempat_lahir" class="text-lg text-gray-900 mt-1"><?= htmlspecialchars($Pendatang->TempatLahir) ?></span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Tanggal Lahir</span>
                            <span id="view-tanggal_lahir" class="text-lg text-gray-900 mt-1"><?= htmlspecialchars($Pendatang->TanggalLahir) ?></span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Jenis Kelamin</span>
                            <span id="view-jenis_kelamin" class="text-lg text-gray-900 mt-1"><?= htmlspecialchars($Pendatang->JenisKelamin) ?></span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Golongan Darah</span>
                            <span id="view-golda" class="text-lg text-gray-900 mt-1"><?= htmlspecialchars($Pendatang->Golda) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alamat Asal -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center border-b border-gray-200 pb-3">
                    <i class="fas fa-map-marker-alt text-red-600 mr-3"></i>
                    Alamat Domisili
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-4">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Provinsi</span>
                            <span id="view-provinsi" class="text-lg text-gray-900 mt-1"><?= htmlspecialchars($Pendatang->Provinsi) ?></span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Kabupaten/Kota</span>
                            <span id="view-kabupaten" class="text-lg text-gray-900 mt-1"><?= htmlspecialchars($Pendatang->Kabupaten) ?></span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Kecamatan</span>
                            <span id="view-kecamatan" class="text-lg text-gray-900 mt-1"><?= htmlspecialchars($Pendatang->Kecamatan) ?></span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Kelurahan</span>
                            <span id="view-kelurahan" class="text-lg text-gray-900 mt-1"><?= htmlspecialchars($Pendatang->Kelurahan) ?></span>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">RT</span>
                            <span id="view-rt" class="text-lg text-gray-900 mt-1"><?= htmlspecialchars($Pendatang->RT) ?></span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">RW</span>
                            <span id="view-rw" class="text-lg text-gray-900 mt-1"><?= htmlspecialchars($Pendatang->RW) ?></span>
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Alamat Lengkap KTP</span>
                    <p id="view-alamat" class="text-lg text-gray-900 mt-2 p-4 bg-gray-100 rounded-lg whitespace-pre-line"><?= htmlspecialchars($Pendatang->Alamat) ?></p>
                </div>
            </div>

            <!-- Informasi Kedatangan -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center border-b border-gray-200 pb-3">
                    <i class="fas fa-calendar-check text-green-600 mr-3"></i>
                    Informasi Kedatangan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Tempat Tujuan</span>
                            <p id="view-tempat_tujuan" class="text-lg text-gray-900 mt-2 p-3 bg-gray-50 rounded-lg whitespace-pre-line"><?= htmlspecialchars($Pendatang->TempatTujuan) ?></p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Latitude</span>
                                <span id="view-latitude" class="text-lg text-gray-900 mt-1"><?= htmlspecialchars($Pendatang->Latitude) ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Longitude</span>
                                <span id="view-longitude" class="text-lg text-gray-900 mt-1"><?= htmlspecialchars($Pendatang->Longitude) ?></span>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Tujuan Kedatangan</span>
                            <p id="view-tujuan" class="text-lg text-gray-900 mt-2 p-3 bg-gray-50 rounded-lg whitespace-pre-line"><?= htmlspecialchars($Pendatang->Tujuan) ?></p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Tanggal Masuk</span>
                                <span id="view-tanggal_masuk" class="text-lg text-gray-900 mt-1"><?= htmlspecialchars($Pendatang->TanggalMasuk) ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Tanggal Keluar</span>
                                <span id="view-tanggal_keluar" class="text-lg text-gray-900 mt-1"><?= !empty($Pendatang->TanggalKeluar) ? htmlspecialchars($Pendatang->TanggalKeluar) : '-' ?></span>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Status Tinggal</span>
                            <div class="mt-2">
                                <span id="view-status_tinggal" class="px-4 py-2 rounded-full text-sm font-semibold
                                    <?php
                                    switch ($Pendatang->StatusTinggal) {
                                        case 'Aktif':
                                            echo 'bg-green-100 text-green-800';
                                            break;
                                        case 'Pending':
                                            echo 'bg-yellow-100 text-yellow-800';
                                            break;
                                        case 'Ditolak':
                                            echo 'bg-red-100 text-red-800';
                                            break;
                                        default:
                                            echo 'bg-gray-100 text-gray-800';
                                    }
                                    ?>
                                "><?= htmlspecialchars($Pendatang->StatusTinggal) ?></span>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Kepala Lingkungan</span>
                            <span id="view-id_kaling" class="text-lg text-gray-900 mt-1">
                                <?php
                                $namaKalingFound = '-';
                                if (isset($NamaKaling) && is_array($NamaKaling)) {
                                    foreach ($NamaKaling as $k) {
                                        if ($k->idKaling == $Pendatang->idKaling) {
                                            $namaKalingFound = htmlspecialchars($k->NamaKaling);
                                            break;
                                        }
                                    }
                                }
                                echo $namaKalingFound;
                                ?>
                            </span>
                        </div>
                        <?php
                        if ($Pendatang->StatusTinggal == 'Tidak Aktif' && !empty($Pendatang->AlasanKeluar)):
                        ?>
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Alasan Keluar</span>
                                <p id="view-alasan" class="text-lg text-gray-900 mt-2 p-3 bg-gray-100 rounded-lg border-l-4 border-gray-400 ">
                                    <?= htmlspecialchars($Pendatang->AlasanKeluar) ?>
                                </p>
                            </div>
                        <?php
                        elseif (!empty($Pendatang->Alasan) && $Pendatang->Alasan != '-'):
                        ?>
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-500 uppercase tracking-wide">Alasan/Keterangan</span>
                                <p id="view-alasan" class="text-lg text-gray-900 mt-2 p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-400 ">
                                    <?= htmlspecialchars($Pendatang->Alasan) ?>
                                </p>
                            </div>
                        <?php
                        endif;
                        ?>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex flex-wrap gap-4 justify-end">
                    <button onclick="window.history.back()" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-all duration-200 flex items-center space-x-2 font-medium">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </button>

                </div>
            </div>
        </div>
    </div>