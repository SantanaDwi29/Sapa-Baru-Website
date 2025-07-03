<div class="p-6 bg-white shadow-lg rounded-xl mb-16 mt-10">
    <div class="border-b pb-4 mb-4">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-table text-indigo-950 mr-2"></i>
                Tabel Kepala Lingkungan
            </h1>
        </div>
    </div>
    <div class="p-4">
        <div class="py-4 border-b border-gray-200 flex justify-between items-center flex-wrap gap-4">
            <div class="flex items-center gap-2">
                <span class="text-gray-600">Show</span>
                <select id="entries-select" class="border border-gray-300 rounded px-2 py-1">
                    <option value="1">1</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-gray-600">entries</span>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-gray-600">Status:</span>
                    <select id="statusFilter" class="border border-gray-300 rounded px-2 py-1">
                        <option value="">Semua</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Pending">Pending</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-gray-600">Search:</span>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 text-sm"></i>
                        </div>
                        <input type="text" id="searchInput"
                            class="w-full lg:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
                            placeholder="Cari nama atau NIK">
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full" id="KalingTabel">
                <thead class="bg-gray-100">
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                         <th class="py-3 px-3 text-left">No</th>
                         <th class="py-3 px-4 text-left">Nama Lengkap</th>
                         <th class="py-3 px-4 text-left">NIK</th>
                         <th class="py-3 px-4 text-left">No Telepon</th>
                         <th class="py-3 px-4 text-left">Email</th>
                         <th class="py-3 px-4 text-left">Jenis Akun</th>
                         <th class="py-3 px-4 text-left">Status</th>
                         <th class="py-3 px-4 text-left">Alamat</th>
                         <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-base" id="tableBody">
                    <?php 
                    if (isset($Kaling) && is_array($Kaling)) {
                        foreach ($Kaling as $index => $item): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-100 table-row">
                                <td class="py-2 px-3 text-left" data-label="No"><?= $index + 1 ?></td>
                                <td class="px-4 py-3 text-gray-900 break-words" data-label="Nama Lengkap"><?= htmlspecialchars($item->NamaKaling) ?></td>
                                <td class="px-4 py-3 text-gray-900 break-words" data-label="NIK"><?= htmlspecialchars($item->NIK) ?></td>
                                <td class="px-4 py-3 text-gray-900" data-label="No Telepon"><?= htmlspecialchars($item->Telp) ?></td>
                                <td class="px-4 py-3 text-gray-900 break-words" data-label="Email"><?= htmlspecialchars($item->Email) ?></td>
                                <td class="px-4 py-3" data-label="Jenis Akun">
                                    <span class="px-2 py-1 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <?= htmlspecialchars($item->JenisAkun) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3" data-label="Status">
                                    <span class="px-2 py-1 font-semibold rounded-full
                                        <?= $item->StatusAkun == 'Aktif' ? 'bg-green-100 text-green-800' : ($item->StatusAkun == 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                        <?= htmlspecialchars($item->StatusAkun) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-900 max-w-xs break-words" data-label="Alamat">
                                    <?= htmlspecialchars($item->Alamat) ?>
                                </td>
                                <td class="px-4 py-3 text-center" data-label="Aksi">
                                    <div class="flex justify-center space-x-3">
                                        <button onclick="editUser('<?= $item->idKaling ?>')"
                                            class="text-blue-600 hover:text-blue-800 transition-colors text-xl">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deleteUser('<?= $item->idKaling ?>')"
                                            class="text-red-600 hover:text-red-800 transition-colors text-xl">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; 
                    }
                    ?>
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center" id="pagination-footer">
                <div class="text-gray-700" id="entries-info">
                    </div>
                <div class="flex gap-2" id="pagination-buttons">
                     </div>
            </div>
        </div>
    </div>
</div>