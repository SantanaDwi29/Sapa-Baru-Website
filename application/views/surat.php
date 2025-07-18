<?php
$JenisAkun = $this->session->userdata('JenisAkun');
$isKaling = ($JenisAkun === 'Kepala Lingkungan');
?>

<div class="bg-white shadow-lg rounded-xl p-6 md:p-8">
    <div class="border-b border-gray-200 pb-5 mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 flex items-center mb-4 sm:mb-0">
            <i class="fa-solid fa-id-card text-indigo-950 mr-4 text-4xl"></i>
            Surat Pengantar Pendatang
        </h1>
        <?php if ($JenisAkun == "Admin" || $isKaling) { ?>
            <button onclick="openModal()"
                    class="px-5 py-2.5 bg-indigo-950 text-white font-semibold rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                <i class="fa-solid fa-plus-circle mr-2"></i>
                Tambah Tipe Surat
            </button>
        <?php } ?>
    </div>

    <form action="<?= site_url('Surat/save') ?>" method="POST" class="space-y-7" id="pengajuanSuratForm">
        <div>
            <label for="id_pendatang" class="block text-lg font-semibold text-gray-800 mb-2">
                Data Pendatang <span class="text-red-500">*</span>
            </label>
            <select name="id_pendatang" id="id_pendatang"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                    required
                    <?php if ($isKaling) echo 'disabled'; ?>>
                <option value="">Pilih Pendatang</option>
                <?php if(isset($Pendatang)) { foreach ($Pendatang as $p): ?>
                    <option value="<?= $p->idPendatang ?>"><?= $p->NamaLengkap ?></option>
                <?php endforeach; } ?>
            </select>
        </div>

        <div>
            <label for="id_surat" class="block text-lg font-semibold text-gray-800 mb-2">
                Tipe Surat <span class="text-red-500">*</span>
            </label>
            <select name="id_surat" id="id_surat"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100 disabled:cursor-not-allowed"
                    required
                    <?php if ($isKaling) echo 'disabled'; ?>>
                <option value="">Pilih Tipe Surat</option>
                 <?php if(isset($Keperluan)) { foreach ($Keperluan as $k) { ?>
                    <option value="<?= $k->idKeperluan ?>"><?= $k->NamaKeperluan ?></option>
                <?php } } ?>
            </select>
        </div>

        <div class="flex justify-end pt-5">
            <button type="submit"
                    class="inline-flex items-center px-7 py-3 text-lg font-bold bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:bg-gray-400 disabled:cursor-not-allowed"
                    <?php if ($isKaling) echo 'disabled'; ?>>
                <i class="fa-solid fa-paper-plane mr-2.5"></i>
                Ajukan Surat
            </button>
        </div>
    </form>
</div>

<div id="suratModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden opacity-0 transition-opacity">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-1/2 p-6 md:p-8 transform scale-95 transition-transform mx-auto my-8">
        <div class="flex justify-between items-center border-b pb-4 mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Tambah Tipe Surat Baru</h3>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-3xl">&times;</button>
        </div>
        <form id="addTypeSuratForm" class="space-y-5">
            <div>
                <label for="NamaKeperluan" class="block text-lg font-medium text-gray-700 mb-2">Nama Tipe Surat</label>
                <input type="text" id="NamaKeperluan" name="NamaKeperluan"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"
                       placeholder="Contoh: Surat Keterangan Domisili" required>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-gray-300 text-gray-800 font-semibold rounded-lg hover:bg-gray-400">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function verifikasiSurat(idPengajuan) {
        Swal.fire({
            title: 'Verifikasi Surat?',
            text: "Anda yakin ingin memverifikasi pengajuan ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Verifikasi!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `<?= site_url('Surat/verifikasi/') ?>${idPengajuan}`;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '<?= $this->security->get_csrf_token_name(); ?>';
                csrfInput.value = '<?= $this->security->get_csrf_hash(); ?>';
                form.appendChild(csrfInput);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function tolakSurat(idPengajuan) {
        Swal.fire({
            title: 'Tolak Pengajuan Surat',
            input: 'textarea',
            inputLabel: 'Alasan Penolakan',
            inputPlaceholder: 'Tuliskan alasan penolakan di sini...',
            showCancelButton: true,
            confirmButtonText: 'Tolak Surat',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
            preConfirm: (alasan) => {
                if (!alasan || alasan.trim() === '') {
                    Swal.showValidationMessage('Alasan penolakan wajib diisi!');
                }
                return alasan;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `<?= site_url('Surat/tolak/') ?>${idPengajuan}`;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '<?= $this->security->get_csrf_token_name(); ?>';
                csrfInput.value = '<?= $this->security->get_csrf_hash(); ?>';
                form.appendChild(csrfInput);

                const alasanInput = document.createElement('input');
                alasanInput.type = 'hidden';
                alasanInput.name = 'alasan_penolakan';
                alasanInput.value = result.value;
                form.appendChild(alasanInput);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function hapusPengajuan(idPengajuan) {
        Swal.fire({
            title: 'Hapus Pengajuan Ini?',
            text: "Data yang sudah dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${idPengajuan}`).submit();
            }
        });
    }
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('suratModal');
    const idSuratSelect = document.getElementById('id_surat');
    const pengajuanForm = document.getElementById('pengajuanSuratForm');
    const isKaling = "<?= $isKaling ? 'true' : 'false' ?>" === "true";

    if (isKaling) {
        pengajuanForm.addEventListener('click', function(event) {
            if (event.target.disabled) {
                Swal.fire({
                    icon: 'info',
                    title: 'Akses Dibatasi',
                    text: 'Kepala Lingkungan hanya dapat menambah tipe surat di halaman ini.',
                });
            }
        }, true);
    }

    window.openModal = function() {
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.remove('opacity-0'), 10);
    }
    window.closeModal = function() {
        modal.classList.add('opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    document.getElementById('addTypeSuratForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const namaKeperluan = document.getElementById('NamaKeperluan').value;
        if (!namaKeperluan.trim()) {
            Swal.fire('Perhatian!', 'Nama tipe surat tidak boleh kosong.', 'warning');
            return;
        }

        fetch('<?= site_url('Surat/add_keperluan') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
            body: 'NamaKeperluan=' + encodeURIComponent(namaKeperluan)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire('Berhasil!', data.message, 'success');
                closeModal();
                
                const newOption = new Option(data.name, data.id, false, true);
                idSuratSelect.appendChild(newOption);
            } else {
                Swal.fire('Gagal!', data.message, 'error');
            }
        });
    });
});
</script>