<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $judul; ?></title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1><?= $judul; ?></h1>
        <p>Dicetak pada: <?= date('d F Y H:i:s'); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Nama Lengkap</th>
                <th>NIK</th>
                <th>No. Telepon</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($penanggung_jawab)): ?>
                <?php $no = 1; foreach ($penanggung_jawab as $pj): ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= htmlspecialchars($pj->NamaPJ, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?= htmlspecialchars($pj->NIK, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?= htmlspecialchars($pj->Telp, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?= htmlspecialchars($pj->Email, ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data untuk ditampilkan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>