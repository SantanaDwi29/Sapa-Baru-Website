<?php
$JenisAkun = $this->session->userdata('JenisAkun');
?>

<div class="bg-white shadow-lg rounded-xl p-6 md:p-8">
    <div class="border-b border-gray-200 pb-5 mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 flex items-center mb-4 sm:mb-0">
            <i class="fa-solid fa-id-card text-indigo-950 mr-4 text-4xl"></i>
            Surat Pengantar Pendatang
        </h1>
        <?php if ($JenisAkun == "Admin" || $JenisAkun == "Kepala Lingkungan") { ?>
            <button onclick="openModal()"
                class="px-5 py-2.5 bg-indigo-950 text-white font-semibold rounded-lg shadow-mdfocus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300 ease-in-out">
                <i class="fa-solid fa-plus-circle mr-2"></i>
                Tambah Tipe Surat
            </button>
        <?php } ?>
    </div>

    <form action="<?= site_url('Surat/save') ?>" method="POST" class="space-y-7" id="pengajuanSuratForm">
        <input type="hidden" name="id_daftar" id="id_daftar" value="">

        <div>
            <label for="id_pendatang" class="block text-lg font-semibold text-gray-800 mb-2">
                Data Pendatang <span class="text-red-500">*</span>
            </label>
            <select name="id_pendatang" id="id_pendatang"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 ease-in-out"
                    required>
                <option value="">Pilih Pendatang</option>
                <?php foreach ($Pendatang as $p): ?>
                    <option value="<?= $p->idPendatang ?>"><?= $p->NamaLengkap ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="id_surat" class="block text-lg font-semibold text-gray-800 mb-2">
                Tipe Surat <span class="text-red-500">*</span>
            </label>
            <select name="id_surat" id="id_surat"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 ease-in-out"
                    required>
                <option value="">Pilih Tipe Surat</option>
                <?php foreach ($Keperluan as $k) { ?>
                    <option value="<?= $k->idKeperluan ?>"><?= $k->NamaKeperluan ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="flex justify-end pt-5">
            <button type="submit"
                    class="inline-flex items-center px-7 py-3 text-lg font-bold bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 ease-in-out">
                <i class="fa-solid fa-paper-plane mr-2.5"></i>
                Ajukan Surat
            </button>
        </div>
    </form>
</div>

<div id="suratModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden opacity-0 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-2/3 lg:w-1/2 p-6 md:p-8 transform scale-95 transition-transform duration-300 ease-in-out mx-auto my-8">
        <div class="flex justify-between items-center border-b pb-4 mb-6">
            <h3 class="text-2xl font-bold text-gray-800">Tambah Tipe Surat Baru</h3>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700 text-3xl leading-none">&times;</button>
        </div>
        <form id="addTypeSuratForm" class="space-y-5">
            <div>
                <label for="NamaKeperluan" class="block text-lg font-medium text-gray-700 mb-2">Nama Tipe Surat</label>
                <input type="text" id="NamaKeperluan" name="NamaKeperluan"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 ease-in-out"
                       placeholder="Contoh: Surat Keterangan Usaha" required>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeModal()"
                        class="px-5 py-2.5 bg-gray-300 text-gray-800 font-semibold rounded-lg shadow-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200 ease-in-out">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors duration-200 ease-in-out">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const modal = document.getElementById('suratModal');
    const idSuratSelect = document.getElementById('id_surat'); // Get the select element for Tipe Surat
    const pendatangSelect = document.getElementById('id_pendatang'); // Get the select element for Data Pendatang

    function openModal() {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('div').classList.remove('scale-95');
        }, 10);
    }

    function closeModal() {
        modal.querySelector('div').classList.add('scale-95');
        modal.classList.add('opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });

    // Handle form submission for adding new Tipe Surat (modal form)
    document.getElementById('addTypeSuratForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const namaKeperluan = document.getElementById('NamaKeperluan').value;

        if (namaKeperluan.trim() === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: 'Nama tipe surat tidak boleh kosong.',
            });
            return;
        }

        fetch('<?= site_url('Surat/add_keperluan') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'NamaKeperluan=' + encodeURIComponent(namaKeperluan)
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                closeModal();
                document.getElementById('NamaKeperluan').value = ''; // Clear the input field

                // Dynamically add the new option to the "Tipe Surat" select
                const newOption = document.createElement('option');
                newOption.value = data.id;
                newOption.textContent = data.name;
                idSuratSelect.appendChild(newOption);
                idSuratSelect.value = data.id; // Select the newly added type
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message,
                });
            }
        })
        .catch(error => {
            console.error('Error adding Tipe Surat:', error); // More specific error logging
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat menambahkan tipe surat. Cek konsol browser untuk detail.',
            });
        });
    });

    // Function to load "Tipe Surat" options when the page loads or after adding a new type
    function loadJenisSurat() {
        fetch('<?= site_url('Surat/get_keperluan_ajax') ?>')
            .then(response => {
                if (!response.ok) { // Check for HTTP errors
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Tipe Surat Data:', data); // Log the received data
                idSuratSelect.innerHTML = '<option value="">Pilih Tipe Surat</option>'; // Clear existing options
                data.forEach(keperluan => {
                    const option = document.createElement('option');
                    // Accessing properties with object syntax (assuming model returns result())
                    option.value = keperluan.idKeperluan;
                    option.textContent = keperluan.NamaKeperluan;
                    idSuratSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading Tipe Surat:', error); // Log the actual error
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memuat!',
                    text: 'Gagal memuat tipe surat. Silakan coba lagi atau hubungi administrator. Detail: ' + error.message,
                });
            });
    }

    // Function to load available "Data Pendatang" options
    function loadPendatang() {
        fetch('<?= site_url('Surat/get_available_pendatang_ajax') ?>')
            .then(response => {
                if (!response.ok) { // Check for HTTP errors
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Data Pendatang:', data); // Log the received data
                pendatangSelect.innerHTML = '<option value="">Pilih Pendatang</option>'; // Clear existing options
                data.forEach(pendatang => {
                    const option = document.createElement('option');
                    // Accessing properties with object syntax
                    option.value = pendatang.idPendatang;
                    option.textContent = pendatang.NamaLengkap;
                    pendatangSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading Data Pendatang:', error); // Log the actual error
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memuat!',
                    text: 'Gagal memuat data pendatang. Silakan coba lagi atau hubungi administrator. Detail: ' + error.message,
                });
            });
    }

    // Call load functions when the page loads
    document.addEventListener('DOMContentLoaded', () => {
        loadJenisSurat();
        loadPendatang();
        <?php if ($this->session->flashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= $this->session->flashdata('success') ?>',
                timer: 3000,
                showConfirmButton: false
            });
        <?php elseif ($this->session->flashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '<?= $this->session->flashdata('error') ?>',
            });
        <?php endif; ?>
    });
    function verifikasiSurat(idPengajuan) {
    Swal.fire({
        title: 'Verifikasi Surat Ini?',
        text: "Pastikan semua data pengajuan sudah benar sebelum diverifikasi.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10B981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Verifikasi!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Memproses...',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            window.location.href = `<?= site_url('Surat/verifikasi/') ?>${idPengajuan}`;
        }
    });
}
function tolakSurat(idPengajuan) {
    Swal.fire({
        title: 'Alasan Penolakan',
        input: 'textarea',
        inputPlaceholder: 'Tuliskan alasan mengapa pengajuan surat ini ditolak...',
        inputAttributes: {
            'aria-label': 'Tuliskan alasan penolakan di sini'
        },
        showCancelButton: true,
        confirmButtonText: 'Kirim Alasan Penolakan',
        confirmButtonColor: '#dc2626',
        cancelButtonText: 'Batal',
        inputValidator: (value) => {
            if (!value || value.trim().length < 10) {
                return 'Anda wajib mengisi alasan penolakan (minimal 10 karakter)!'
            }
        }
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            const alasan = result.value;
            Swal.fire({
                title: 'Memproses Penolakan...',
                icon: 'info',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            // Membuat dan mengirim form secara dinamis
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `<?= site_url('Surat/tolak/') ?>${idPengajuan}`;

            const hiddenAlasan = document.createElement('input');
            hiddenAlasan.type = 'hidden';
            hiddenAlasan.name = 'alasan_penolakan';
            hiddenAlasan.value = alasan;
            form.appendChild(hiddenAlasan);

            const csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
            const csrfHash = '<?= $this->security->get_csrf_hash(); ?>';
            const hiddenCsrf = document.createElement('input');
            hiddenCsrf.type = 'hidden';
            hiddenCsrf.name = csrfName;
            hiddenCsrf.value = csrfHash;
            form.appendChild(hiddenCsrf);

            document.body.appendChild(form);
            form.submit();
        }
    });
}
function hapusPengajuan(idPengajuan) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data pengajuan yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit form hapus yang tersembunyi
            document.getElementById(`delete-form-${idPengajuan}`).submit();
        }
    });
}
</script>