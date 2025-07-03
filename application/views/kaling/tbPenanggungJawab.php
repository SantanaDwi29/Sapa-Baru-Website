<div class="p-6 bg-white shadow-lg rounded-xl mb-16 mt-10">
        <div class="border-b pb-4 mb-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-table text-indigo-950 mr-2"></i>
                    Tabel Penanggung Jawab
                </h1>
            </div>
        </div>
        <div class="p-4">
            <div class="py-4 border-b border-gray-200 flex justify-between items-center flex-wrap gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-gray-600">Show</span>
                    <select id="entries-select-pj" class="border border-gray-300 rounded px-2 py-1">
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
                        <select id="statusFilter-pj" class="border border-gray-300 rounded px-2 py-1">
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
                            <input type="text" id="searchInput-pj"
                                class="w-full lg:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
                                placeholder="Cari nama PJ atau NIK">
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto w-full">
                <table class="w-full" id="PJTabel">
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
                    <tbody class="text-gray-700 text-base" id="tableBody-pj">
                        <?php if (isset($PJ) && is_array($PJ)): ?>
                            <?php foreach ($PJ as $index => $item): ?>
                                <tr class="border-b border-gray-200 hover:bg-gray-100 table-row">
                                    <td class="py-2 px-3 text-left" data-label="No"><?= $index + 1 ?></td>
                                    <td class="px-4 py-3 text-gray-900 break-words" data-label="Nama Lengkap"><?= htmlspecialchars($item->NamaPJ) ?></td>
                                    <td class="px-4 py-3 text-gray-900 break-words" data-label="NIK"><?= htmlspecialchars($item->NIK) ?></td>
                                    <td class="px-4 py-3 text-gray-900" data-label="No Telepon"><?= htmlspecialchars($item->Telp) ?></td>
                                    <td class="px-4 py-3 text-gray-900 break-words" data-label="Email"><?= htmlspecialchars($item->Email) ?></td>
                                    <td class="px-4 py-3" data-label="Jenis Akun">
                                        <span class="px-2 py-1 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            <?= htmlspecialchars($item->JenisAkun) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3" data-label="Status">
                                        <span class="px-2 py-1 font-semibold rounded-full
                                            <?= $item->StatusAkun == 'Aktif' ? 'bg-green-100 text-green-800' : ($item->StatusAkun == 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') ?>">
                                            <?= htmlspecialchars($item->StatusAkun) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-900 max-w-xs break-words" data-label="Alamat"><?= htmlspecialchars($item->Alamat) ?></td>
                                    <td class="px-4 py-3 text-center" data-label="Aksi">
                                        <div class="flex justify-center space-x-3">
                                            <button onclick="editUserPJ('<?= $item->idPJ ?>')" class="text-blue-600 hover:text-blue-800 transition-colors text-xl">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="deleteUserPJ('<?= $item->idPJ ?>')" class="text-red-600 hover:text-red-800 transition-colors text-xl">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center" id="pagination-footer-pj">
                    <div class="text-gray-700" id="entries-info-pj"></div>
                    <div class="flex gap-2" id="pagination-buttons-pj"></div>
                </div>
            </div>
        </div>
    </div>