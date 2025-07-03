
    <div class="bg-white shadow-xl rounded-lg mb-10">
        <div class="p-6">
            <div class="border-b pb-4">
                <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-user-shield text-indigo-950 mr-3"></i>
                    <span id="form-title-pj">Form Tambah Data Penanggung Jawab</span>
                </h1>
            </div>
            <form id="pj-form" action="<?= site_url('PenanggungJawab/save') ?>" method="POST">
                <input type="hidden" name="id_daftar" id="id_daftar_pj" value="">

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div class="space-y-1">
                        <label for="namaLengkap_pj" class="block text-lg font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="namaLengkap" id="namaLengkap_pj" required
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan nama lengkap">
                    </div>

                    <div class="space-y-1">
                        <label for="nik_pj" class="block text-lg font-medium text-gray-700">NIK <span class="text-red-500">*</span></label>
                        <input type="text" name="nik" id="nik_pj" required maxlength="16"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan 16 digit NIK"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>

                    <div class="space-y-1">
                        <label for="telp_pj" class="block text-lg font-medium text-gray-700">No Telepon <span class="text-red-500">*</span></label>
                        <input type="tel" name="telp" id="telp_pj" required maxlength="13"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Contoh: 081234567890"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>

                    <div class="space-y-1">
                        <label for="email_pj" class="block text-lg font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email_pj" required
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan email yang valid">
                    </div>
                    
                    <div class="md:col-span-2 space-y-1">
                        <label for="alamat_pj" class="block text-lg font-medium text-gray-700">Alamat <span class="text-red-500">*</span></label>
                        <textarea name="alamat" id="alamat_pj" required rows="3"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Masukkan alamat lengkap"></textarea>
                    </div>

                    <div class="space-y-1">
                        <label for="password_pj" class="block text-lg font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password_pj"
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Isi untuk mengubah password">
                         <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password.</p>
                    </div>

                    <div class="space-y-1">
                        <label for="jenisAkun_pj" class="block text-lg font-medium text-gray-700">Jenis Akun</label>
                        <input type="text" name="jenisAkun" id="jenisAkun_pj" value="Penanggung Jawab" readonly
                            class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-lg bg-gray-100 cursor-not-allowed">
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-8">
                    <button type="button" id="reset-btn-pj"
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
        // Fungsi Global untuk Edit dan Delete
        function editUserPJ(id) {
            document.getElementById('form-title-pj').textContent = 'Form Edit Data Penanggung Jawab';

            fetch(`<?= site_url('PenanggungJawab/get/') ?>${id}`)
                .then(response => {
                    if (!response.ok) throw new Error('Data tidak ditemukan di server.');
                    return response.json();
                })
                .then(data => {
                    document.getElementById('id_daftar_pj').value = data.id_daftar;
                    document.getElementById('namaLengkap_pj').value = data.NamaPJ;
                    document.getElementById('nik_pj').value = data.NIK;
                    document.getElementById('telp_pj').value = data.Telp;
                    document.getElementById('email_pj').value = data.Email;
                    document.getElementById('alamat_pj').value = data.Alamat;
                    document.getElementById('jenisAkun_pj').value = data.JenisAkun;
                    document.getElementById('password_pj').value = '';

                    document.getElementById('pj-form').scrollIntoView({ behavior: 'smooth', block: 'start' });

                    Swal.fire({
                        icon: 'success',
                        title: 'Mode Edit Aktif',
                        text: 'Silakan ubah data yang diinginkan.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                })
                .catch(error => {
                    console.error('Error fetching PJ data:', error);
                    Swal.fire({ icon: 'error', title: 'Gagal Memuat Data', text: error.message });
                });
        }

        function deleteUserPJ(id) {
            Swal.fire({
                title: 'Anda Yakin?',
                text: "Data penanggung jawab ini akan dihapus secara permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `<?= site_url('PenanggungJawab/delete/') ?>${id}`;
                }
            });
        }

        // DOMContentLoaded untuk semua interaksi lainnya
        document.addEventListener('DOMContentLoaded', function () {
            // Kontrol untuk Tabel
            const tableBody = document.getElementById('tableBody-pj');
            if (tableBody) {
                const entriesSelect = document.getElementById('entries-select-pj');
                const statusFilter = document.getElementById('statusFilter-pj');
                const searchInput = document.getElementById('searchInput-pj');
                const entriesInfo = document.getElementById('entries-info-pj');
                const paginationButtons = document.getElementById('pagination-buttons-pj');
                const allRows = Array.from(tableBody.querySelectorAll('tr'));
                
                let currentPage = 1;
                let entriesPerPage = parseInt(entriesSelect.value, 10);

                const renderTable = () => {
                    const searchTerm = searchInput.value.toLowerCase();
                    const statusValue = statusFilter.value;

                    const filteredRows = allRows.filter(row => {
                        const namaCell = row.querySelector('[data-label="Nama Lengkap"]');
                        const nikCell = row.querySelector('[data-label="NIK"]');
                        const statusCell = row.querySelector('[data-label="Status"] span');
                        
                        const nama = namaCell ? namaCell.textContent.toLowerCase() : '';
                        const nik = nikCell ? nikCell.textContent.toLowerCase() : '';
                        const status = statusCell ? statusCell.textContent.trim() : '';

                        return (nama.includes(searchTerm) || nik.includes(searchTerm)) && (statusValue === "" || status === statusValue);
                    });

                    allRows.forEach(row => row.style.display = 'none');
                    const totalEntries = filteredRows.length;
                    const totalPages = Math.ceil(totalEntries / entriesPerPage) || 1;
                    currentPage = Math.min(currentPage, totalPages) || 1;
                    const startIndex = (currentPage - 1) * entriesPerPage;
                    const endIndex = startIndex + entriesPerPage;
                    
                    filteredRows.slice(startIndex, endIndex).forEach(row => row.style.display = '');

                    entriesInfo.textContent = `Showing ${totalEntries > 0 ? startIndex + 1 : 0} to ${Math.min(endIndex, totalEntries)} of ${totalEntries} entries`;
                    renderPaginationButtons(totalPages);
                };

                const renderPaginationButtons = (totalPages) => {
                    paginationButtons.innerHTML = '';
                    // Previous Button
                    const prevBtn = Object.assign(document.createElement('button'), {
                        textContent: 'Previous',
                        className: 'px-3 py-1 border border-gray-300 rounded hover:bg-gray-100 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-400',
                        disabled: currentPage === 1,
                        onclick: () => { currentPage--; renderTable(); }
                    });
                    paginationButtons.appendChild(prevBtn);
                    for (let i = 1; i <= totalPages; i++) {
                        const pageBtn = Object.assign(document.createElement('button'), {
                            textContent: i,
                            className: `px-3 py-1 border rounded ${i === currentPage ? 'bg-blue-500 text-white border-blue-500' : 'border-gray-300 hover:bg-gray-100'}`,
                            onclick: () => { currentPage = i; renderTable(); }
                        });
                        if (i === currentPage) pageBtn.onclick = null;
                        paginationButtons.appendChild(pageBtn);
                    }
                    const nextBtn = Object.assign(document.createElement('button'), {
                        textContent: 'Next',
                        className: 'px-3 py-1 border border-gray-300 rounded hover:bg-gray-100 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-400',
                        disabled: currentPage === totalPages || totalPages === 0,
                        onclick: () => { currentPage++; renderTable(); }
                    });
                    paginationButtons.appendChild(nextBtn);
                };
                
                entriesSelect.addEventListener('change', () => { entriesPerPage = parseInt(entriesSelect.value); currentPage = 1; renderTable(); });
                searchInput.addEventListener('input', () => { currentPage = 1; renderTable(); });
                statusFilter.addEventListener('change', () => { currentPage = 1; renderTable(); });

                renderTable();
            }

            const form = document.getElementById('pj-form');
            const resetButton = document.getElementById('reset-btn-pj');
            if (form && resetButton) {
                resetButton.addEventListener('click', () => {
                    form.reset();
                    document.getElementById('id_daftar_pj').value = '';
                    document.getElementById('form-title-pj').textContent = 'Form Tambah Data Penanggung Jawab';
                    Swal.fire({ icon: 'info', title: 'Form Direset', timer: 1500, showConfirmButton: false });
                });
            }
        });
    </script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
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

            // Ambil teks hanya jika sel ditemukan, untuk menghindari error
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
    
    entriesSelect.addEventListener('change', function () {
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