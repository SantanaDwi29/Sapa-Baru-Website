<div class="bg-white shadow-md rounded-lg">
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <i class="fa-solid fa-user-shield text-indigo-950 mr-3"></i>
            Verifikasi Akun
        </h1>
        <div class="flex items-center gap-3 w-full lg:w-auto">
            <div class="relative w-full lg:w-64">
                <input type="text" id="searchInput"
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-lg"
                    placeholder="Cari nama...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full table-auto" id="verifikasiTabel">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left text-xl">No</th>
                        <th class="py-3 px-6 text-left text-xl">NIK</th>
                        <th class="py-3 px-6 text-left text-xl">Nama Lengkap</th>
                        <th class="py-3 px-6 text-left text-xl">No Telepon</th>
                        <th class="py-3 px-6 text-left text-xl">Jenis Akun</th>
                        <th class="py-3 px-6 text-left text-xl">Status</th>
                        <th class="py-3 px-6 text-left text-xl">Alasan</th>
                        <th class="py-3 px-6 text-center text-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    <?php
                    $no = 1;
                    foreach ($verifikasi as $item):
                        if ($item->StatusAktivasi == 'Aktif') continue;
                    ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left whitespace-nowrap text-lg"><?= $no++ ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 text-lg"><?= htmlspecialchars($item->NIK) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 text-lg"><?= htmlspecialchars($item->NamaLengkap) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 text-lg"><?= htmlspecialchars($item->Telp) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 font-semibold rounded-full text-lg
                                    <?= $item->JenisAkun == 'Kepala Lingkungan' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' ?>">
                                    <?= htmlspecialchars($item->JenisAkun) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 font-semibold rounded-full text-lg
                                    <?= $item->StatusAktivasi == 'Aktif' ? 'bg-green-100 text-green-800' : ($item->StatusAktivasi == 'Ditolak' || $item->StatusAktivasi == 'Tolak' ? 'bg-red-100 text-red-800' : ' bg-yellow-100 text-yellow-800') ?>">
                                    <?= htmlspecialchars($item->StatusAktivasi) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 text-lg"><?= htmlspecialchars($item->Alasan) ?></td>

                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center items-center space-x-3">
                                    <?php if ($item->StatusAktivasi != 'Aktif' && $item->StatusAktivasi != 'Ditolak' && $item->StatusAktivasi != 'Tolak'): ?>
                                        <button onclick="showTolakModal(<?= $item->idDaftar ?>, '<?= htmlspecialchars($item->NamaLengkap) ?>')"
                                            class="text-red-600 hover:text-red-800 transition-colors"
                                            title="Tolak">
                                            <i class="fa-regular fa-circle-xmark text-3xl"></i>
                                        </button>
                                        <button onclick="confirmVerifikasi(<?= $item->idDaftar ?>)"
                                            class="text-green-600 hover:text-green-800 transition-colors"
                                            title="Verifikasi">
                                            <i class="fa-regular fa-circle-check text-3xl"></i>
                                    </button>
                                    <?php elseif ($item->StatusAktivasi == 'Ditolak' || $item->StatusAktivasi == 'Tolak'): ?>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-gray-400 flex items-center">
                                                <i class="fas fa-ban text-2xl mr-1"></i>
                                                <span class="text-lg">Ditolak</span>
                                            </span>
                                            <button onclick="confirmReset(<?= $item->idDaftar ?>)"
                                                class="text-blue-600 hover:text-blue-800 transition-colors ml-2"
                                                title="Reset ke Pending">
                                                <i class="fas fa-redo text-xl"></i>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="tolakModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-xl leading-6 font-medium text-gray-900 mt-4">Konfirmasi Penolakan</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-lg text-gray-500" id="tolakMessage">
                    Apakah Anda yakin ingin menolak akun ini?
                </p>
                <div class="mt-4">
                    <label for="alasanTolak" class="block text-lg font-medium text-gray-700 text-left mb-2">
                        Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="alasanTolak"
                        rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="Masukkan alasan penolakan..."
                        required></textarea>
                    <p id="alasanError" class="text-red-500 text-sm mt-1 hidden">Alasan penolakan wajib diisi!</p>
                </div>
            </div>
            <div class="items-center px-4 py-3">
                <button
                    id="confirmTolakBtn"
                    class="px-4 py-2 bg-red-500 text-white text-lg font-medium rounded-md w-full shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300 mb-2">
                    Ya, Tolak!
                </button>
                <button
                    onclick="closeTolakModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-800 text-lgfont-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->

<script>
    let currentTolakId = null;

    function confirmVerifikasi(id) {
        Swal.fire({
            title: 'Konfirmasi Verifikasi',
            text: 'Apakah Anda yakin ingin memverifikasi akun ini? Akun akan bisa login setelah diverifikasi.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10B981',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ya, Verifikasi!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= site_url('verifikasi/verifikasi/') ?>' + id;
            }
        });
    }

    function showTolakModal(id, nama) {
        currentTolakId = id;
        document.getElementById('tolakMessage').textContent = `Apakah Anda yakin ingin menolak akun atas nama ${nama}?`;
        document.getElementById('alasanTolak').value = '';
        document.getElementById('alasanError').classList.add('hidden');
        document.getElementById('tolakModal').classList.remove('hidden');
    }

    function closeTolakModal() {
        document.getElementById('tolakModal').classList.add('hidden');
        currentTolakId = null;
    }

    document.getElementById('confirmTolakBtn').addEventListener('click', function() {
        const alasan = document.getElementById('alasanTolak').value.trim();

        if (!alasan) {
            document.getElementById('alasanError').classList.remove('hidden');
            return;
        }

        document.getElementById('alasanError').classList.add('hidden');

        this.disabled = true;
        this.textContent = 'Memproses...';

        fetch('<?= site_url('verifikasi/tolak') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${currentTolakId}&alasan=${encodeURIComponent(alasan)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: data.message,
                        icon: 'success',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Gagal!',
                        text: data.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Terjadi kesalahan sistem',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            })
            .finally(() => {
                this.disabled = false;
                this.textContent = 'Ya, Tolak!';
                closeTolakModal();
            });
    });

    function confirmReset(id) {
        Swal.fire({
            title: 'Konfirmasi Reset',
            text: 'Apakah Anda yakin ingin mereset status akun ini kembali ke Pending?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3B82F6',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ya, Reset!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= site_url('verifikasi/reset/') ?>' + id;
            }
        });
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeTolakModal();
        }
    });

    document.getElementById('tolakModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeTolakModal();
        }
    });

    <?php if (isset($success) && !empty($success)): ?>
        Swal.fire({
            title: 'Berhasil!',
            text: '<?= $success ?>',
            icon: 'success',
            timer: 4000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    <?php endif; ?>

    <?php if (isset($error) && !empty($error)): ?>
        Swal.fire({
            title: 'Gagal!',
            text: '<?= $error ?>',
            icon: 'error',
            timer: 4000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    <?php endif; ?>
</script>

<style>
    .fa-redo:hover {
        transform: rotate(180deg);
        transition: transform 0.3s ease;
    }

    .px-2.py-1 {
        transition: all 0.2s ease;
    }

    .px-2.py-1:hover {
        transform: scale(1.05);
    }

    #tolakModal {
        transition: opacity 0.3s ease;
    }

    #tolakModal.hidden {
        opacity: 0;
        pointer-events: none;
    }

    #tolakModal:not(.hidden) {
        opacity: 1;
        pointer-events: auto;
    }
</style>