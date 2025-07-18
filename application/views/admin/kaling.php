<?php
$JenisAkun = $this->session->userdata('JenisAkun');
if ($JenisAkun == 'Admin') {
?>
    <div class="bg-white shadow-xl rounded-lg mb-10">
        <div class="p-6">
            <div class="border-b pb-4">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-user-edit text-indigo-950 mr-3"></i>
                    <span id="form-title">Form Tambah Data Kepala Lingkungan</span>
                </h1>
            </div>
            <form id="kaling-form" action="<?= site_url('Kaling/save') ?>" method="POST">
                <input type="hidden" name="id_daftar" id="id_daftar" value="">

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div class="space-y-1">
                        <label for="namaLengkap" class="block text-lg font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="namaLengkap" id="namaLengkap" required
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan nama lengkap">
                    </div>

                    <div class="space-y-1">
                        <label for="nik" class="block text-lg font-medium text-gray-700">NIK <span class="text-red-500">*</span></label>
                        <input type="text" name="nik" id="nik" required maxlength="16"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan 16 digit NIK"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>

                    <div class="space-y-1">
                        <label for="telp" class="block text-lg font-medium text-gray-700">No Telepon <span class="text-red-500">*</span></label>
                        <input type="tel" name="telp" id="telp" required maxlength="13"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Contoh: 081234567890"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>

                    <div class="space-y-1">
                        <label for="email" class="block text-lg font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" required
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan email yang valid">
                    </div>

                    <div class="md:col-span-2 space-y-1">
                        <label for="alamat" class="block text-lg font-medium text-gray-700">Alamat <span class="text-red-500">*</span></label>
                        <textarea name="alamat" id="alamat" required rows="3"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan alamat lengkap"></textarea>
                    </div>

                    <div class="space-y-1">
                        <label for="password" class="block text-lg font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Isi untuk mengubah password">
                        <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password.</p>
                    </div>

                    <div class="space-y-1">
                        <label for="jenisAkun" class="block text-lg font-medium text-gray-700">Jenis Akun</label>
                        <input type="text" name="jenisAkun" id="jenisAkun" value="Kepala Lingkungan" readonly
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg bg-gray-100 cursor-not-allowed">
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-8">
                    <button type="button" id="reset-btn"
                        class="px-6 py-2.5 text-lg bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 text-lg bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editUser(id) {
            document.getElementById('form-title').textContent = 'Form Edit Data Kepala Lingkungan';

            fetch(`<?= site_url('Kaling/get/') ?>${id}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('id_daftar').value = data.id_daftar;
                    document.getElementById('namaLengkap').value = data.NamaKaling;
                    document.getElementById('nik').value = data.NIK;
                    document.getElementById('telp').value = data.Telp;
                    document.getElementById('email').value = data.Email;
                    document.getElementById('alamat').value = data.Alamat;
                    document.getElementById('jenisAkun').value = data.JenisAkun;
                    document.getElementById('password').value = '';

                    document.getElementById('kaling-form').scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });

                    Swal.fire({
                        icon: 'success',
                        title: 'Mode Edit Aktif',
                        text: 'Silakan ubah data yang diinginkan.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                })
                .catch(error => {
                    console.error('Error fetching user data:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Memuat Data',
                        text: 'Terjadi kesalahan saat mengambil data kepala.'
                    });
                });
        }

        function deleteUser(id) {
            Swal.fire({
                title: 'Anda Yakin?',
                text: "Data kepala lingkungan ini akan dihapus secara permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= site_url('Kaling/delete/') ?>' + id;
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const entriesSelect = document.getElementById('entries-select');
            const statusFilter = document.getElementById('statusFilter');
            const searchInput = document.getElementById('searchInput');
            const tableBody = document.getElementById('tableBody');
            const entriesInfo = document.getElementById('entries-info');
            const paginationButtons = document.getElementById('pagination-buttons');

            if (!tableBody) {
                console.log("Elemen tabel tidak ditemukan, script filter tidak dijalankan.");
                return;
            }

            const allRows = Array.from(tableBody.querySelectorAll('tr'));
            let currentPage = 1;
            let entriesPerPage = parseInt(entriesSelect.value, 10);

            function renderTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value;

                const filteredRows = allRows.filter(row => {
                    const namaCell = row.querySelector('[data-label="Nama Lengkap"]');
                    const nikCell = row.querySelector('[data-label="NIK"]');
                    const statusCell = row.querySelector('[data-label="Status"] span');

                    const nama = namaCell ? namaCell.textContent.toLowerCase() : '';
                    const nik = nikCell ? nikCell.textContent.toLowerCase() : '';
                    const status = statusCell ? statusCell.textContent.trim() : '';

                    const matchesSearch = nama.includes(searchTerm) || nik.includes(searchTerm);
                    const matchesStatus = statusValue === "" || status === statusValue;

                    return matchesSearch && matchesStatus;
                });

                allRows.forEach(row => row.style.display = 'none');

                const totalEntries = filteredRows.length;
                const totalPages = Math.ceil(totalEntries / entriesPerPage) || 1;
                currentPage = Math.min(currentPage, totalPages) || 1;

                const startIndex = (currentPage - 1) * entriesPerPage;
                const endIndex = startIndex + entriesPerPage;

                const rowsToShow = filteredRows.slice(startIndex, endIndex);
                rowsToShow.forEach(row => row.style.display = '');

                if (entriesInfo) {
                    const startEntry = totalEntries > 0 ? startIndex + 1 : 0;
                    const endEntry = Math.min(endIndex, totalEntries);
                    entriesInfo.textContent = `Showing ${startEntry} to ${endEntry} of ${totalEntries} entries`;
                }

                if (paginationButtons) {
                    renderPaginationButtons(totalPages);
                }
            }

            function renderPaginationButtons(totalPages) {
                paginationButtons.innerHTML = '';

                const prevButton = document.createElement('button');
                prevButton.textContent = 'Previous';
                prevButton.className = 'px-3 py-1 border border-gray-300 rounded text-gray-700 hover:bg-gray-100 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-400';
                prevButton.disabled = currentPage === 1;
                prevButton.addEventListener('click', () => {
                    if (currentPage > 1) {
                        currentPage--;
                        renderTable();
                    }
                });
                paginationButtons.appendChild(prevButton);

                for (let i = 1; i <= totalPages; i++) {
                    const pageButton = document.createElement('button');
                    pageButton.textContent = i;
                    if (i === currentPage) {
                        pageButton.className = 'px-3 py-1 border border-blue-500 rounded bg-blue-500 text-white';
                    } else {
                        pageButton.className = 'px-3 py-1 border border-gray-300 rounded hover:bg-gray-100';
                        pageButton.addEventListener('click', () => {
                            currentPage = i;
                            renderTable();
                        });
                    }
                    paginationButtons.appendChild(pageButton);
                }

                const nextButton = document.createElement('button');
                nextButton.textContent = 'Next';
                nextButton.className = 'px-3 py-1 border border-gray-300 rounded text-gray-700 hover:bg-gray-100 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-400';
                nextButton.disabled = currentPage === totalPages || totalPages === 0;
                nextButton.addEventListener('click', () => {
                    if (currentPage < totalPages) {
                        currentPage++;
                        renderTable();
                    }
                });
                paginationButtons.appendChild(nextButton);
            }

            entriesSelect.addEventListener('change', () => {
                currentPage = 1;
                renderTable();
            });
            searchInput.addEventListener('input', () => {
                currentPage = 1;
                renderTable();
            });
            statusFilter.addEventListener('change', () => {
                currentPage = 1;
                renderTable();
            });


            renderTable();

            const form = document.getElementById('kaling-form');
            const resetButton = document.getElementById('reset-btn');

            resetButton.addEventListener('click', () => {
                form.reset();
                document.getElementById('id_daftar').value = '';
                document.getElementById('form-title').textContent = 'Form Tambah Data Kepala Lingkungan';
                Swal.fire({
                    icon: 'info',
                    title: 'Form Direset',
                    text: 'Anda dapat menambahkan data baru.',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const entriesSelect = document.getElementById('entries-select');
            const statusFilter = document.getElementById('statusFilter');
            const searchInput = document.getElementById('searchInput');
            const tableBody = document.getElementById('tableBody');
            const allRows = Array.from(tableBody.querySelectorAll('tr'));
            const entriesInfo = document.getElementById('entries-info');
            const paginationButtons = document.getElementById('pagination-buttons');

            let currentPage = 1;
            let entriesPerPage = parseInt(entriesSelect.value, 10);

            function renderTable() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value;



                const filteredRows = allRows.filter(row => {
                    const namaCell = row.querySelector('[data-label="Nama Lengkap"]');
                    const nikCell = row.querySelector('[data-label="NIK"]');
                    const statusCell = row.querySelector('[data-label="Status"] span');


                    const nama = namaCell ? namaCell.textContent.toLowerCase() : '';
                    const nik = nikCell ? nikCell.textContent.toLowerCase() : '';
                    const status = statusCell ? statusCell.textContent.trim() : '';

                    const matchesSearch = nama.includes(searchTerm) || nik.includes(searchTerm);
                    const matchesStatus = statusValue === "" || status === statusValue;

                    return matchesSearch && matchesStatus;
                });


                allRows.forEach(row => row.style.display = 'none');


                const totalEntries = filteredRows.length;
                const totalPages = Math.ceil(totalEntries / entriesPerPage);
                currentPage = Math.min(currentPage, totalPages) || 1;

                const startIndex = (currentPage - 1) * entriesPerPage;
                const endIndex = startIndex + entriesPerPage;

                const rowsToShow = filteredRows.slice(startIndex, endIndex);
                rowsToShow.forEach(row => row.style.display = '');

                const startEntry = totalEntries > 0 ? startIndex + 1 : 0;
                const endEntry = Math.min(endIndex, totalEntries);
                entriesInfo.textContent = `Showing ${startEntry} to ${endEntry} of ${totalEntries} entries`;

                renderPaginationButtons(totalPages);
            }

            function renderPaginationButtons(totalPages) {
                paginationButtons.innerHTML = '';
                const prevButton = document.createElement('button');
                prevButton.textContent = 'Previous';
                prevButton.className = 'px-3 py-1 border border-gray-300 rounded';
                if (currentPage === 1) {
                    prevButton.classList.add('bg-gray-100', 'text-gray-400', 'cursor-not-allowed');
                    prevButton.disabled = true;
                } else {
                    prevButton.classList.add('hover:bg-gray-100');
                    prevButton.addEventListener('click', () => {
                        currentPage--;
                        renderTable();
                    });
                }
                paginationButtons.appendChild(prevButton);

                for (let i = 1; i <= totalPages; i++) {
                    const pageButton = document.createElement('button');
                    pageButton.textContent = i;
                    pageButton.className = 'px-3 py-1 border border-gray-300 rounded';
                    if (i === currentPage) {
                        pageButton.classList.add('bg-blue-500', 'text-white', 'border-blue-500');
                    } else {
                        pageButton.classList.add('hover:bg-gray-100');
                        pageButton.addEventListener('click', () => {
                            currentPage = i;
                            renderTable();
                        });
                    }
                    paginationButtons.appendChild(pageButton);
                }

                const nextButton = document.createElement('button');
                nextButton.textContent = 'Next';
                nextButton.className = 'px-3 py-1 border border-gray-300 rounded';
                if (currentPage === totalPages || totalPages === 0) {
                    nextButton.classList.add('bg-gray-100', 'text-gray-400', 'cursor-not-allowed');
                    nextButton.disabled = true;
                } else {
                    nextButton.classList.add('hover:bg-gray-100');
                    nextButton.addEventListener('click', () => {
                        currentPage++;
                        renderTable();
                    });
                }
                paginationButtons.appendChild(nextButton);
            }

            entriesSelect.addEventListener('change', function() {
                entriesPerPage = parseInt(this.value, 10);
                currentPage = 1;
                renderTable();
            });

            searchInput.addEventListener('input', () => {
                currentPage = 1;
                renderTable();
            });

            statusFilter.addEventListener('change', () => {
                currentPage = 1;
                renderTable();
            });

            renderTable();
        });
    </script>

<?php
}
?>