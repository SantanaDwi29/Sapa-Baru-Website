<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($judul); ?></title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #333;
        }
        .container {
            width: 98%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 3px double #333;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
        }
        .header p {
            margin: 4px 0 0;
            font-size: 12px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?= htmlspecialchars($judul); ?></h1>
            <p>Dicetak pada: <?= date('d F Y, H:i:s'); ?> WITA</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>NIK</th>
                    <th>Jenis Kelamin</th>
                    <th>Alamat Tujuan</th>
                    <th>Tanggal Masuk</th>
                    <th>Tanggal Keluar</th>
                    <th>Kepala Lingkungan</th>
                    <th>Alasan Keluar</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pendatang)): ?>
                    <?php $no = 1; foreach ($pendatang as $p): ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td><?= htmlspecialchars($p->NamaLengkap ?? '-'); ?></td>
                            <td><?= htmlspecialchars($p->NIK ?? '-'); ?></td>
                            <td><?= htmlspecialchars($p->JenisKelamin ?? '-'); ?></td>
                            <td><?= htmlspecialchars($p->TempatTujuan ?? '-'); ?></td>
                            <td><?= !empty($p->TanggalMasuk) ? htmlspecialchars(date('d M Y', strtotime($p->TanggalMasuk))) : '-'; ?></td>
                            <td><?= !empty($p->TanggalKeluar) ? htmlspecialchars(date('d M Y', strtotime($p->TanggalKeluar))) : '-'; ?></td>
                            <td><?= htmlspecialchars($p->NamaKaling ?? '-'); ?></td>
                            <td><?= htmlspecialchars($p->AlasanKeluar ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data untuk ditampilkan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>