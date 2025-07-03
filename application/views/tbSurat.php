<?php
$JenisAkun=$this->session->userdata('JenisAkun');
?>
<div class="p-6 bg-white shadow-lg rounded-xl mb-16 mt-10 ">

    <div class="border-b pb-4 mb-4">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
            Pengajuan Surat
        </h1>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300" id="TabelPengajuan">
            <thead class="bg-gray-100">
                <tr class="text-gray-600 uppercase text-sm">
                    <th class="py-3 px-6 text-left text-lg border-b border-gray-300">No</th>
                    <th class="py-3 px-6 text-left text-lg border-b border-gray-300">No Surat</th>
                    <th class="py-3 px-6 text-left text-lg border-b border-gray-300">Nama</th>
                    <th class="py-3 px-6 text-left text-lg border-b border-gray-300">Keperluan</th>
                    <th class="py-3 px-6 text-left text-lg border-b border-gray-300">Tanggal Pengajuan</th>
                    <th class="py-3 px-6 text-left text-lg border-b border-gray-300">Status Verifikasi</th>
                    <th class="py-3 px-6 text-left text-lg border-b border-gray-300">Alasan</th>
                    <?php if ($JenisAkun == "Kepala Lingkungan" || $JenisAkun == "Admin") { ?>
                    <th class="py-3 px-6 text-center text-lg border-b border-gray-300">Aksi</th>
                    <?php } ?>

                </tr>
            </thead>
            <tbody id="bodyPengajuan">
                <?php
                $pending_rejected_found = false;
                $no_pending_rejected = 1;
                foreach ($Pengajuan as $item):
                    $item = (object) $item;
                    if ($item->StatusPengajuan == 'Pending' || $item->StatusPengajuan == 'Ditolak'):
                        $pending_rejected_found = true;
                ?>
                        <tr>
                            <td class="py-2 px-3 text-left text-lg border-b border-gray-200"><?= $no_pending_rejected++ ?></td>
                            <td class="px-2 py-3 whitespace-nowrap text-lg text-gray-900 border-b border-gray-200"><?= htmlspecialchars($item->NomorSurat ?? '-') ?></td>
                            <td class="px-2 py-3 whitespace-nowrap text-lg text-gray-900 border-b border-gray-200"><?= htmlspecialchars($item->NamaLengkap) ?></td>
                            <td class="px-2 py-3 whitespace-nowrap text-lg text-gray-900 border-b border-gray-200"><?= htmlspecialchars($item->NamaKeperluan) ?></td>
                            <td class="px-2 py-3 whitespace-nowrap text-lg text-gray-900 border-b border-gray-200"><?= htmlspecialchars(date('d-m-Y H:i', strtotime($item->TanggalPengajuan))) ?></td>

                            <td class="px-2 py-3 whitespace-nowrap text-lg text-gray-900 border-b border-gray-200 text-center">
                                <span class="
                                    <?php
                                    if ($item->StatusPengajuan == 'Pending') echo 'bg-yellow-100 text-yellow-800';
                                    elseif ($item->StatusPengajuan == 'Ditolak') echo 'bg-red-100 text-red-800';
                                    ?> px-3 py-1 rounded-full text-lg  font-semibold
                                ">
                                    <?= htmlspecialchars($item->StatusPengajuan) ?>
                                </span>
                            </td>
                            <td class="px-2 py-3 whitespace-nowrap text-lg text-gray-900 border-b border-gray-200">
                                <?php
                                if ($item->StatusPengajuan == 'Pending') {
                                    echo '-';
                                } else {
                                    echo htmlspecialchars($item->Alasan ?? '-');
                                }
                                ?>
                            </td>
                            <?php if ($JenisAkun == "Kepala Lingkungan" || $JenisAkun == "Admin") { ?>

                            <td class="px-2 py-3 whitespace-nowrap text-center border-b border-gray-200">
                                <div class="flex justify-center items-center space-x-4">

                                    <?php if ($item->StatusPengajuan == 'Pending'): ?>
                                        <button type="button"
                                            onclick="verifikasiSurat(<?= $item->idPengajuan ?>)"
                                            class="text-green-600 hover:text-green-800 transition-colors"
                                            title="Verifikasi Surat">
                                            <i class="fa-regular fa-circle-check text-3xl"></i>
                                        </button>

                                        <button type="button"
                                            onclick="tolakSurat(<?= $item->idPengajuan ?>)"
                                            class="text-red-600 hover:text-red-800 transition-colors"
                                            title="Tolak Surat">
                                            <i class="fa-regular fa-circle-xmark text-3xl"></i>
                                        </button>
                                    <?php endif; ?>

                                    <button type="button"
                                        onclick="hapusPengajuan(<?= $item->idPengajuan ?>)"
                                        class="text-red-600 hover:text-red-800 transition-colors"
                                        title="Hapus Pengajuan">
                                        <i class="fas fa-trash text-2xl"></i>
                                    </button>

                                    <form id="delete-form-<?= $item->idPengajuan ?>"
                                        action="<?= site_url('Surat/delete/' . $item->idPengajuan) ?>"
                                        method="post"
                                        class="hidden">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                    </form>

                                </div>
                            </td>
                            <?php } ?>

                        </tr>
                    <?php
                    endif;
                endforeach;

                if (!$pending_rejected_found):
                    ?>
                    <tr>
                        <td colspan="7" class="text-center text-gray-500 py-4 border-t border-gray-200">Belum ada data pengajuan surat.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>


    <div class="border-b pb-4 mb-4 mt-10">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
            Surat Terverifikasi
        </h1>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300" id="TabelTerverifikasi">
            <thead class="bg-gray-100">
                <tr class="text-gray-600 uppercase text-sm">
                    <th class="py-3 px-6 text-left text-lg border-b border-gray-300">No</th>
                    <th class="py-3 px-6 text-left text-lg border-b border-gray-300">No Surat</th>
                    <th class="py-3 px-6 text-left text-lg border-b border-gray-300">Nama</th>
                    <th class="py-3 px-6 text-left text-lg border-b border-gray-300">Keperluan</th>
                    <th class="py-3 px-6 text-left text-lg border-b border-gray-300">Tanggal Pengajuan</th>
                    <th class="py-3 px-6 text-left text-lg border-b border-gray-300">Status Verifikasi</th>
                    <th class="py-3 px-6 text-center text-lg border-b border-gray-300">Aksi</th>
                </tr>
            </thead>
            <tbody id="bodyTerverifikasi">
                <?php
                $verified_found = false;
                $no_verified = 1; // Initialize counter for verified
                foreach ($Pengajuan as $item):
                    $item = (object) $item; // Cast to object if it's an array
                    if ($item->StatusPengajuan == 'Terverifikasi'):
                        $verified_found = true;
                ?>
                        <tr>
                            <td class="py-2 px-3 text-left text-lg border-b border-gray-200"><?= $no_verified++ ?></td>
                            <td class="px-2 py-3 whitespace-nowrap text-lg text-gray-900 border-b border-gray-200"><?= htmlspecialchars($item->NomorSurat ?? 'N/A') ?></td>
                            <td class="px-2 py-3 whitespace-nowrap text-lg text-gray-900 border-b border-gray-200"><?= htmlspecialchars($item->NamaLengkap) ?></td>
                            <td class="px-2 py-3 whitespace-nowrap text-lg text-gray-900 border-b border-gray-200"><?= htmlspecialchars($item->NamaKeperluan) ?></td>
                            <td class="px-2 py-3 whitespace-nowrap text-lg text-gray-900 border-b border-gray-200 text-center"><?= htmlspecialchars(date('d-m-Y H:i', strtotime($item->TanggalPengajuan))) ?></td>
                            <td class="px-2 py-3 whitespace-nowrap text-lg text-gray-900 border-b border-gray-200 text-center">
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text- font-semibold">
                                    <?= htmlspecialchars($item->StatusPengajuan) ?>
                                </span>
                            </td>
                            <td class="px-2 py-3 whitespace-nowrap text-center border-b border-gray-200">
                                <div class="flex justify-center space-x-3">
                                    <form action="<?= site_url('Surat/delete/' . $item->idPengajuan) ?>" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus surat terverifikasi ini?');" class="inline-block">
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition-colors text-2xl" title="Hapus Surat Terverifikasi">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                    <a href="<?= site_url('Surat/cetak/' . $item->idPengajuan) ?>" target="_blank"
                                        class="text-blue-600 hover:text-blue-800 transition-colors text-2xl" title="Cetak Surat">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php
                    endif;
                endforeach;

                if (!$verified_found):
                    ?>
                    <tr>
                        <td colspan="7" class="text-center text-gray-500 py-4 border-t border-gray-200">Belum ada data terverifikasi.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>