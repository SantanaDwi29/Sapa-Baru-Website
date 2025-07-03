<div class="bg-white shadow-md rounded-lg">
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <i class="fa-solid fa-file-invoice text-indigo-950 mr-3"></i>
            Pusat Laporan
        </h1>
    </div>

    <div class="p-6">
        <div class="">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left text-lg">No</th>
                        <th class="py-3 px-6 text-left text-lg">Jenis Laporan</th>
                        <th class="py-3 px-6 text-left text-lg">Deskripsi</th>
                        <th class="py-3 px-6 text-center text-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-4 px-6 text-lg">1</td>
                        <td class="py-4 px-6 font-semibold text-lg">Laporan Data Pendatang Aktif</td>
                        <td class="py-4 px-6 text-lg">Daftar lengkap seluruh pendatang baru yang masih aktif.</td>
                        <td class="py-4 px-6 text-center">
                            <div class="relative inline-block text-left">
                                <button type="button" class="report-dropdown-toggle inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50">
                                    Pilih Export
                                    <i class="fa-solid fa-chevron-down -mr-1 ml-2 h-5 w-5"></i>
                                </button>
                                <div class="dropdown-menu hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20">
                                    <div class="py-1">
                                        <a href="<?= base_url('laporan/pendatang/pdf') ?>" class="flex items-center px-4 py-2 text-base text-gray-700 hover:bg-gray-100"><i class="fa-solid fa-file-pdf w-5 h-5 mr-3 text-red-600"></i> Export PDF</a>
                                        <a href="<?= base_url('laporan/pendatang/excel') ?>" class="flex items-center px-4 py-2 text-base text-gray-700 hover:bg-gray-100"><i class="fa-solid fa-file-excel w-5 h-5 mr-3 text-green-600"></i> Export Excel</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-4 px-6 text-lg">2</td>
                        <td class="py-4 px-6 font-semibold text-lg">Log Pengajuan Surat</td>
                        <td class="py-4 px-6 text-lg">Melihat riwayat semua surat keterangan yang telah diajukan.</td>
                        <td class="py-4 px-6 text-center">
                            <div class="relative inline-block text-left">
                                <button type="button" class="report-dropdown-toggle inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50">
                                    Pilih Aksi
                                    <i class="fa-solid fa-chevron-down -mr-1 ml-2 h-5 w-5"></i>
                                </button>
                                <div class="dropdown-menu hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20">
                                    <div class="py-1">
                                        <a href="<?= base_url('laporan/view_log_surat') ?>" class="flex items-center px-4 py-2 text-base text-gray-700 hover:bg-gray-100"><i class="fa-solid fa-display w-5 h-5 mr-3 text-sky-600"></i> Lihat di Layar</a>
                                        <a href="<?= base_url('laporan/log/pdf') ?>" class="flex items-center px-4 py-2 text-base text-gray-700 hover:bg-gray-100"><i class="fa-solid fa-file-pdf w-5 h-5 mr-3 text-red-600"></i> Export PDF</a>
                                        <a href="<?= base_url('laporan/log/excel') ?>" class="flex items-center px-4 py-2 text-base text-gray-700 hover:bg-gray-100"><i class="fa-solid fa-file-excel w-5 h-5 mr-3 text-green-600"></i> Export Excel</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-4 px-6 text-lg">3</td>
                        <td class="py-4 px-6 font-semibold text-lg">Laporan Arsip Pendatang</td>
                        <td class="py-4 px-6 text-lg">Daftar pendatang yang sudah tidak aktif atau pindah.</td>
                        <td class="py-4 px-6 text-center">
                            <div class="relative inline-block text-left">
                                <button type="button" class="report-dropdown-toggle inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50">
                                    Pilih Export
                                    <i class="fa-solid fa-chevron-down -mr-1 ml-2 h-5 w-5"></i>
                                </button>
                                <div class="dropdown-menu hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20">
                                    <div class="py-1">
                                        <a href="<?= base_url('laporan/arsip/pdf') ?>" class="flex items-center px-4 py-2 text-base text-gray-700 hover:bg-gray-100"><i class="fa-solid fa-file-pdf w-5 h-5 mr-3 text-red-600"></i> Export PDF</a>
                                        <a href="<?= base_url('laporan/arsip/excel') ?>" class="flex items-center px-4 py-2 text-base text-gray-700 hover:bg-gray-100"><i class="fa-solid fa-file-excel w-5 h-5 mr-3 text-green-600"></i> Export Excel</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reportToggles = document.querySelectorAll('.report-dropdown-toggle');

    const closeAllReportDropdowns = () => {
        reportToggles.forEach(button => {
            button.nextElementSibling.classList.add('hidden');
        });
    };

    reportToggles.forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation();

            const currentMenu = this.nextElementSibling;
            const isHidden = currentMenu.classList.contains('hidden');
            
            closeAllReportDropdowns();

            if (isHidden) {
                currentMenu.classList.remove('hidden');
            }
        });
    });

    window.addEventListener('click', function() {
        closeAllReportDropdowns();
    });
});
</script>