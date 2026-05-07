<?php
include 'koneksi.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Logika Create
if(isset($_POST['tambah'])){
    $nama = $_POST['nama'];
    $sandi = $_POST['sandi'];
    // Gunakan prepared statements atau escape minimal
    $nama = mysqli_real_escape_string($koneksi, $nama);
    $sandi = mysqli_real_escape_string($koneksi, $sandi);
    
    mysqli_query($koneksi, "INSERT INTO users (nama, sandi) VALUES('$nama', '$sandi')");
    
    // Mencegah form resubmission saat refresh (PRG Pattern)
    header("Location: index.php");
    exit();
}

// Logika Delete
if(isset($_GET['hapus'])){
    $id = intval($_GET['hapus']);
    mysqli_query($koneksi, "DELETE FROM users WHERE id=$id");
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP CRUD Railway</title>
    <!-- Modern Font: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-grad-1: #0f172a;
            --bg-grad-2: #1e293b;
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --danger: #ef4444;
            --danger-hover: #dc2626;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --glass-bg: rgba(30, 41, 59, 0.7);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--bg-grad-1), var(--bg-grad-2));
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 60px 20px;
        }

        .container {
            width: 100%;
            max-width: 850px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 2.8rem;
            font-weight: 700;
            background: linear-gradient(to right, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
            letter-spacing: -1px;
        }

        .header p {
            color: var(--text-muted);
            font-size: 1.1rem;
        }

        .card {
            background: rgba(15, 23, 42, 0.4);
            border-radius: 16px;
            padding: 24px 30px;
            margin-bottom: 30px;
            border: 1px solid var(--glass-border);
        }

        .card h2 {
            font-size: 1.4rem;
            margin-bottom: 20px;
            font-weight: 600;
            color: #e2e8f0;
        }

        .form-group {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        input {
            flex: 1;
            min-width: 200px;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--glass-border);
            color: #fff;
            padding: 14px 20px;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            outline: none;
        }

        input::placeholder {
            color: var(--text-muted);
        }

        input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
            background: rgba(15, 23, 42, 0.8);
        }

        button {
            background: var(--primary);
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.4);
        }

        button:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.5);
        }

        button:active {
            transform: translateY(0);
        }

        /* Table Styles */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            border-radius: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background: rgba(15, 23, 42, 0.8);
            color: var(--text-muted);
            font-weight: 600;
            padding: 18px 24px;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--glass-border);
            text-transform: uppercase;
        }

        td {
            padding: 18px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            color: #e2e8f0;
            vertical-align: middle;
        }

        tr:hover td {
            background: rgba(255, 255, 255, 0.03);
        }

        tr:last-child td {
            border-bottom: none;
        }

        .badge {
            display: inline-block;
            background: rgba(59, 130, 246, 0.15);
            color: #93c5fd;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .btn-delete {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            text-decoration: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .btn-delete:hover {
            background: var(--danger);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(239, 68, 68, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: var(--text-muted);
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            body {
                padding: 20px 10px;
            }
            .container {
                padding: 30px 20px;
                border-radius: 20px;
            }
            .form-group {
                flex-direction: column;
            }
            button {
                width: 100%;
            }
            .header h1 {
                font-size: 2.2rem;
            }
            td, th {
                padding: 14px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>User Dashboard</h1>
            <p>Kelola data pengguna dengan antarmuka yang modern</p>
        </div>

        <div class="card">
            <h2>Tambah Data Baru</h2>
            <form method="POST" class="form-group">
                <input type="text" name="nama" placeholder="Masukkan Nama" required autocomplete="off">
                <input type="password" name="sandi" placeholder="Masukkan Sandi" required>
                <button type="submit" name="tambah">Simpan Data</button>
            </form>
        </div>

        <div class="card" style="padding: 0; overflow: hidden;">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Pengguna</th>
                            <th>Kata Sandi</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $data = mysqli_query($koneksi, "SELECT * FROM users ORDER BY id DESC");
                        if(mysqli_num_rows($data) > 0) {
                            while($d = mysqli_fetch_array($data)){
                        ?>
                        <tr>
                            <td><span class="badge">#<?php echo $d['id']; ?></span></td>
                            <td style="font-weight: 500; font-size: 1.05rem;"><?php echo htmlspecialchars($d['nama']); ?></td>
                            <td style="color: #94a3b8;">&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;</td>
                            <td style="text-align: center;">
                                <a href="index.php?hapus=<?php echo $d['id']; ?>" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                    Hapus
                                </a>
                            </td>
                        </tr>
                        <?php 
                            }
                        } else {
                            echo '<tr><td colspan="4"><div class="empty-state">Belum ada data pengguna yang terdaftar.</div></td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
