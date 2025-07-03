<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($judul); ?></title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .container {
            width: 95%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #f2f2f2;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f9f9f9;
            font-weight: bold;
            color: #333;
        }
        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tbody tr:hover {
            background-color: #e9e9e9;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><?= htmlspecialchars($judul); ?></h2>
            <p>Dicetak pada: <?= date('d F Y'); ?></p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Nama Pendatang</th>
                    <th>Keperluan</th>
                    <th>Status</th>
                    <th>Nomor Surat</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data untuk ditampilkan.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach($logs as $log): ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= htmlspecialchars(date('d F Y', strtotime($log->TanggalPengajuan))); ?></td>
                        <td><?= htmlspecialchars($log->nama_pendatang); ?></td>
                        <td><?= htmlspecialchars($log->nama_keperluan); ?></td>
                        <td><?= htmlspecialchars($log->StatusPengajuan); ?></td>
                        <td><?= htmlspecialchars($log->NomorSurat ?? '-'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>