<?php
$JenisAkun = $this->session->userdata('JenisAkun');
?>
<style>
    .swal-custom-popup {
        max-height: 90vh !important;
        width: 550px !important;
        max-width: 90%;
    }

    .swal-custom-html-container {
        overflow-y: auto !important;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .swal-custom-html-container::-webkit-scrollbar {
        display: none;
    }
</style>
<div class="bg-white shadow-md rounded-lg">

    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-user text-indigo-950 mr-3"></i>
            Data Pendatang
        </h1>
        <?php if ($JenisAkun == "Admin" || $JenisAkun == "Penanggung Jawab") { ?>

            <button onclick="openModal()"
                class="px-4 py-3 bg-indigo-950 text-white rounded-lg text-lg lg:text-base transition-all duration-300">
                <i class="fa-solid fa-user-plus mr-2"></i>
                Tambah Pendatang
            </button>
        <?php } ?>
    </div>

    <div class="p-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <span class="text-lg text-gray-600">Show</span>
                <select id="entries-select" class="border border-gray-300 rounded px-2 py-1 text-lg">
                    <option value="1">1</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-lg text-gray-600">entries</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-lg text-gray-600">Search:</span>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 text-sm"></i>
                    </div>
                    <input type="text" id="searchInput" class="w-full lg:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
                        placeholder="Cari nama, NIK, status...">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full table-auto" id="pendatangTabel">
                <thead class="bg-gray-100">
                    <tr class="text-gray-600 uppercase text-sm">
                        <th class="py-3 px-5 text-left font-semibold">No</th>
                        <th class="py-3 px-5 text-left font-semibold">Nama</th>
                        <th class="py-3 px-5 text-left font-semibold">NIK</th>
                        <th class="py-3 px-5 text-left font-semibold">Tanggal Masuk</th>
                        <th class="py-3 px-5 text-left font-semibold">Status Verifikasi</th>
                        <th class="py-3 px-5 text-left font-semibold">Alasan</th>
                        <th class="py-3 px-5 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm" id="table-body">
                    <?php foreach ($Pendatang as $index => $item): ?>
                        <?php
                        $row_class = 'border-b border-gray-200 hover:bg-gray-100 transition-colors duration-200';
                        if ($item->StatusTinggal == 'Tidak Aktif') {
                            $row_class .= ' bg-gray-50 opacity-60';
                        }
                        ?>
                        <tr class="<?= $row_class ?>">
                            <td class="py-3 px-5 text-left text-base"><?= $index + 1 ?></td>
                            <td class="py-3 px-5 text-left text-base font-semibold text-gray-800"><?= htmlspecialchars($item->NamaLengkap) ?></td>
                            <td class="py-3 px-5 text-left text-base"><?= htmlspecialchars($item->NIK) ?></td>
                            <td class="py-3 px-5 text-left text-base"><?= htmlspecialchars(date('d M Y', strtotime($item->TanggalMasuk))) ?></td>

                            <td class="py-3 px-5 text-left text-base">
                                <?php
                                $status = $item->StatusTinggal;
                                $badge_class = 'bg-yellow-100 text-yellow-800';
                                $icon = 'fa-solid fa-clock';
                                if ($status == 'Aktif') {
                                    $badge_class = 'bg-green-100 text-green-800';
                                    $icon = 'fa-solid fa-check-circle';
                                } else if ($status == 'Ditolak' || $status == 'Tolak') {
                                    $badge_class = 'bg-red-100 text-red-800';
                                    $icon = 'fa-solid fa-times-circle';
                                } else if ($status == 'Tidak Aktif') {
                                    $badge_class = 'bg-slate-200 text-slate-600';
                                    $icon = 'fa-solid fa-archive';
                                }
                                ?>
                                <span class="px-3 py-1.5 text-sm font-semibold rounded-full inline-flex items-center gap-2 <?= $badge_class ?>">
                                    <i class="<?= $icon ?>"></i>
                                    <span><?= htmlspecialchars($status) ?></span>
                                </span>
                            </td>

                            <td class="py-3 px-5 text-left text-base">
                                <?php
                                if ($item->StatusTinggal == 'Tidak Aktif' && !empty($item->AlasanKeluar)) {
                                    echo '<div class="text-sm text-slate-700">';
                                    echo '<div class="font-bold text-slate-500">Telah Keluar / Diarsipkan</div>';
                                    if (!empty($item->TanggalKeluar)) {
                                        echo '<div><i class="fas fa-calendar-alt fa-fw mr-1 text-slate-400"></i> ' . htmlspecialchars(date('d M Y', strtotime($item->TanggalKeluar))) . '</div>';
                                    }
                                    echo '<div class="mt-1"><i class="fas fa-info-circle fa-fw mr-1 text-slate-400"></i> ' . htmlspecialchars($item->AlasanKeluar) . '</div>';
                                    echo '</div>';
                                } else if (($item->StatusTinggal == 'Ditolak' || $item->StatusTinggal == 'Tolak') && !empty($item->Alasan)) {
                                    echo '<div class="text-sm text-red-700">';
                                    echo '<div class="font-bold text-red-500">Ditolak</div>';
                                    echo '<div class="mt-1"><i class="fas fa-info-circle fa-fw mr-1 text-red-400"></i> ' . htmlspecialchars($item->Alasan) . '</div>';
                                    echo '</div>';
                                } else {
                                    echo '<span class="text-gray-400">-</span>';
                                }
                                ?>
                            </td>

                            <td class="py-3 px-5 text-center text-base">
                                <div class="flex justify-center items-center space-x-4">
                                    <button onclick="window.location.href='<?= base_url('Pendatang/viewDetail/' . $item->idPendatang) ?>'" class="text-blue-600 hover:text-blue-800" title="Lihat Detail">
                                        <i class="fas fa-eye text-2xl"></i>
                                    </button>

                                    <?php if ($JenisAkun == "Admin" || $JenisAkun == "Penanggung Jawab") { ?>
                                        <?php if ($item->StatusTinggal == 'Pending'): ?>
                                            <button onclick="editUser('<?= $item->idPendatang ?>')" class="text-green-600 hover:text-green-800" title="Edit Data"><i class="fas fa-edit text-2xl"></i></button>
                                        <?php endif; ?>
                                        <?php if ($item->StatusTinggal != 'Aktif'): ?>
                                            <button onclick="deleteUser('<?= $item->idPendatang ?>')" class="text-red-600 hover:text-red-800" title="Hapus Data"><i class="fas fa-trash text-2xl"></i></button>
                                        <?php endif; ?>
                                    <?php } ?>

                                    <?php if ($JenisAkun == "Admin" || $JenisAkun == "Kepala Lingkungan") { ?>
                                        <?php if ($item->StatusTinggal == 'Pending'): ?>
                                            <button onclick="verifikasiUser(<?= $item->idPendatang ?>)" class="text-yellow-600 hover:text-orange-800" title="Verifikasi Pendatang"><i class="fa-regular fa-circle-check text-2xl"></i></button>
                                        <?php endif; ?>
                                    <?php } ?>

                                    <?php if ($item->StatusTinggal == 'Aktif'): ?>
                                        <button onclick="archiveUser(<?= $item->idPendatang ?>)" class="text-orange-500 hover:text-orange-700" title="Arsipkan / Set Tanggal Keluar"><i class="fas fa-calendar-times text-2xl"></i></button>
                                    <?php endif; ?>

                                    <?php if ($item->StatusTinggal == 'Tidak Aktif'): ?>
                                        <button onclick="reactivateUser(<?= $item->idPendatang ?>)" class="text-teal-500 hover:text-teal-700" title="Aktifkan Kembali">
                                            <i class="fas fa-box-open text-2xl"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
            <div class="text-lg text-gray-700">
                Showing 1 to 2 of 2 entries
            </div>
            <div class="flex gap-2">
                <button id="prev-btn" class="px-3 py-1 border border-gray-300 rounded text-lg text-gray-500 bg-gray-100 cursor-not-allowed" disabled>
                    Previous
                </button>
                <button class="px-3 py-1 border border-blue-500 bg-blue-500 text-white rounded text-lg">
                    1
                </button>
                <button id="next-btn" class="px-3 py-1 border border-gray-300 rounded text-lg text-gray-500 bg-gray-100 cursor-not-allowed" disabled>
                    Next
                </button>
            </div>
        </div>
    </div>
</div>
<div id="imagePreviewModal" class="fixed inset-0 bg-black bg-opacity-75 z-[9999] hidden items-center justify-center p-4">
    <div class="relative bg-white p-2 rounded-lg shadow-xl max-w-4xl max-h-[90vh]">
        <button onclick="closeImageModal()" class="absolute -top-4 -right-4 bg-gray-400 text-white rounded-full w-10 h-10 flex items-center justify-center text-2xl font-bold hover:bg-red-700 ">
            &times;
        </button>
        <img id="modalImage" src="" alt="Preview Gambar" class="max-w-full max-h-[85vh] object-contain">
    </div>
</div>
<div id="modal-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-y-auto ">
            <div class="flex justify-between items-center p-6 border-b sticky top-0 bg-white z-10 shadow-lg ">
                <h3 class="text-2xl font-bold text-gray-900">Tambah Data Pendatang</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <?= form_open_multipart('pendatang/save', ['id' => 'pendatang-form', 'method' => 'post', 'class' => 'p-6']) ?> <input type="hidden" name="id_pendatang" id="id_pendatang">
            <!-- <form id="pendatang-form" class="p-6"> -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-lg font-medium text-gray-700 mb-3">Foto Diri <span class="text-red-500">*</span> </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                            <div class="mb-4 w-full h-48 flex items-center justify-center bg-gray-50 rounded">
                                <img src="<?php echo base_url('assets/img/swafoto.png'); ?>" alt="Foto Diri" class="max-h-full" id="preview-foto-diri">
                            </div>
                            <input class="block w-full text-lg text-black border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                id="fotoDiri"
                                name="fotoDiri"
                                type="file"
                                accept="image/*">
                            <p class="mt-2 text-sm text-gray-500">Upload foto diri (JPG, PNG, maksimal 5MB)</p>
                        </div>
                        <div class="mt-4 text-center">
                            <button type="button" onclick="previewFoto('foto_diri')"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-lg mr-2 transition-colors">
                                Preview Foto
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-lg font-medium text-gray-700 mb-2">NIK (16 Digit Sesuai KTP) <span class="text-red-500">*</span> </label>
                            <input type="text" id="nik" name="nik" maxlength="16" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan NIK">
                        </div>

                        <div>
                            <label class="block text-lg font-medium text-gray-700 mb-2">Nama Lengkap (Sesuai KTP) <span class="text-red-500">*</span> </label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan nama lengkap">
                        </div>



                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-2">No Handphone <span class="text-red-500">*</span> </label>
                                <input type="tel" id="telepon" name="telepon" pattern="[0-9]{1,13}" inputmode="numeric" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan nomor hp aktif" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,13)">
                            </div>
                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-2">Agama <span class="text-red-500">*</span> </label>
                                <select id="agama" name="agama" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Pilih Agama</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-2">Tempat Lahir <span class="text-red-500">*</span> </label>
                                <input type="text" id="tempat_lahir" name="tempat_lahir" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tempat lahir">
                            </div>
                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span> </label>
                                <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span> </label>
                                <select id="jenis_kelamin" name="jenis_kelamin" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-2">Golongan Darah <span class="text-red-500">*</span> </label>
                                <select id="golda" name="golda" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Pilih Golongan Darah</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="AB">AB</option>
                                    <option value="O">O</option>
                                </select>
                            </div>
                        </div>


                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-lg font-medium text-gray-700 mb-2">Provinsi Asal <span class="text-red-500">*</span> </label>
                                    <select id="provinsi" name="provinsi" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="" disabled selected>Pilih Provinsi</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-lg font-medium text-gray-700 mb-2">Kabupaten/Kota Asal <span class="text-red-500">*</span></label>
                                    <select id="kabupaten" name="kabupaten" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="" disabled selected>Pilih Kabupaten/Kota</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-lg font-medium text-gray-700 mb-2">Kecamatan Asal <span class="text-red-500">*</span></label>
                                    <select id="kecamatan" name="kecamatan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="" disabled selected>Pilih Kecamatan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-lg font-medium text-gray-700 mb-2">Kelurahan Asal <span class="text-red-500">*</span> </label>
                                    <select id="kelurahan" name="kelurahan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="" disabled selected>Pilih Kelurahan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-lg font-medium text-gray-700 mb-2">RT <span class="text-red-500">*</span></label>
                                    <input type="text" id="rt" name="rt" maxlength="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="001">
                                </div>
                                <div>
                                    <label class="block text-lg font-medium text-gray-700 mb-2">RW <span class="text-red-500">*</span> </label>
                                    <input type="text" id="rw" name="rw" maxlength="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="002">
                                </div>

                            </div>
                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-2">Alamat Lengkap KTP <span class="text-red-500">*</span> </label>
                                <textarea id="alamat" name="alamat" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Alamat lengkap sesuai KTP"></textarea>
                            </div>

                        </div>

                    </div>
                    <div class="flex justify-start space-x-3 mt-8 pt-6 border-t">
                        <button type="button" onclick="closeModal()" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors text-lg">
                            Kembali
                        </button>
                        <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-lg">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Data
                        </button>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-lg font-medium text-gray-700 mb-3">Foto KTP <span class="text-red-500">*</span> </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                            <div class="mb-4 w-full h-48 flex items-center justify-center bg-gray-50 rounded">
                                <img src="<?php echo base_url('assets/img/ktp.png'); ?>" alt="Foto KTP" class="max-h-full" id="preview-foto-ktp">
                            </div>
                            <input class="block w-full text-lg text-black border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                id="fotoKTP"
                                name="fotoKTP"
                                type="file"
                                accept="image/*">
                            <p class="mt-2 text-sm text-gray-500">Upload foto KTP (JPG, PNG, maksimal 5MB)</p>
                        </div>
                        <div class="mt-4 space-x-2 text-center">
                            <button type="button" onclick="previewFoto('foto_ktp')"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-lg transition-colors">
                                Preview Foto
                            </button>

                        </div>
                    </div>

                    <?php if ($JenisAkun == "Admin"): ?>
                        <div class="mb-4">
                            <label class="block text-lg font-medium text-gray-700 mb-2">Pilih Penanggung Jawab (PJ)</label>
                            <select id="pj_selector" name="id_pj" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih PJ untuk menentukan lokasi otomatis --</option>
                                <?php foreach ($NamaPJ as $j): ?>
                                    <option value="<?= $j->idPJ ?>"><?= htmlspecialchars($j->NamaPJ) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>


                    <div>
                        <label class="block text-lg font-medium text-gray-700 mb-3">Lokasi Tinggal <span class="text-red-500">*</span> </label>
                        <!-- <div class="space-y-3 mb-4">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" value="" class="sr-only peer">
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-600 rounded-full peer dark:bg-white-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-white-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600"></div>
                                    <span class="ms-3 text-lg font-medium text-gray-900 dark:text-gray-400">Gunakan alamat dan lokasi yang sama dengan penanggung jawab</span>
                                   
                                </label>
                            </div> -->

                        <div id="map" class="w-full h-64 border border-gray-300 rounded-lg mb-3 z-0"></div>
                        <div class="mb-4">
                            <label class="block text-lg font-medium text-gray-700 mb-2">Cari Alamat</label>
                            <div class="flex gap-2">
                                <input type="text"
                                    id="address-search"
                                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Masukkan alamat yang ingin dicari...">
                                <button type="button"
                                    onclick="searchAddress()"
                                    class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors">
                                    <i class="fas fa-search mr-1"></i>Cari
                                </button>
                            </div>
                            <!-- Search Results Dropdown -->
                            <div id="search-results" class="hidden mt-2 border border-gray-300 rounded-lg bg-white shadow-lg max-h-60 overflow-y-auto z-10">
                                <!-- Results will be populated here -->
                            </div>
                        </div>
                        <button type="button" onclick="getCurrentLocation()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-lg transition-colors">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            Cari Lokasi Saya
                        </button>
                        <div class="mt-4">
                            <label class="block text-lg font-medium text-gray-700 mb-2">Alamat Sekarang </label>
                            <textarea id="tempat_tujuan" name="tempat_tujuan" rows="3"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Alamat lengkap tempat tinggal saat ini"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-2">Latitude</label>
                                <input type="text" id="latitude" name="latitude" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-lg bg-gray-50" readonly>
                            </div>
                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-2">Longitude</label>
                                <input type="text" id="longitude" name="longitude" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-lg bg-gray-50" readonly>
                            </div>
                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-2">Tanggal Masuk <span class="text-red-500">*</span></label>
                                <input type="date" id="tanggal_masuk" name="tanggal_masuk" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-2">Tanggal Keluar (Opsional)</label>
                                <input type="date" id="tanggal_keluar" name="tanggal_keluar" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                        </div>
                        <div class="gap-4 mt-4 space-y-4">
                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-2">Tujuan Kedatangan <span class="text-red-500">*</span></label>
                                <textarea id="tujuan" name="tujuan" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Jelaskan tujuan kedatangan Anda"></textarea>
                            </div>
                            <div>
                                <label class="block text-lg font-medium text-gray-700 mb-2">Kepala Lingkungan <span class="text-red-500">*</span> </label>
                                <select id="id_kaling" name="id_kaling" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Pilih Kepala Lingkungan</option>
                                    <?php foreach ($NamaKaling as $k) { ?>
                                        <option value="<?= $k->idKaling ?>"><?= $k->NamaKaling ?></option>
                                    <?php } ?>
                                </select>
                            </div>


                            <input type="hidden" id="latitude_hidden" name="latitude_hidden">
                            <input type="hidden" id="longitude_hidden" name="longitude_hidden">

                        </div>
                    </div>
                </div>
            </div>
            <!-- </form> -->
        </div>
    </div>
</div>
<!-- Filterasi -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const entriesSelect = document.getElementById('entries-select');
        const tableBody = document.getElementById('table-body');
        const table = document.getElementById('pendatangTabel');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');

        const pjSelector = document.getElementById('pj_selector');

        if (pjSelector) {
            pjSelector.addEventListener('change', function() {
                const selectedPjId = this.value;

                if (!selectedPjId) {
                    console.log('Tidak ada PJ yang dipilih.');
                    return;
                }

                Swal.fire({
                    title: 'Mencari Lokasi PJ...',
                    icon: 'info',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch(`<?= site_url('pendatang/get_pj_location/') ?>${selectedPjId}`)
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();
                        if (data.status === 'success') {
                            const lat = parseFloat(data.latitude);
                            const lon = parseFloat(data.longitude);

                            if (map) {
                                map.setView([lat, lon], 17);
                                if (marker) {
                                    map.removeLayer(marker);
                                }

                                marker = L.marker([lat, lon]).addTo(map);

                                updateCoordinates(lat, lon);
                                reverseGeocode(lat, lon);
                            } else {
                                initializeMap();
                                setTimeout(() => {
                                    map.setView([lat, lon], 17);
                                    if (marker) map.removeLayer(marker);
                                    marker = L.marker([lat, lon]).addTo(map);
                                    updateCoordinates(lat, lon);
                                    reverseGeocode(lat, lon);
                                }, 500);
                            }

                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: data.message,
                            });
                        }
                    })
                    .catch(error => {
                        Swal.close();
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat mengambil data lokasi.',
                        });
                    });
            });
        }


        let currentPage = 1;
        let entriesPerPage = 10;
        let filteredData = [];
        let allData = [];

        function initializeData() {
            const rows = tableBody.querySelectorAll('tr');
            allData = Array.from(rows).map((row, index) => {
                const cells = row.querySelectorAll('td');
                return {
                    element: row.cloneNode(true),
                    originalIndex: index + 1,
                    nama: cells[1]?.textContent.trim().toLowerCase() || '',
                    nik: cells[2]?.textContent.trim().toLowerCase() || '',
                    tanggal: cells[3]?.textContent.trim() || '',
                    status: cells[4]?.textContent.trim().toLowerCase() || '',
                    alasan: cells[5]?.textContent.trim().toLowerCase() || ''
                };
            });
            filteredData = [...allData];
            updateTable();
        }

        function filterData(searchTerm) {
            const term = searchTerm.toLowerCase().trim();

            if (term === '') {
                filteredData = [...allData];
            } else {
                filteredData = allData.filter(item =>
                    item.nama.includes(term) ||
                    item.nik.includes(term) ||
                    item.status.includes(term) ||
                    item.alasan.includes(term)
                );
            }

            currentPage = 1;
            updateTable();
        }

        function updateTable() {
            const startIndex = (currentPage - 1) * entriesPerPage;
            const endIndex = startIndex + entriesPerPage;
            const pageData = filteredData.slice(startIndex, endIndex);

            tableBody.innerHTML = '';

            if (pageData.length === 0) {
                const emptyRow = document.createElement('tr');
                emptyRow.innerHTML = `
                <td colspan="7" class="py-8 px-6 text-center text-gray-500 text-lg">
                    ${filteredData.length === 0 ? 'Tidak ada data yang ditemukan' : 'Tidak ada data di halaman ini'}
                </td>
            `;
                tableBody.appendChild(emptyRow);
            } else {
                pageData.forEach((item, index) => {
                    const row = item.element.cloneNode(true);
                    const firstCell = row.querySelector('td:first-child');
                    if (firstCell) {
                        firstCell.textContent = startIndex + index + 1;
                    }
                    tableBody.appendChild(row);
                });
            }

            updatePagination();
            updateInfoText();
        }

        function updatePagination() {
            const totalPages = Math.ceil(filteredData.length / entriesPerPage);

            if (currentPage <= 1) {
                prevBtn.disabled = true;
                prevBtn.classList.add('cursor-not-allowed', 'bg-gray-100', 'text-gray-500');
                prevBtn.classList.remove('hover:bg-gray-200', 'text-gray-700');
            } else {
                prevBtn.disabled = false;
                prevBtn.classList.remove('cursor-not-allowed', 'bg-gray-100', 'text-gray-500');
                prevBtn.classList.add('hover:bg-gray-200', 'text-gray-700');
            }

            if (currentPage >= totalPages || totalPages === 0) {
                nextBtn.disabled = true;
                nextBtn.classList.add('cursor-not-allowed', 'bg-gray-100', 'text-gray-500');
                nextBtn.classList.remove('hover:bg-gray-200', 'text-gray-700');
            } else {
                nextBtn.disabled = false;
                nextBtn.classList.remove('cursor-not-allowed', 'bg-gray-100', 'text-gray-500');
                nextBtn.classList.add('hover:bg-gray-200', 'text-gray-700');
            }

            const currentPageBtn = document.querySelector('.bg-blue-500');
            if (currentPageBtn) {
                currentPageBtn.textContent = totalPages > 0 ? currentPage : 1;
            }
        }

        function updateInfoText() {
            const startIndex = (currentPage - 1) * entriesPerPage + 1;
            const endIndex = Math.min(currentPage * entriesPerPage, filteredData.length);
            const totalEntries = filteredData.length;

            const infoText = document.querySelector('.text-lg.text-gray-700');
            if (infoText) {
                if (totalEntries === 0) {
                    infoText.textContent = 'Showing 0 to 0 of 0 entries';
                } else {
                    infoText.textContent = `Showing ${startIndex} to ${endIndex} of ${totalEntries} entries`;
                }
            }
        }

        searchInput.addEventListener('input', function() {
            filterData(this.value);
        });

        entriesSelect.addEventListener('change', function() {
            entriesPerPage = parseInt(this.value);
            currentPage = 1;
            updateTable();
        });

        prevBtn.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                updateTable();
            }
        });

        nextBtn.addEventListener('click', function() {
            const totalPages = Math.ceil(filteredData.length / entriesPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                updateTable();
            }
        });

        initializeData();
    });
