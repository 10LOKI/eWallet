<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;
use App\Wallet;
use App\Depense;

$db = new Database();
$userId = $_SESSION['user_id'] ?? 1;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Wallet App</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<!-- KEEP YOUR CSS HERE -->
<link rel="stylesheet" href="../assets/style.css">

	<style>
		:root {
			--primary: #0F4C5C;
			--primary-dark: #0B3945;
			--primary-soft: #DCEEEF;
			--bg-main: #F4F8F9;
			--text-main: #1C2B2F;
			--text-muted: #6C7A80;
		}

		body {
			background: var(--bg-main);
			font-family: 'Inter', sans-serif;
			color: var(--text-main);
		}

		/* Sidebar */
		.sidebar {
			background: linear-gradient(180deg, var(--primary), var(--primary-dark));
			min-height: 100vh;
			color: white;
		}

		.sidebar h4 {
			font-weight: 600;
			letter-spacing: 1px;
		}

		.sidebar a {
			color: rgba(255,255,255,.85);
			padding: 14px 24px;
			display: flex;
			align-items: center;
			gap: 10px;
			transition: .2s;
		}

		.sidebar a:hover,
		.sidebar a.active {
			background: rgba(255,255,255,.12);
			color: #fff;
		}

		/* Cards */
		.card {
			border: none;
			border-radius: 16px;
			box-shadow: 0 10px 25px rgba(0,0,0,.06);
		}

		.stat-card h3 {
			font-weight: 700;
		}

		.stat-icon {
			width: 48px;
			height: 48px;
			border-radius: 12px;
			background: var(--primary-soft);
			color: var(--primary);
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 22px;
		}

		/* Progress */
		.progress {
			height: 10px;
			border-radius: 10px;
			background: #E6EFF1;
		}

		.progress-bar {
			background: var(--primary);
		}

		/* Buttons */
		.btn-primary {
			background: var(--primary);
			border: none;
			border-radius: 10px;
			padding: 10px 18px;
		}

		.btn-primary:hover {
			background: var(--primary-dark);
		}

		/* Table */
		.table thead {
			color: var(--text-muted);
			font-size: 14px;
		}

		.table tbody tr {
			transition: .15s;
		}

		.table tbody tr:hover {
			background: #F0F7F8;
		}

		.badge-category {
			background: var(--primary-soft);
			color: var(--primary);
			border-radius: 8px;
			padding: 6px 10px;
			font-weight: 500;
		}

		.auto {
			background: var(--primary);
			color: #fff;
			font-size: 11px;
			padding: 3px 8px;
			border-radius: 8px;
			margin-left: 6px;
		}

		/* Header */
		.topbar {
			background: white;
			border-radius: 16px;
			padding: 16px 24px;
			box-shadow: 0 6px 20px rgba(0,0,0,.05);
		}
	</style>
</head>
<body>
<div class="container-fluid">
<div class="row">