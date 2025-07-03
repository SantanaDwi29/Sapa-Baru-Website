<div class="bg-white shadow-md rounded-lg">
    <div class="p-6 border-b flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center">
            <i class="fa-solid fa-history text-indigo-950 mr-3"></i>
            Laporan Riwayat Pengajuan Surat
        </h1>
        <a href="<?= base_url('laporan') ?>" class="bg-gray-200 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-300 transition duration-300 text-base font-medium">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto relative">
            <table class="w-full table-auto text-base">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">No</th>
                        <th class="py-3 px-6 text-left">Tanggal</th>
                        <th class="py-3 px-6 text-left">Nama Pendatang</th>
                        <th class="py-3 px-6 text-left">Keperluan</th>
                        <th class="py-3 px-6 text-center">Status</th>
                        <th class="py-3 px-6 text-left">Nomor Surat</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    
                    <?php if (empty($logs)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-10 text-gray-500">
                                Tidak ada data riwayat pengajuan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($logs as $log): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-4 px-6 text-left whitespace-nowrap"><?= $no++; ?></td>
                                <td class="py-4 px-6 text-left"><?= htmlspecialchars(date('d M Y, H:i', strtotime($log->TanggalPengajuan))); ?></td>
                                <td class="py-4 px-6 text-left font-semibold"><?= htmlspecialchars($log->nama_pendatang); ?></td>
                                <td class="py-4 px-6 text-left"><?= htmlspecialchars($log->nama_keperluan); ?></td>
                                <td class="py-4 px-6 text-center">
                                    <?php
                                        $status = $log->StatusPengajuan;
                                        $badge_color = 'bg-gray-200 text-gray-800'; // Default
                                        if ($status == 'Terverifikasi' || $status == 'Selesai') {
                                            $badge_color = 'bg-green-100 text-green-800';
                                        } elseif ($status == 'Pending') {
                                            $badge_color = 'bg-yellow-100 text-yellow-800';
                                        } elseif ($status == 'Ditolak') {
                                            $badge_color = 'bg-red-100 text-red-800';
                                        }
                                    ?>
                                    <span class="font-medium px-3 py-1 text-sm rounded-full <?= $badge_color; ?>">
                                        <?= htmlspecialchars($status); ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-left"><?= htmlspecialchars($log->NomorSurat ?? '-'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>