</script>
<script>
    const imageModal = document.getElementById('imagePreviewModal');
    const modalImage = document.getElementById('modalImage');

    function openImageModal(imageSrc) {
        if (imageModal && modalImage) {
            modalImage.src = imageSrc;
            imageModal.classList.remove('hidden');
            imageModal.classList.add('flex');
        }
    }

    function closeImageModal() {
        if (imageModal) {
            imageModal.classList.add('hidden');
            imageModal.classList.remove('flex');
            modalImage.src = '';
        }
    }
    imageModal.addEventListener('click', function(event) {
        if (event.target === imageModal) {
            closeImageModal();
        }
    });

    function setThumbnail(jenis) {
        let fileInput, previewImg;

        if (jenis === 'foto_diri') {
            fileInput = document.getElementById('fotoDiri');
            previewImg = document.getElementById('preview-foto-diri');
        } else if (jenis === 'foto_ktp') {
            fileInput = document.getElementById('fotoKTP');
            previewImg = document.getElementById('preview-foto-ktp');
        }

        if (fileInput && fileInput.files && fileInput.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
            };
            reader.readAsDataURL(fileInput.files[0]);
        }
    }

    document.getElementById('fotoDiri').addEventListener('change', () => setThumbnail('foto_diri'));
    document.getElementById('fotoKTP').addEventListener('change', () => setThumbnail('foto_ktp'));

    function previewFoto(jenis) {
        let fileInput, previewImg;

        if (jenis === 'foto_diri') {
            fileInput = document.getElementById('fotoDiri');
            previewImg = document.getElementById('preview-foto-diri');
        } else if (jenis === 'foto_ktp') {
            fileInput = document.getElementById('fotoKTP');
            previewImg = document.getElementById('preview-foto-ktp');
        }

        if (fileInput.files.length > 0) {
            const imageSrc = previewImg.src;
            openImageModal(imageSrc);
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'File Belum Dipilih',
                text: 'Silakan pilih file foto terlebih dahulu sebelum melihat preview.',
                confirmButtonText: 'OK'
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('fotoDiri').addEventListener('change', function() {
            if (this.files && this.files[0]) {
                previewFoto('foto_diri');
            }
        });

        document.getElementById('fotoKTP').addEventListener('change', function() {
            if (this.files && this.files[0]) {
                previewFoto('foto_ktp');
            }
        });
    });

    function scanKTP() {
        const fileInput = document.getElementById('fotoKTP');

        if (!fileInput.files || !fileInput.files[0]) {
            Swal.fire({
                icon: 'warning',
                title: 'Foto KTP Tidak Dipilih',
                text: 'Pilih foto KTP terlebih dahulu!',
                confirmButtonText: 'OK'
            });
            return;
        }


    }

    function validateForm() {
        const requiredFields = [
            'nik', 'nama_lengkap', 'telepon', 'agama', 'tempat_lahir',
            'tanggal_lahir', 'jenis_kelamin', 'provinsi', 'kabupaten',
            'kecamatan', 'kelurahan', 'alamat', 'tanggal_masuk', 'tujuan', 'id_kaling'
        ];


        for (let field of requiredFields) {
            const element = document.getElementById(field);
            if (!element.value.trim()) {
                const fieldLabel = element.previousElementSibling ?
                    element.previousElementSibling.textContent :
                    field.replace('_', ' ').toUpperCase();

                Swal.fire({
                    icon: 'error',
                    title: 'Semua field harus diisi',
                    text: `Field ${fieldLabel} harus diisi!`,
                    confirmButtonText: 'OK'
                }).then(() => {
                    element.focus();
                });
                return false;
            }
        }
        const rw = document.getElementById('rt').value;
        if (!rt || !rw || rt === '0' || rw === '0') {
            Swal.fire({
                icon: 'warning',
                title: 'rt dan rw harus berisi 0',
                showCancelButton: true,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    openModal();
                }
            });
            return false;
        }
        const fotoKTP = document.getElementById('fotoKTP');
        if (!fotoKTP.files || !fotoKTP.files[0]) {
            Swal.fire({
                icon: 'error',
                title: 'Foto KTP Wajib Diisi',
                text: 'Silakan upload foto KTP Anda!',
                confirmButtonText: 'Upload Foto KTP'
            }).then(() => {
                fotoKTP.focus();
            });
            return false;
        }

        const fotoDiri = document.getElementById('fotoDiri');
        if (!fotoDiri.files || !fotoDiri.files[0]) {
            Swal.fire({
                icon: 'error',
                title: 'Foto Diri Wajib Diisi',
                text: 'Silakan upload foto diri/swafoto Anda!',
                confirmButtonText: 'Upload Foto Diri'
            }).then(() => {
                fotoDiri.focus();
            });
            return false;
        }

        const nik = document.getElementById('nik').value;
        if (nik.length !== 16 || !/^\d{16}$/.test(nik)) {
            Swal.fire({
                icon: 'error',
                title: 'Format NIK Salah',
                text: 'NIK harus berisi 16 digit angka!',
                confirmButtonText: 'Perbaiki'
            }).then(() => {
                document.getElementById('nik').focus();
            });
            return false;
        }

        const telepon = document.getElementById('telepon').value;
        if (!/^\d{10,13}$/.test(telepon)) {
            Swal.fire({
                icon: 'error',
                title: 'Format Telepon Salah',
                text: 'Nomor telepon harus berisi 10-13 digit angka!',
                confirmButtonText: 'Perbaiki'
            }).then(() => {
                document.getElementById('telepon').focus();
            });
            return false;
        }

        const latitude = document.getElementById('latitude').value;
        const longitude = document.getElementById('longitude').value;

        if (!latitude || !longitude || latitude === '0' || longitude === '0') {
            Swal.fire({
                icon: 'warning',
                title: 'Lokasi Belum Dipilih',
                text: 'Silakan pilih lokasi tinggal pada peta!',
                confirmButtonText: 'Pilih Lokasi',
                showCancelButton: true,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    openModal();
                }
            });
            return false;
        }

        return true;
    }

    function updatePhotoStatus() {
        const fotoKTP = document.getElementById('fotoKTP');
        const fotoDiri = document.getElementById('fotoDiri');

        const ktpStatus = document.getElementById('ktp-status');
        if (ktpStatus) {
            if (fotoKTP.files && fotoKTP.files[0]) {
                ktpStatus.innerHTML = '<span class="text-green-600 text-sm">✓ Foto KTP sudah diupload</span>';
            } else {
                ktpStatus.innerHTML = '<span class="text-red-600 text-sm">* Foto KTP wajib diupload</span>';
            }
        }

        const diriStatus = document.getElementById('diri-status');
        if (diriStatus) {
            if (fotoDiri.files && fotoDiri.files[0]) {
                diriStatus.innerHTML = '<span class="text-green-600 text-sm">✓ Foto diri sudah diupload</span>';
            } else {
                diriStatus.innerHTML = '<span class="text-red-600 text-sm">* Foto diri wajib diupload</span>';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const fotoKTP = document.getElementById('fotoKTP');
        const fotoDiri = document.getElementById('fotoDiri');

        if (fotoKTP) {
            fotoKTP.addEventListener('change', updatePhotoStatus);
        }

        if (fotoDiri) {
            fotoDiri.addEventListener('change', updatePhotoStatus);
        }

        updatePhotoStatus();
    });

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('pendatang-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (!navigator.onLine) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tidak Ada Koneksi Internet',
                        text: 'Periksa koneksi WiFi atau data seluler Anda terlebih dahulu.',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                if (!validateForm()) {
                    return false;
                }

                Swal.fire({
                    title: 'Konfirmasi Pengiriman',
                    text: 'Apakah Anda yakin data yang diisi sudah benar?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Kirim!',
                    cancelButtonText: 'Periksa Lagi'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Mengirim Data...',
                            text: 'Mohon tunggu, data sedang diproses',
                            icon: 'info',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        submitFormWithSuccess(this);
                    }
                });
            });
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        const provinsiSelect = document.getElementById("provinsi");
        const kabupatenSelect = document.getElementById("kabupaten");
        const kecamatanSelect = document.getElementById("kecamatan");
        const kelurahanSelect = document.getElementById("kelurahan");


        fetch("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json")
            .then(res => {
                if (!res.ok) throw new Error('Network error');
                return res.json();
            })
            .then(data => {
                Swal.close();
                data.forEach(provinsi => {
                    provinsiSelect.innerHTML += `<option value="${provinsi.name}" data-id="${provinsi.id}">${provinsi.name}</option>`;
                });

            })
            .catch(error => {
                Swal.close();
                console.error('Error loading provinces:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memuat Provinsi',
                    text: 'Terjadi kesalahan saat memuat data provinsi. Periksa koneksi internet Anda.',
                    confirmButtonText: 'Coba Lagi',
                    showCancelButton: true,
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            });

        provinsiSelect.addEventListener("change", function() {
            const selectedOption = this.options[this.selectedIndex];
            const provinsiId = selectedOption.getAttribute('data-id');
            const provinsiName = selectedOption.value;

            kabupatenSelect.innerHTML = `<option value="">Pilih Kabupaten/Kota</option>`;
            kecamatanSelect.innerHTML = `<option value="">Pilih Kecamatan</option>`;
            kelurahanSelect.innerHTML = `<option value="">Pilih Kelurahan</option>`;

            if (provinsiId && provinsiName && provinsiName !== "") {
                console.log('Provinsi dipilih:', provinsiName, 'ID:', provinsiId);

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });

                Toast.fire({
                    icon: 'info',
                    title: 'Memuat kabupaten/kota...'
                });

                fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinsiId}.json`)
                    .then(res => {
                        if (!res.ok) throw new Error('Network error');
                        return res.json();
                    })
                    .then(data => {
                        data.forEach(kabupaten => {
                            kabupatenSelect.innerHTML += `<option value="${kabupaten.name}" data-id="${kabupaten.id}">${kabupaten.name}</option>`;
                        });

                        Toast.fire({
                            icon: 'success',
                            title: 'Kabupaten/kota berhasil dimuat'
                        });
                    })
                    .catch(error => {
                        console.error('Error loading regencies:', error);
                        Toast.fire({
                            icon: 'error',
                            title: 'Gagal memuat kabupaten/kota'
                        });
                    });
            }
        });

        kabupatenSelect.addEventListener("change", function() {
            const selectedOption = this.options[this.selectedIndex];
            const kabupatenId = selectedOption.getAttribute('data-id');
            const kabupatenName = selectedOption.value;

            kecamatanSelect.innerHTML = `<option value="">Pilih Kecamatan</option>`;
            kelurahanSelect.innerHTML = `<option value="">Pilih Kelurahan</option>`;

            if (kabupatenId && kabupatenName && kabupatenName !== "") {
                console.log('Kabupaten dipilih:', kabupatenName, 'ID:', kabupatenId);

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });

                Toast.fire({
                    icon: 'info',
                    title: 'Memuat kecamatan...'
                });

                fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${kabupatenId}.json`)
                    .then(res => {
                        if (!res.ok) throw new Error('Network error');
                        return res.json();
                    })
                    .then(data => {
                        data.forEach(kecamatan => {
                            kecamatanSelect.innerHTML += `<option value="${kecamatan.name}" data-id="${kecamatan.id}">${kecamatan.name}</option>`;
                        });

                        Toast.fire({
                            icon: 'success',
                            title: 'Kecamatan berhasil dimuat'
                        });
                    })
                    .catch(error => {
                        console.error('Error loading districts:', error);
                        Toast.fire({
                            icon: 'error',
                            title: 'Gagal memuat kecamatan'
                        });
                    });
            }
        });

        kecamatanSelect.addEventListener("change", function() {
            const selectedOption = this.options[this.selectedIndex];
            const kecamatanId = selectedOption.getAttribute('data-id');
            const kecamatanName = selectedOption.value;

            kelurahanSelect.innerHTML = `<option value="">Pilih Kelurahan</option>`;

            if (kecamatanId && kecamatanName && kecamatanName !== "") {
                console.log('Kecamatan dipilih:', kecamatanName, 'ID:', kecamatanId);

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });

                Toast.fire({
                    icon: 'info',
                    title: 'Memuat kelurahan...'
                });

                fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${kecamatanId}.json`)
                    .then(res => {
                        if (!res.ok) throw new Error('Network error');
                        return res.json();
                    })
                    .then(data => {
                        data.forEach(kelurahan => {
                            kelurahanSelect.innerHTML += `<option value="${kelurahan.name}" data-id="${kelurahan.id}">${kelurahan.name}</option>`;
                        });

                        Toast.fire({
                            icon: 'success',
                            title: 'Kelurahan berhasil dimuat'
                        });
                    })
                    .catch(error => {
                        console.error('Error loading villages:', error);
                        Toast.fire({
                            icon: 'error',
                            title: 'Gagal memuat kelurahan'
                        });
                    });
            }
        });
    });

    function resetWilayahDropdowns() {
        const kabupatenSelect = document.getElementById("kabupaten");
        const kecamatanSelect = document.getElementById("kecamatan");
        const kelurahanSelect = document.getElementById("kelurahan");

        if (kabupatenSelect) kabupatenSelect.innerHTML = `<option value="">Pilih Kabupaten/Kota</option>`;
        if (kecamatanSelect) kecamatanSelect.innerHTML = `<option value="">Pilih Kecamatan</option>`;
        if (kelurahanSelect) kelurahanSelect.innerHTML = `<option value="">Pilih Kelurahan</option>`;
    }

    function validateWilayahSelection() {
        const provinsi = document.getElementById("provinsi")?.value;
        const kabupaten = document.getElementById("kabupaten")?.value;
        const kecamatan = document.getElementById("kecamatan")?.value;
        const kelurahan = document.getElementById("kelurahan")?.value;

        if (!provinsi || provinsi === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Provinsi Belum Dipilih',
                text: 'Silakan pilih provinsi terlebih dahulu!',
                confirmButtonText: 'OK'
            });
            return false;
        }

        if (!kabupaten || kabupaten === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Kabupaten/Kota Belum Dipilih',
                text: 'Silakan pilih kabupaten/kota terlebih dahulu!',
                confirmButtonText: 'OK'
            });
            return false;
        }

        if (!kecamatan || kecamatan === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Kecamatan Belum Dipilih',
                text: 'Silakan pilih kecamatan terlebih dahulu!',
                confirmButtonText: 'OK'
            });
            return false;
        }

        if (!kelurahan || kelurahan === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Kelurahan Belum Dipilih',
                text: 'Silakan pilih kelurahan terlebih dahulu!',
                confirmButtonText: 'OK'
            });
            return false;
        }

        return true;
    }

    function submitFormWithSuccess(form) {
        try {
            const formData = new FormData(form);

            const rtElement = document.getElementById('rt');
            const rwElement = document.getElementById('rw');

            if (rtElement) {
                const rtValue = rtElement.value.trim();
                if (rtValue === '' || rtValue === null || rtValue === undefined) {
                    formData.set('rt', '');
                } else {
                    formData.set('rt', rtValue);
                }
            }

            if (rwElement) {
                const rwValue = rwElement.value.trim();
                if (rwValue === '' || rwValue === null || rwValue === undefined) {
                    formData.set('rw', '');
                } else {
                    formData.set('rw', rwValue);
                }
            }

            console.log('RT Value being sent:', formData.get('rt'));
            console.log('RW Value being sent:', formData.get('rw'));

            for (let [key, value] of formData.entries()) {
                if (key === 'rt' || key === 'rw') {
                    console.log(`${key}:`, value, '(type:', typeof value, ')');
                }
            }

            fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        return response.json().catch(() => ({}));
                    }
                    throw new Error('Network response was not ok');
                })
                .then(data => {
                    Swal.close();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data pendatang berhasil disimpan.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        document.getElementById('pendatang-form').reset();
                        closeModal();
                    });
                })
                .catch(error => {
                    Swal.close();
                    console.error('Submit error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyimpan',
                        text: 'Terjadi kesalahan saat menyimpan data. Periksa koneksi internet dan coba lagi.',
                        confirmButtonText: 'OK'
                    });
                });

        } catch (error) {
            console.error('Form submit error:', error);
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan pada form. Silakan coba lagi.',
                confirmButtonText: 'OK'
            });
        }
    }

    function debugFormData() {
        const rtElement = document.getElementById('rt');
        const rwElement = document.getElementById('rw');

        console.log('=== DEBUG FORM DATA ===');
        console.log('RT Element:', rtElement);
        console.log('RT Value:', rtElement ? rtElement.value : 'Element not found');
        console.log('RT Value type:', rtElement ? typeof rtElement.value : 'N/A');
        console.log('RT Value === "0":', rtElement ? (rtElement.value === "0") : 'N/A');
        console.log('RT Value == 0:', rtElement ? (rtElement.value == 0) : 'N/A');

        console.log('RW Element:', rwElement);
        console.log('RW Value:', rwElement ? rwElement.value : 'Element not found');
        console.log('RW Value type:', rwElement ? typeof rwElement.value : 'N/A');
        console.log('RW Value === "0":', rwElement ? (rwElement.value === "0") : 'N/A');
        console.log('RW Value == 0:', rwElement ? (rwElement.value == 0) : 'N/A');
        console.log('======================');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const rtElement = document.getElementById('rt');
        const rwElement = document.getElementById('rw');

        if (rtElement) {
            rtElement.addEventListener('change', function() {
                console.log('RT changed to:', this.value, '(type:', typeof this.value, ')');
            });
        }

        if (rwElement) {
            rwElement.addEventListener('change', function() {
                console.log('RW changed to:', this.value, '(type:', typeof this.value, ')');
            });
        }
    });

    function openModal() {
        const modal = document.getElementById('modal-overlay');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        Swal.fire({
            icon: 'info',
            title: 'Peta Dibuka',
            text: 'Klik pada peta untuk memilih lokasi atau gunakan pencarian alamat.',
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });

        setTimeout(() => {
            initializeMap();
        }, 100);
    }

    function closeModal() {
        Swal.fire({
            title: 'Tutup Peta?',
            text: 'Apakah Anda yakin ingin menutup form? Data yang belum disimpan akan hilang.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Tutup',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const modal = document.getElementById('modal-overlay');
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';

                document.getElementById('pendatang-form').reset();

                const fotoKtpName = document.getElementById('foto-ktp-name');
                const scanKtpName = document.getElementById('scan-ktp-name');
                if (fotoKtpName) fotoKtpName.textContent = 'No file selected.';
                if (scanKtpName) scanKtpName.textContent = 'No file selected.';

                document.getElementById('latitude').value = '';
                document.getElementById('longitude').value = '';
                document.getElementById('latitude_hidden').value = '';
                document.getElementById('longitude_hidden').value = '';
                document.getElementById('tempat_tujuan').value = '';

                if (map) {
                    map.remove();
                    map = null;
                    marker = null;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Peta Ditutup',
                    text: 'Form telah direset.',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }
        });
    }

    function updateCoordinates(lat, lng) {
        document.getElementById('latitude').value = lat.toFixed(6);
        document.getElementById('longitude').value = lng.toFixed(6);
        document.getElementById('latitude_hidden').value = lat.toFixed(6);
        document.getElementById('longitude_hidden').value = lng.toFixed(6);

        Swal.fire({
            icon: 'success',
            title: 'Koordinat Diperbarui',
            text: `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`,
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }

    let map;
    let marker;

    function initializeMap() {
        if (typeof L === 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Library Peta Tidak Tersedia',
                text: 'Library Leaflet tidak dapat dimuat. Periksa koneksi internet Anda.',
                confirmButtonText: 'OK'
            });
            return;
        }

        if (map) {
            map.remove();
        }

        try {
            map = L.map('map').setView([-8.40824, 115.18802], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            map.on('click', function(e) {
                if (marker) {
                    map.removeLayer(marker);
                }

                marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);
                updateCoordinates(e.latlng.lat, e.latlng.lng);
                reverseGeocode(e.latlng.lat, e.latlng.lng);
            });

            Swal.fire({
                icon: 'success',
                title: 'Peta Siap Digunakan',
                text: 'Klik pada peta untuk memilih lokasi.',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });

        } catch (error) {
            console.error('Map initialization error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal Memuat Peta',
                text: 'Terjadi kesalahan saat memuat peta. Silakan refresh halaman.',
                confirmButtonText: 'Refresh',
                showCancelButton: true,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        }
    }

    function searchAddress() {
        const searchInput = document.getElementById('address-search');
        const searchResults = document.getElementById('search-results');
        const query = searchInput.value.trim();

        if (!query) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Masukkan alamat yang ingin dicari!',
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }

        searchResults.innerHTML = '<div class="p-3 text-center text-gray-500">Mencari alamat...</div>';
        searchResults.classList.remove('hidden');

        const searchUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&countrycodes=id`;

        fetch(searchUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                displaySearchResults(data);
            })
            .catch(error => {
                console.error('Error searching address:', error);
                searchResults.classList.add('hidden');
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Terjadi kesalahan saat mencari alamat. Periksa koneksi internet Anda.',
                    confirmButtonText: 'OK'
                });
            });
    }

    function displaySearchResults(results) {
        const searchResults = document.getElementById('search-results');

        if (results.length === 0) {
            searchResults.innerHTML = '<div class="p-3 text-center text-gray-500">Alamat tidak ditemukan</div>';

            Swal.fire({
                icon: 'info',
                title: 'Alamat Tidak Ditemukan',
                text: 'Coba gunakan kata kunci yang lebih spesifik atau periksa ejaan alamat.',
                timer: 3000,
                showConfirmButton: false
            });
            return;
        }

        let html = '';
        results.forEach((result, index) => {
            html += `
            <div class="p-3 border-b border-gray-200 hover:bg-gray-50 cursor-pointer" 
                 onclick="selectSearchResult(${result.lat}, ${result.lon}, '${result.display_name.replace(/'/g, "\\'")}')">
                <div class="font-medium text-gray-900">${result.display_name}</div>
                <div class="text-sm text-gray-500">Lat: ${parseFloat(result.lat).toFixed(6)}, Lon: ${parseFloat(result.lon).toFixed(6)}</div>
            </div>
        `;
        });

        searchResults.innerHTML = html;

        Swal.fire({
            icon: 'success',
            title: 'Alamat Ditemukan',
            text: `Ditemukan ${results.length} hasil pencarian. Pilih salah satu alamat.`,
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }

    function selectSearchResult(lat, lon, address) {
        const searchResults = document.getElementById('search-results');
        const searchInput = document.getElementById('address-search');

        searchResults.classList.add('hidden');
        searchInput.value = '';

        if (map) {
            map.setView([lat, lon], 15);

            if (marker) {
                map.removeLayer(marker);
            }

            marker = L.marker([lat, lon]).addTo(map);
            updateCoordinates(lat, lon);
            document.getElementById('tempat_tujuan').value = address;

            Swal.fire({
                icon: 'success',
                title: 'Lokasi Dipilih',
                text: 'Alamat berhasil dipilih dan ditandai pada peta.',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan Peta',
                text: 'Peta belum diinisialisasi. Silakan refresh halaman.',
                confirmButtonText: 'OK'
            });
        }
    }

    function clearSearch() {
        const searchInput = document.getElementById('address-search');
        const searchResults = document.getElementById('search-results');

        searchInput.value = '';
        searchResults.classList.add('hidden');

        Swal.fire({
            icon: 'info',
            title: 'Pencarian Dibersihkan',
            text: 'Field pencarian telah dikosongkan.',
            timer: 1500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }

    function getCurrentLocation() {
        if (!navigator.geolocation) {
            Swal.fire({
                icon: 'error',
                title: 'Geolocation Tidak Didukung',
                text: 'Browser Anda tidak mendukung fitur geolocation.',
                confirmButtonText: 'OK'
            });
            return;
        }

        Swal.fire({
            title: 'Mencari Lokasi Anda...',
            text: 'Mohon tunggu, sedang mencari lokasi Anda',
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            Swal.close();

            if (map) {
                map.setView([lat, lng], 15);

                if (marker) {
                    map.removeLayer(marker);
                }

                marker = L.marker([lat, lng]).addTo(map);
                updateCoordinates(lat, lng);
                reverseGeocode(lat, lng);

                Swal.fire({
                    icon: 'success',
                    title: 'Lokasi Ditemukan!',
                    text: 'Lokasi Anda berhasil ditemukan dan ditandai pada peta.',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Peta Tidak Tersedia',
                    text: 'Peta belum diinisialisasi. Silakan refresh halaman.',
                    confirmButtonText: 'OK'
                });
            }
        }, function(error) {
            Swal.close();

            let errorMessage = '';
            let errorTitle = '';

            switch (error.code) {
                case error.PERMISSION_DENIED:
                    errorTitle = 'Izin Akses Ditolak';
                    errorMessage = 'Izin akses lokasi ditolak. Silakan izinkan akses lokasi di browser Anda dan coba lagi.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    errorTitle = 'Lokasi Tidak Tersedia';
                    errorMessage = 'Informasi lokasi tidak tersedia. Pastikan GPS aktif dan coba lagi.';
                    break;
                case error.TIMEOUT:
                    errorTitle = 'Waktu Habis';
                    errorMessage = 'Waktu pencarian lokasi habis. Coba lagi atau pilih lokasi secara manual pada peta.';
                    break;
                default:
                    errorTitle = 'Kesalahan Tidak Diketahui';
                    errorMessage = 'Terjadi kesalahan yang tidak diketahui saat mencari lokasi.';
                    break;
            }

            Swal.fire({
                icon: 'error',
                title: errorTitle,
                text: errorMessage,
                confirmButtonText: 'OK',
                footer: '<small>Anda dapat memilih lokasi secara manual dengan mengklik pada peta</small>'
            });
        }, {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 300000
        });
    }

    function reverseGeocode(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Data Alamat:', data);
                if (data.display_name) {
                    document.getElementById('tempat_tujuan').value = data.display_name;
                } else {
                    consol.warn('Alamat tidak ditemukan');
                    document.getElementById('tempat_tujuan').value = `Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
                }
            })
            .catch(error => {
                console.error('Error fetching address:', error);
                document.getElementById('tempat_tujuan').value = `Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;

            });
    }

    function deleteUser(idPendatang) {
        Swal.fire({
            title: 'Warning',
            text: "Apakah anda yang menghapus data Pendatang ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= site_url('Pendatang/delete/') ?>' + idPendatang;
            }
        });
    }

    function archiveUser(idPendatang) {
        const mainModalContent = document.querySelector('#modal-overlay > div > div');

        Swal.fire({
            heightAuto: false,
            customClass: {
                popup: 'swal-custom-popup',
                htmlContainer: 'swal-custom-html-container',
                title: 'text-2xl font-bold',
            },

            didOpen: () => {
                document.body.style.overflow = 'hidden';
                if (mainModalContent) mainModalContent.style.overflow = 'hidden';
            },
            didClose: () => {
                document.body.style.overflow = 'auto';
                if (mainModalContent) mainModalContent.style.overflow = 'auto';
            },

            title: '<i class="fas fa-archive text-orange-500"></i> Arsipkan Data Pendatang',
            html: `
            <p class="text-lg text-gray-600 mb-6">Anda akan menonaktifkan data ini. Silakan masukkan tanggal dan alasan keluar.</p>
            <div class="text-left space-y-4 px-2">
                <div>
                    <label for="swal-tanggal-keluar" class="block text-lg font-medium text-gray-700 mb-2">Tanggal Keluar <span class="text-red-500">*</span></label>
                    <input id="swal-tanggal-keluar" type="date" 
                           class="w-full px-4 py-3 text-lg text-gray-700 border border-gray-300 rounded-lg transition duration-150 ease-in-out 
                                  focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none">
                </div>
                <div>
                    <label for="swal-alasan-keluar" class="block text-lg font-medium text-gray-700 mb-2">Alasan Keluar <span class="text-red-500">*</span></label>
                    <textarea id="swal-alasan-keluar" 
                              rows="4"
                              class="w-full px-4 py-3 text-lg text-gray-700 border border-gray-300 rounded-lg transition duration-150 ease-in-out 
                                     focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none" 
                              placeholder="Contoh: Pindah ke luar kota karena pekerjaan." 
                              style="resize: vertical;"></textarea>
                </div>
            </div>
        `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Arsipkan!',
            cancelButtonText: 'Batal',
            preConfirm: () => {
                const tanggalKeluar = document.getElementById('swal-tanggal-keluar').value;
                const alasanKeluar = document.getElementById('swal-alasan-keluar').value;
                if (!tanggalKeluar) {
                    Swal.showValidationMessage('Tanggal keluar wajib diisi!');
                    return false;
                }
                if (!alasanKeluar.trim()) {
                    Swal.showValidationMessage('Alasan keluar wajib diisi!');
                    return false;
                }
                return {
                    tanggal: tanggalKeluar,
                    alasan: alasanKeluar
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Data sedang diarsipkan.',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => Swal.showLoading()
                });

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `<?= site_url('pendatang/archive/') ?>${idPendatang}`;

                const inputTanggal = document.createElement('input');
                inputTanggal.type = 'hidden';
                inputTanggal.name = 'tanggal_keluar';
                inputTanggal.value = result.value.tanggal;
                form.appendChild(inputTanggal);

                const inputAlasan = document.createElement('input');
                inputAlasan.type = 'hidden';
                inputAlasan.name = 'alasan_keluar';
                inputAlasan.value = result.value.alasan;
                form.appendChild(inputAlasan);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function reactivateUser(idPendatang) {
        const mainModalContent = document.querySelector('#modal-overlay > div > div');

        Swal.fire({
            didOpen: () => {
                if (mainModalContent) mainModalContent.style.overflow = 'hidden';
            },
            didClose: () => {
                if (mainModalContent) mainModalContent.style.overflow = 'auto';
            },

            title: 'Aktifkan Kembali?',
            text: "Anda yakin ingin mengaktifkan kembali data pendatang ini? Statusnya akan berubah menjadi 'Aktif'.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Aktifkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= site_url('Pendatang/reactivate/') ?>' + idPendatang;
            }
        });
    }
    async function editUser(idPendatang) {
        console.log('Editing user with ID:', idPendatang);

        Swal.fire({
            title: 'Memuat data...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        try {
            const response = await fetch(`<?= site_url('Pendatang/get/') ?>${idPendatang}`);
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('Received data:', data);

            if (data.status && data.status === 'error') {
                throw new Error(data.message || 'Data tidak ditemukan');
            }

            const pendatangData = data.data || data;

            document.getElementById('id_pendatang').value = pendatangData.idPendatang || '';
            document.getElementById('id_kaling').value = pendatangData.idKaling || '';
            document.getElementById('nik').value = pendatangData.NIK || '';
            document.getElementById('nama_lengkap').value = pendatangData.NamaLengkap || '';
            document.getElementById('alamat').value = pendatangData.Alamat || '';
            document.getElementById('telepon').value = pendatangData.Telepon || '';
            document.getElementById('tempat_lahir').value = pendatangData.TempatLahir || '';
            document.getElementById('tanggal_lahir').value = pendatangData.TanggalLahir || '';
            document.getElementById('tujuan').value = pendatangData.Tujuan || '';
            document.getElementById('tempat_tujuan').value = pendatangData.TempatTujuan || '';
            document.getElementById('tanggal_masuk').value = pendatangData.TanggalMasuk || '';
            document.getElementById('tanggal_keluar').value = pendatangData.TanggalKeluar || '';
            document.getElementById('jenis_kelamin').value = pendatangData.JenisKelamin || '';
            document.getElementById('golda').value = pendatangData.Golda || '';
            document.getElementById('agama').value = pendatangData.Agama || '';
            document.getElementById('rt').value = pendatangData.RT || '';
            document.getElementById('rw').value = pendatangData.RW || '';

            document.getElementById('latitude').value = pendatangData.Latitude || '';
            document.getElementById('longitude').value = pendatangData.Longitude || '';
            document.getElementById('latitude_hidden').value = pendatangData.Latitude || '';
            document.getElementById('longitude_hidden').value = pendatangData.Longitude || '';

            loadWilayahForEdit(pendatangData);
            document.getElementById('modal-overlay').classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                initializeMapForEdit(pendatangData.Latitude, pendatangData.Longitude);
            }, 300);

            await Swal.fire({
                icon: 'info',
                title: 'Perhatian',
                text: 'Silakan unggah ulang Foto Diri dan Foto KTP Anda.',
                confirmButtonText: 'Mengerti'
            });

            Swal.close();

            setTimeout(() => {
                const formElement = document.querySelector('.bg-white.shadow-xl');
                if (formElement) {
                    formElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }, 100);

        } catch (error) {
            console.error('Error in editUser:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal Memuat Data',
                text: 'Terjadi kesalahan: ' + error.message,
                confirmButtonText: 'OK'
            });
        }
    }


    function loadWilayahForEdit(pendatangData) {
        const provinsiSelect = document.getElementById("provinsi");

        if (pendatangData.Provinsi) {
            for (let option of provinsiSelect.options) {
                if (option.value === pendatangData.Provinsi) {
                    option.selected = true;
                    const provinsiId = option.getAttribute('data-id');

                    if (provinsiId) {
                        loadKabupatenForEdit(provinsiId, pendatangData.Kabupaten, pendatangData);
                    }
                    break;
                }
            }
        }
    }

    function loadKabupatenForEdit(provinsiId, selectedKabupaten, pendatangData) {
        const kabupatenSelect = document.getElementById("kabupaten");

        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinsiId}.json`)
            .then(res => res.json())
            .then(data => {
                kabupatenSelect.innerHTML = `<option value="">Pilih Kabupaten/Kota</option>`;

                data.forEach(kabupaten => {
                    const selected = kabupaten.name === selectedKabupaten ? 'selected' : '';
                    kabupatenSelect.innerHTML += `<option value="${kabupaten.name}" data-id="${kabupaten.id}" ${selected}>${kabupaten.name}</option>`;
                });

                if (selectedKabupaten) {
                    const selectedOption = kabupatenSelect.querySelector(`option[value="${selectedKabupaten}"]`);
                    if (selectedOption) {
                        const kabupatenId = selectedOption.getAttribute('data-id');
                        loadKecamatanForEdit(kabupatenId, pendatangData.Kecamatan, pendatangData);
                    }
                }
            })
            .catch(error => {
                console.error('Error loading kabupaten:', error);
            });
    }

    function loadKecamatanForEdit(kabupatenId, selectedKecamatan, pendatangData) {
        const kecamatanSelect = document.getElementById("kecamatan");

        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${kabupatenId}.json`)
            .then(res => res.json())
            .then(data => {
                kecamatanSelect.innerHTML = `<option value="">Pilih Kecamatan</option>`;

                data.forEach(kecamatan => {
                    const selected = kecamatan.name === selectedKecamatan ? 'selected' : '';
                    kecamatanSelect.innerHTML += `<option value="${kecamatan.name}" data-id="${kecamatan.id}" ${selected}>${kecamatan.name}</option>`;
                });

                if (selectedKecamatan) {
                    const selectedOption = kecamatanSelect.querySelector(`option[value="${selectedKecamatan}"]`);
                    if (selectedOption) {
                        const kecamatanId = selectedOption.getAttribute('data-id');
                        loadKelurahanForEdit(kecamatanId, pendatangData.Kelurahan);
                    }
                }
            })
            .catch(error => {
                console.error('Error loading kecamatan:', error);
            });
    }

    function loadKelurahanForEdit(kecamatanId, selectedKelurahan) {
        const kelurahanSelect = document.getElementById("kelurahan");

        fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${kecamatanId}.json`)
            .then(res => res.json())
            .then(data => {
                kelurahanSelect.innerHTML = `<option value="">Pilih Kelurahan</option>`;

                data.forEach(kelurahan => {
                    const selected = kelurahan.name === selectedKelurahan ? 'selected' : '';
                    kelurahanSelect.innerHTML += `<option value="${kelurahan.name}" data-id="${kelurahan.id}" ${selected}>${kelurahan.name}</option>`;
                });
            })
            .catch(error => {
                console.error('Error loading kelurahan:', error);
            });
    }

    function initializeMapForEdit(latitude, longitude) {
        if (typeof L === 'undefined') {
            console.error('Leaflet library not loaded');
            return;
        }

        if (map) {
            map.remove();
            map = null;
            marker = null;
        }

        try {
            map = L.map('map').setView([latitude || -8.40824, longitude || 115.18802], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            if (latitude && longitude) {
                marker = L.marker([parseFloat(latitude), parseFloat(longitude)]).addTo(map);
                map.setView([parseFloat(latitude), parseFloat(longitude)], 15);
            }

            map.on('click', function(e) {
                if (marker) {
                    map.removeLayer(marker);
                }

                marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);
                updateCoordinates(e.latlng.lat, e.latlng.lng);
                reverseGeocode(e.latlng.lat, e.latlng.lng);
            });

            console.log('Map initialized successfully for edit mode');

        } catch (error) {
            console.error('Map initialization error:', error);
        }
    }

    function verifikasiUser(idPendatang) {
        console.log('ID yang dikirim:', idPendatang);

        if (!idPendatang || idPendatang === '' || idPendatang === 'undefined') {
            Swal.fire({
                title: 'Error!',
                text: 'ID tidak valid!',
                icon: 'error'
            });
            return;
        }

        Swal.fire({
            title: 'Verifikasi Pendatang',
            text: 'Pilih tindakan yang ingin dilakukan:',
            icon: 'question',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonColor: '#10B981',
            denyButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: '<i class="fas fa-check"></i> Verifikasi',
            denyButtonText: '<i class="fas fa-times"></i> Tolak',
            cancelButtonText: '<i class="fas fa-ban"></i> Batal',
            reverseButtons: false,
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Konfirmasi Verifikasi',
                    text: 'Apakah Anda yakin ingin memverifikasi pendatang ini? Akun akan bisa login setelah diverifikasi.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#10B981',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, Verifikasi!',
                    cancelButtonText: 'Batal'
                }).then((confirmResult) => {
                    if (confirmResult.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses Verifikasi...',
                            text: 'Mohon tunggu, sedang memverifikasi pendatang',
                            icon: 'info',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        window.location.href = `<?= site_url('pendatang/verifikasi/') ?>${idPendatang}`;
                    }
                });
            } else if (result.isDenied) {
                Swal.fire({
                    title: 'Alasan Penolakan',
                    text: 'Masukkan alasan penolakan verifikasi:',
                    input: 'textarea',
                    inputPlaceholder: 'Contoh: Data tidak lengkap, dokumen tidak valid, dll...',
                    inputAttributes: {
                        'aria-label': 'Alasan penolakan',
                        'style': 'min-height: 100px; resize: vertical;'
                    },
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: '<i class="fas fa-times"></i> Tolak',
                    cancelButtonText: 'Batal',
                    inputValidator: (value) => {
                        if (!value || value.trim() === '') {
                            return 'Alasan penolakan wajib diisi!'
                        }
                        if (value.trim().length < 10) {
                            return 'Alasan penolakan minimal 10 karakter!'
                        }
                    },
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((rejectResult) => {
                    if (rejectResult.isConfirmed) {
                        const alasan = rejectResult.value.trim();

                        Swal.fire({
                            title: 'Konfirmasi Penolakan',
                            html: `Apakah Anda yakin ingin menolak verifikasi dengan alasan:<br><br><strong>"${alasan}"</strong>`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#EF4444',
                            cancelButtonColor: '#6B7280',
                            confirmButtonText: 'Ya, Tolak!',
                            cancelButtonText: 'Batal'
                        }).then((finalResult) => {
                            if (finalResult.isConfirmed) {
                                console.log('ID sebelum submit:', idPendatang);
                                console.log('Alasan:', alasan);

                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = `<?= site_url('pendatang/tolak/') ?>${idPendatang}`;
                                form.style.display = 'none';

                                const idInput = document.createElement('input');
                                idInput.type = 'hidden';
                                idInput.name = 'id';
                                idInput.value = idPendatang;
                                form.appendChild(idInput);

                                const alasanInput = document.createElement('input');
                                alasanInput.type = 'hidden';
                                alasanInput.name = 'alasan';
                                alasanInput.value = alasan;
                                form.appendChild(alasanInput);

                                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                                if (csrfMeta) {
                                    const csrfInput = document.createElement('input');
                                    csrfInput.type = 'hidden';
                                    csrfInput.name = 'csrf_token';
                                    csrfInput.value = csrfMeta.getAttribute('content');
                                    form.appendChild(csrfInput);
                                }

                                document.body.appendChild(form);

                                console.log('Form action:', form.action);
                                console.log('Form data:', new FormData(form));

                                form.submit();
                            }
                        });
                    }
                });
            }
        });
    }
</script>