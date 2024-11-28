-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 24 Nov 2024 pada 05.09
-- Versi server: 10.4.14-MariaDB
-- Versi PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_tms_empty`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `currency`
--

CREATE TABLE `currency` (
  `currency_id` int(11) NOT NULL,
  `currency_code` longtext COLLATE utf8_unicode_ci NOT NULL,
  `currency_symbol` longtext COLLATE utf8_unicode_ci NOT NULL,
  `currency_name` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data untuk tabel `currency`
--

INSERT INTO `currency` (`currency_id`, `currency_code`, `currency_symbol`, `currency_name`) VALUES
(1, 'USD', '$', 'US dollar'),
(2, 'IDR', 'Rp', 'Indonesia Rupiah'),
(3, 'EUR', '€', 'Euro'),
(4, 'AUD', '$', 'Australian Dollar'),
(5, 'CAD', '$', 'Canadian Dollar'),
(6, 'JPY', '¥', 'Japanese Yen'),
(7, 'NZD', '$', 'N.Z. Dollar'),
(8, 'CHF', 'Fr', 'Swiss Franc'),
(9, 'HKD', '$', 'Hong Kong Dollar'),
(10, 'SGD', '$', 'Singapore Dollar'),
(11, 'SEK', 'kr', 'Swedish Krona'),
(12, 'DKK', 'kr', 'Danish Krone'),
(13, 'PLN', 'zł', 'Polish Zloty'),
(14, 'HUF', 'Ft', 'Hungarian Forint'),
(15, 'CZK', 'Kč', 'Czech Koruna'),
(16, 'MXN', '$', 'Mexican Peso'),
(17, 'CZK', 'Kč', 'Czech Koruna'),
(18, 'MYR', 'RM', 'Malaysian Ringgit');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_accident_type`
--

CREATE TABLE `tb_accident_type` (
  `id` int(11) NOT NULL,
  `accident_id` varchar(200) NOT NULL,
  `desc` varchar(400) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_areas`
--

CREATE TABLE `tb_areas` (
  `id` int(11) NOT NULL,
  `area_id` varchar(100) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `area_type` int(11) DEFAULT NULL COMMENT '1 = sales, 2 = branch',
  `additional_information` varchar(200) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) DEFAULT 0,
  `id_company` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_banner`
--

CREATE TABLE `tb_banner` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `desc` varchar(100) DEFAULT NULL,
  `image` varchar(100) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_clients`
--

CREATE TABLE `tb_clients` (
  `id` int(11) NOT NULL,
  `client_id` varchar(100) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `address1` longtext DEFAULT NULL,
  `address2` longtext DEFAULT NULL,
  `city` varchar(200) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `pic` varchar(100) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `additional_information` longtext DEFAULT NULL,
  `payment_term` varchar(4) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_client_rates`
--

CREATE TABLE `tb_client_rates` (
  `id` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL COMMENT '1 = ON CALL, 2 = DEDICATED',
  `client_id` int(11) DEFAULT NULL,
  `origin_id` int(11) DEFAULT NULL,
  `destination_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `currency` varchar(10) DEFAULT NULL,
  `rate_type` int(11) DEFAULT 1 COMMENT '1 = REGULAR, 2 = WEIGHT',
  `vehicle_rate` double DEFAULT NULL,
  `min_weight` double DEFAULT NULL,
  `remark` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `id_company` int(11) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_company`
--

CREATE TABLE `tb_company` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(200) DEFAULT NULL,
  `no_telp` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `code` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_component`
--

CREATE TABLE `tb_component` (
  `id` int(11) NOT NULL,
  `description` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) DEFAULT 0,
  `id_company` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_component_entries`
--

CREATE TABLE `tb_component_entries` (
  `id` int(11) NOT NULL,
  `id_manifest` int(11) NOT NULL,
  `id_cost_component` int(11) NOT NULL,
  `qty` int(11) DEFAULT 0,
  `price` double DEFAULT 0,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL,
  `amount` double DEFAULT 0,
  `type` int(11) DEFAULT NULL COMMENT '0 = transporter, 1 = client'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_customers`
--

CREATE TABLE `tb_customers` (
  `id` int(11) NOT NULL,
  `customer_id` varchar(100) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `address1` longtext DEFAULT NULL,
  `address2` longtext DEFAULT NULL,
  `type` int(11) DEFAULT NULL COMMENT '1 = modern, 2 = traditional',
  `city` varchar(200) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `region_id` longtext DEFAULT NULL COMMENT 'check if not use delete',
  `area_id` int(11) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `pic` varchar(100) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `additional_information` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_dedicated_rate`
--

CREATE TABLE `tb_dedicated_rate` (
  `id` int(11) NOT NULL,
  `id_vehicle` int(11) NOT NULL,
  `id_transporter` int(11) NOT NULL,
  `vehicle_rate` double DEFAULT NULL,
  `currency` varchar(10) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `desc` varchar(200) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_drivers`
--

CREATE TABLE `tb_drivers` (
  `id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `address1` longtext DEFAULT NULL,
  `address2` longtext DEFAULT NULL,
  `city` varchar(200) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `transporter_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `token` varchar(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `token_fcm` varchar(300) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL,
  `foto_sim` varchar(255) DEFAULT NULL,
  `foto_ktp` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_history_change_load`
--

CREATE TABLE `tb_history_change_load` (
  `id` int(11) NOT NULL,
  `old_load` double DEFAULT NULL,
  `new_load` double DEFAULT NULL,
  `desc` varchar(200) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_manifest` int(11) DEFAULT NULL,
  `id_company` int(11) DEFAULT NULL,
  `range` double DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `status` int(1) DEFAULT NULL COMMENT '1 = reduce, 2 = increase'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_manifests`
--

CREATE TABLE `tb_manifests` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `tr_id` int(11) DEFAULT NULL,
  `start` int(11) DEFAULT 0,
  `schedule_date` timestamp NULL DEFAULT NULL,
  `trip` int(2) DEFAULT NULL,
  `finish` int(11) DEFAULT 0,
  `mileage` int(11) DEFAULT 0,
  `mode` int(11) DEFAULT NULL,
  `manifest_status` int(1) DEFAULT 0 COMMENT '0 = open, 1 = confirm',
  `variable_cost` double DEFAULT NULL,
  `client_variable_cost` double DEFAULT NULL,
  `sum_component_cost` double DEFAULT 0,
  `client_sum_component_cost` double DEFAULT 0,
  `load_m3` double DEFAULT 0,
  `load_kg` double DEFAULT 0,
  `driver_id` int(11) DEFAULT NULL,
  `co_driver_id` int(11) DEFAULT NULL,
  `order_case` int(1) DEFAULT 0 COMMENT '0 = Regular, 1 = Urgent',
  `approved_by` varchar(200) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(11) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL,
  `min_weight_client_rate` double DEFAULT NULL,
  `min_weight_transporter_rate` double DEFAULT NULL,
  `client_rate_id` int(11) DEFAULT NULL,
  `transporter_rate_id` int(11) DEFAULT NULL,
  `client_rate_status` int(11) DEFAULT NULL,
  `transporter_rate_status` int(11) DEFAULT NULL,
  `id_purchase_invoice` int(11) DEFAULT NULL,
  `id_sales_invoice` int(11) DEFAULT NULL,
  `file_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_manifest_status`
--

CREATE TABLE `tb_manifest_status` (
  `id` int(11) NOT NULL,
  `status` varchar(100) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `deleted` int(11) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_manifest_status`
--

INSERT INTO `tb_manifest_status` (`id`, `status`, `description`, `deleted`, `created_by`, `created_date`) VALUES
(0, 'Open', NULL, 0, 43, '2024-02-21 13:14:56'),
(1, 'Confirm', NULL, 0, 43, '2024-02-21 13:14:56'),
(2, 'Completed', NULL, 0, 43, '2024-02-21 13:14:56'),
(3, 'Delivery', NULL, 0, NULL, '2024-03-09 07:13:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_mechanics`
--

CREATE TABLE `tb_mechanics` (
  `id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `address1` longtext DEFAULT NULL,
  `address2` longtext DEFAULT NULL,
  `city` varchar(200) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_mobile_apps_version`
--

CREATE TABLE `tb_mobile_apps_version` (
  `id` int(11) NOT NULL,
  `code_version` varchar(50) NOT NULL,
  `name_version` varchar(50) DEFAULT NULL,
  `type_platform` varchar(50) NOT NULL,
  `desc_version` text DEFAULT NULL,
  `file` varchar(100) NOT NULL,
  `created_by` int(10) DEFAULT NULL,
  `updated_by` int(10) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL,
  `priority` int(1) NOT NULL DEFAULT 0 COMMENT '0 = non mandatory update, 1 = mandatory update'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_notification`
--

CREATE TABLE `tb_notification` (
  `id` int(11) NOT NULL,
  `id_reference` varchar(200) DEFAULT NULL,
  `type_user` int(11) NOT NULL COMMENT '1 = user, 2 = driver',
  `id_user` int(11) NOT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `status_read` int(11) NOT NULL DEFAULT 0 COMMENT '0 = unread, 1 = read',
  `title` varchar(100) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `type_notif` varchar(100) DEFAULT NULL,
  `id_company` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pod`
--

CREATE TABLE `tb_pod` (
  `id` int(11) NOT NULL,
  `transport_order_id` int(11) DEFAULT NULL,
  `pending_code` int(11) DEFAULT NULL,
  `code` int(11) DEFAULT NULL,
  `pod_time` timestamp NULL DEFAULT NULL,
  `receivetime` timestamp NULL DEFAULT NULL,
  `doc_reference` longtext DEFAULT NULL,
  `receiver` longtext DEFAULT NULL,
  `remark` longtext DEFAULT NULL,
  `submit_time` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) DEFAULT NULL,
  `id_company` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL,
  `status` int(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pod_code`
--

CREATE TABLE `tb_pod_code` (
  `id` int(11) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `pod_description` varchar(200) DEFAULT NULL,
  `pic` varchar(200) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pt_owned`
--

CREATE TABLE `tb_pt_owned` (
  `id` int(11) NOT NULL,
  `name_pt` varchar(100) NOT NULL,
  `address_pt` varchar(255) NOT NULL,
  `account_bank_name` varchar(25) DEFAULT NULL,
  `account_name` varchar(25) DEFAULT NULL,
  `account_bank_number` varchar(25) DEFAULT NULL,
  `director` varchar(25) DEFAULT NULL,
  `telp_pt` varchar(50) DEFAULT NULL,
  `taxable` int(2) DEFAULT NULL,
  `deleted` int(2) NOT NULL DEFAULT 0,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NULL DEFAULT NULL,
  `created_by` varchar(25) DEFAULT NULL,
  `updated_by` varchar(25) DEFAULT NULL,
  `id_company` int(11) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_purchase_invoice`
--

CREATE TABLE `tb_purchase_invoice` (
  `id` int(11) NOT NULL,
  `reference` varchar(100) DEFAULT NULL,
  `invoice_date` timestamp NULL DEFAULT NULL,
  `due_date` timestamp NULL DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `transporter_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `area_type` int(11) DEFAULT NULL,
  `sub_total` double DEFAULT NULL,
  `total_vat` double DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `inv_status` int(11) DEFAULT NULL,
  `vat` double DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL,
  `taxable` int(11) DEFAULT NULL,
  `pph` double DEFAULT NULL,
  `total_pph` double DEFAULT NULL,
  `purchase_invoice_type` int(1) DEFAULT NULL COMMENT '1 = add self billing, 2 = add self billing retail',
  `payment_term` int(11) DEFAULT NULL,
  `file_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_ring_code`
--

CREATE TABLE `tb_ring_code` (
  `id` int(11) NOT NULL,
  `ring_name` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_ring_code`
--

INSERT INTO `tb_ring_code` (`id`, `ring_name`, `created_by`, `updated_by`, `created_date`, `updated_date`, `deleted`, `id_company`) VALUES
(1, 'Domestik', NULL, NULL, '2022-06-13 09:56:00', '2022-06-13 09:56:00', 0, NULL),
(2, 'Export', NULL, NULL, '2022-06-13 09:56:00', '2022-06-13 09:56:00', 0, NULL),
(3, 'Import', NULL, NULL, '2022-06-13 09:56:00', '2022-06-13 09:56:00', 0, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_sales_invoice`
--

CREATE TABLE `tb_sales_invoice` (
  `id` int(11) NOT NULL,
  `reference` varchar(200) DEFAULT NULL,
  `invoice_date` timestamp NULL DEFAULT NULL,
  `due_date` timestamp NULL DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `area_type` int(11) DEFAULT NULL,
  `sub_total` double DEFAULT NULL,
  `total_vat` double DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `inv_status` int(11) DEFAULT NULL,
  `taxable` int(2) NOT NULL DEFAULT 1,
  `vat` double DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL,
  `pph` double DEFAULT NULL,
  `total_pph` double DEFAULT NULL,
  `client_invoice_type` int(1) DEFAULT NULL COMMENT '1 = add invoice, 2 = add invoice retail',
  `payment_term` int(11) DEFAULT NULL,
  `file_name` varchar(100) NOT NULL,
  `sub_total_variable_cost` double DEFAULT NULL,
  `sub_total_cost_component` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_service_order`
--

CREATE TABLE `tb_service_order` (
  `id` int(11) NOT NULL,
  `reference` varchar(200) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `odometer` double DEFAULT NULL,
  `registered_date` date DEFAULT NULL,
  `registered_time` varchar(100) DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `completion_time` varchar(100) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `service_type` int(2) DEFAULT NULL COMMENT '1 = INTERIM CAR SERVICE, 2 = FULL CAR SERVICE, 3 = MAJOR CAR SERVICE',
  `service_status` int(2) DEFAULT NULL COMMENT '1 = REJECTED, 2 = QUEUED, 3 = IN PROGRESS, 4 = COMPLETED',
  `assigned_to` int(11) DEFAULT NULL,
  `vat` double DEFAULT NULL,
  `sub_total` double DEFAULT NULL,
  `total_vat` double DEFAULT NULL,
  `total_amount` double DEFAULT NULL,
  `remark` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_service_order_status`
--

CREATE TABLE `tb_service_order_status` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_service_order_status`
--

INSERT INTO `tb_service_order_status` (`id`, `name`, `created_by`, `updated_by`, `created_date`, `updated_date`, `deleted`, `id_company`) VALUES
(1, 'REJECTED', 119, NULL, '2023-06-26 20:39:44', NULL, 0, NULL),
(2, 'QUEUED', 119, NULL, '2023-06-26 20:39:44', NULL, 0, NULL),
(3, 'IN PROGRESS', 119, NULL, '2023-06-26 20:39:44', NULL, 0, NULL),
(4, 'COMPLETED', 119, NULL, '2023-06-26 20:39:44', NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_service_order_type`
--

CREATE TABLE `tb_service_order_type` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_service_order_type`
--

INSERT INTO `tb_service_order_type` (`id`, `name`, `created_by`, `updated_by`, `created_date`, `updated_date`, `deleted`, `id_company`) VALUES
(1, 'INTERM CAR SERVICE', 119, NULL, '2023-06-26 20:37:15', NULL, 0, NULL),
(2, 'FULL CAR SERVICE', 119, NULL, '2023-06-26 20:37:15', NULL, 0, NULL),
(3, 'MAJOR CAR SERVICE', 119, NULL, '2023-06-26 20:37:15', NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_service_tasks`
--

CREATE TABLE `tb_service_tasks` (
  `id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `id_company` int(11) DEFAULT NULL,
  `deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_service_task_entries`
--

CREATE TABLE `tb_service_task_entries` (
  `id` int(11) NOT NULL,
  `id_service_order` int(11) NOT NULL,
  `id_service_task` int(11) NOT NULL,
  `qty` int(11) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_setting`
--

CREATE TABLE `tb_setting` (
  `id` int(11) NOT NULL,
  `name_value` varchar(100) NOT NULL,
  `type` int(11) NOT NULL DEFAULT 1 COMMENT '1 = Text, 2 = Number, 3 = Boolean, 4 = Array',
  `default_value` varchar(200) DEFAULT NULL,
  `code` varchar(100) NOT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_setting`
--

INSERT INTO `tb_setting` (`id`, `name_value`, `type`, `default_value`, `code`, `created_date`, `updated_date`, `created_by`, `updated_by`, `deleted`, `id_company`) VALUES
(1, 'VAT', 2, '10', 'VAT', '2024-07-28 19:34:53', NULL, 142, NULL, 0, 0),
(2, 'CURRENCY', 1, 'IDR', 'CURRENCY', '2024-07-28 19:35:12', NULL, 142, NULL, 0, 0),
(3, 'Contact Person', 3, 'true', 'CP_CLIENT_INVOICE', '2024-07-28 19:35:38', NULL, 142, NULL, 0, 0),
(4, 'tester123', 1, 'tester', 'TESTER', '2024-08-11 01:32:39', '2024-08-11 01:33:39', 142, 142, 1, NULL),
(5, 'Google Map', 3, 'false', 'GOOGLE_MAP', '2024-10-18 21:26:18', '2024-11-01 08:44:13', 142, 142, 0, 8),
(6, 'Google Map', 3, 'false', 'GOOGLE_MAP', '2024-10-18 22:42:46', NULL, 142, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_status_traffic_monitoring`
--

CREATE TABLE `tb_status_traffic_monitoring` (
  `id` int(11) NOT NULL,
  `status_name` varchar(100) NOT NULL,
  `desc` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_status_traffic_monitoring`
--

INSERT INTO `tb_status_traffic_monitoring` (`id`, `status_name`, `desc`) VALUES
(1, 'Open', 'Status Open'),
(2, 'Delivery', 'Status Delivery'),
(3, 'Arrival', 'Status Arrival'),
(4, 'Loading', 'Status Loading'),
(5, 'Unloading', 'Status Unloading'),
(6, 'Completed', 'Status Completed');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_tracking_driver`
--

CREATE TABLE `tb_tracking_driver` (
  `id` int(11) NOT NULL,
  `id_driver` int(11) DEFAULT NULL,
  `id_manifest` int(11) DEFAULT NULL,
  `latlng` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_traffic_monitoring`
--

CREATE TABLE `tb_traffic_monitoring` (
  `id` int(11) NOT NULL,
  `transport_order_id` int(11) DEFAULT NULL,
  `point_id` int(11) DEFAULT NULL,
  `tm_state` varchar(50) DEFAULT NULL,
  `tm_status` int(2) DEFAULT 1,
  `arrival_eta` timestamp NULL DEFAULT NULL,
  `arrival_etatime` varchar(50) DEFAULT NULL,
  `arrival_ata` timestamp NULL DEFAULT NULL,
  `arrival_atatime` varchar(50) DEFAULT NULL,
  `spm_submit` timestamp NULL DEFAULT NULL,
  `spm_submittime` varchar(50) DEFAULT NULL,
  `loading_start` timestamp NULL DEFAULT NULL,
  `loading_starttime` varchar(50) DEFAULT NULL,
  `loading_finish` timestamp NULL DEFAULT NULL,
  `loading_finishtime` varchar(50) DEFAULT NULL,
  `documentation` timestamp NULL DEFAULT NULL,
  `documentationtime` varchar(50) DEFAULT NULL,
  `departure_eta` timestamp NULL DEFAULT NULL,
  `departure_etatime` varchar(50) DEFAULT NULL,
  `departure_ata` timestamp NULL DEFAULT NULL,
  `departure_atatime` varchar(50) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `arrival_note` longtext NOT NULL,
  `arrival_image` varchar(100) NOT NULL,
  `arrival_latlng` varchar(100) NOT NULL,
  `loading_start_note` longtext NOT NULL,
  `loading_start_image` varchar(100) NOT NULL,
  `loading_start_latlng` varchar(100) NOT NULL,
  `loading_finish_note` longtext NOT NULL,
  `loading_finish_image` varchar(100) NOT NULL,
  `loading_finish_latlng` varchar(100) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_transporters`
--

CREATE TABLE `tb_transporters` (
  `id` int(11) NOT NULL,
  `transporter_id` varchar(100) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `address1` longtext DEFAULT NULL,
  `address2` longtext DEFAULT NULL,
  `city` varchar(200) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `transport_mode` int(3) DEFAULT NULL COMMENT '1 = land, 2 = air,  3 = sea, 4 = railway',
  `payment_term` varchar(4) DEFAULT NULL,
  `additional_information` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_transporter_rates`
--

CREATE TABLE `tb_transporter_rates` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `transporter_id` int(11) DEFAULT NULL,
  `origin_id` int(11) DEFAULT NULL,
  `destination_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `status` int(3) DEFAULT NULL COMMENT '1 = ON CALL, 2 = DEDICATED',
  `currency` varchar(10) DEFAULT NULL,
  `rate_type` int(3) DEFAULT 1 COMMENT '1 = REGULAR, 2 = WEIGHT',
  `vehicle_rate` double DEFAULT NULL,
  `min_weight` double DEFAULT NULL,
  `remark` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_transport_mode`
--

CREATE TABLE `tb_transport_mode` (
  `id` int(11) NOT NULL,
  `transport_mode` varchar(100) NOT NULL,
  `desc` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_transport_mode`
--

INSERT INTO `tb_transport_mode` (`id`, `transport_mode`, `desc`) VALUES
(1, 'LAND', NULL),
(2, 'AIR', NULL),
(3, 'SEA', NULL),
(4, 'RAIL WAY', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_transport_order`
--

CREATE TABLE `tb_transport_order` (
  `id` int(11) NOT NULL,
  `reference_id` varchar(100) DEFAULT NULL,
  `manifest_id` int(11) DEFAULT NULL,
  `trip` int(2) DEFAULT NULL,
  `do_number` varchar(100) DEFAULT NULL,
  `so_number` varchar(100) DEFAULT NULL,
  `delivery_date` timestamp NULL DEFAULT NULL,
  `req_arrival_date` timestamp NULL DEFAULT NULL,
  `document_date` timestamp NULL DEFAULT NULL,
  `order_type` int(3) DEFAULT NULL,
  `origin_id` int(11) DEFAULT NULL,
  `dest_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `order_status` int(3) DEFAULT NULL,
  `order_qty` double DEFAULT NULL,
  `uom` int(11) DEFAULT NULL,
  `order_qty_v2` double DEFAULT NULL,
  `uom_v2` int(11) DEFAULT NULL,
  `remark` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL,
  `posting_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_trucking_order`
--

CREATE TABLE `tb_trucking_order` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `schedule_date` timestamp NULL DEFAULT NULL,
  `transport_mode` int(3) DEFAULT NULL,
  `budget` double DEFAULT NULL,
  `pref_vehicle_type` int(11) DEFAULT NULL,
  `tr_status` int(3) DEFAULT NULL COMMENT '0 = open, 1 = close',
  `origin_id` int(11) DEFAULT NULL,
  `dest_id` int(11) DEFAULT NULL,
  `origin_area_id` int(11) DEFAULT NULL,
  `dest_area_id` int(11) DEFAULT NULL,
  `req_pickup_time` timestamp NULL DEFAULT NULL,
  `req_arrival_time` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_truck_accident`
--

CREATE TABLE `tb_truck_accident` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `accident_date` date DEFAULT NULL,
  `accident_type` int(11) DEFAULT NULL,
  `location` longtext DEFAULT NULL,
  `chronology_accident` longtext DEFAULT NULL,
  `vehicle_condition` longtext DEFAULT NULL,
  `amount_less` double DEFAULT NULL,
  `police_investigation_report` longtext DEFAULT NULL,
  `additional_information` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_type_taxable`
--

CREATE TABLE `tb_type_taxable` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `desc` varchar(100) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_type_taxable`
--

INSERT INTO `tb_type_taxable` (`id`, `name`, `desc`, `created_date`, `updated_date`, `created_by`, `updated_by`, `deleted`) VALUES
(1, 'PKP', 'Type Taxable PKP', '2023-10-21 15:19:14', '2023-10-21 15:19:14', 1, NULL, 0),
(2, 'NON PKP', 'Type Taxable Non PKP', '2023-10-21 15:19:40', '2023-10-21 15:19:40', 1, NULL, 0),
(3, 'CARGO', 'Type Taxable Cargo', '2023-10-21 15:19:54', '2023-10-21 15:19:54', 1, NULL, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_uom`
--

CREATE TABLE `tb_uom` (
  `id` int(11) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted` int(11) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_uom`
--

INSERT INTO `tb_uom` (`id`, `code`, `description`, `created_date`, `updated_date`, `created_by`, `updated_by`, `deleted`, `id_company`) VALUES
(1, 'KG', 'Kilograms', '2022-03-30 03:00:32', '2023-10-20 11:52:20', NULL, 119, 0, NULL),
(2, 'CTN', 'Carton', '2022-03-30 03:00:32', '2022-03-30 03:00:32', NULL, NULL, 0, NULL),
(3, 'PCS', 'Piece', '2022-03-30 03:00:32', '2022-03-30 03:00:32', NULL, NULL, 0, NULL),
(4, 'PACK', 'Pack', '2022-03-30 03:00:32', '2022-03-30 03:00:32', NULL, NULL, 0, NULL),
(8, 'M', 'Meters', '2022-06-27 23:52:38', '2022-06-27 23:54:08', 119, 119, 0, 1),
(9, 'L', 'Liters', '2022-06-28 00:57:29', '2022-06-28 00:58:15', 119, 119, 0, 3),
(10, 'TESTER', 'asdass', '2023-08-20 02:12:48', '2023-08-20 03:55:04', 119, 119, 1, NULL),
(11, 'TESTER2EDIT', 'tester2 edit', '2023-08-20 02:29:14', '2023-08-20 03:54:53', 119, 119, 1, NULL),
(12, 'TESTER3EDIT', 'tester 3edit', '2023-08-20 02:37:17', '2023-08-20 03:54:58', 119, 119, 1, NULL),
(13, 'Kgs', 'Kiligrams', '2024-01-20 22:23:15', '2024-01-21 05:23:15', 119, NULL, 0, 1),
(14, 'TON', 'Ton', '2024-07-28 07:22:42', '2024-07-28 08:11:30', 142, 142, 1, 0),
(15, 'TEST', 'teste1', '2024-07-28 08:11:41', '2024-07-28 08:16:03', 142, 142, 1, 0),
(16, 'TON', 'Ton', '2024-07-28 08:16:27', NULL, 142, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_vehicles`
--

CREATE TABLE `tb_vehicles` (
  `id` int(11) NOT NULL,
  `vehicle_id` varchar(100) DEFAULT NULL,
  `driver` int(11) DEFAULT NULL,
  `co_driver` int(11) DEFAULT NULL,
  `transporter_id` int(11) DEFAULT NULL,
  `status` int(3) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `max_volume` double DEFAULT NULL,
  `max_weight` double DEFAULT NULL,
  `subcon` int(11) DEFAULT NULL,
  `additional_information` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL,
  `no_stnk` varchar(25) DEFAULT NULL,
  `no_kir` varchar(25) DEFAULT NULL,
  `no_lambung` varchar(50) DEFAULT NULL,
  `tgl_aktif_stnk` date DEFAULT NULL,
  `tgl_aktif_kir` date DEFAULT NULL,
  `foto_stnk` varchar(255) DEFAULT NULL,
  `foto_kir` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_vehicle_types`
--

CREATE TABLE `tb_vehicle_types` (
  `id` int(11) NOT NULL,
  `type_id` varchar(200) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `volume_cap` double DEFAULT NULL,
  `weight_cap` double DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `id_company` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_vendors`
--

CREATE TABLE `tb_vendors` (
  `id` int(11) NOT NULL,
  `vendor_id` varchar(100) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `address1` longtext DEFAULT NULL,
  `address2` longtext DEFAULT NULL,
  `city` varchar(200) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `additional_information` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` timestamp NULL DEFAULT NULL,
  `id_company` varchar(100) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `role_id` int(11) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `phone` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `gender` int(11) NOT NULL DEFAULT 0 COMMENT '1(male), 2(female)',
  `contact_email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_id` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT current_timestamp(),
  `updated_date` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `image` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_company` int(11) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `address` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`user_id`, `name`, `username`, `password`, `role_id`, `is_active`, `phone`, `gender`, `contact_email`, `employee_id`, `created_date`, `updated_date`, `created_by`, `updated_by`, `image`, `id_company`, `deleted`, `address`, `token`) VALUES
(1, 'SuperAdmin', 'superadmin', '7c4a8d09ca3762af61e59520943dc26494f8941b', 7, 1, '083872167612', 0, 'superadmin@richland.com', NULL, '2024-11-24 04:02:29', NULL, 1, NULL, NULL, NULL, 0, NULL, 'cOmM7zmrvScR3dOdfDZMCkAejkrXz3oQ');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_access_menu`
--

CREATE TABLE `user_access_menu` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_access_menu`
--

INSERT INTO `user_access_menu` (`id`, `role_id`, `menu_id`) VALUES
(2, 7, 1),
(3, 7, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_access_menu_item`
--

CREATE TABLE `user_access_menu_item` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `menu_item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_access_menu_item`
--

INSERT INTO `user_access_menu_item` (`id`, `role_id`, `menu_item_id`) VALUES
(3, 7, 8),
(4, 7, 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_access_sub_menu`
--

CREATE TABLE `user_access_sub_menu` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `sub_menu_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_access_sub_menu`
--

INSERT INTO `user_access_sub_menu` (`id`, `role_id`, `sub_menu_id`) VALUES
(7, 7, 27),
(8, 7, 31),
(9, 7, 33),
(10, 7, 34),
(11, 7, 35),
(12, 7, 32);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_menu`
--

CREATE TABLE `user_menu` (
  `id` int(11) NOT NULL,
  `page_name` varchar(128) DEFAULT NULL,
  `title` varchar(128) NOT NULL,
  `url` varchar(128) NOT NULL,
  `sequence` int(11) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `icon` varchar(128) DEFAULT NULL,
  `sortir_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_menu`
--

INSERT INTO `user_menu` (`id`, `page_name`, `title`, `url`, `sequence`, `description`, `is_active`, `icon`, `sortir_number`) VALUES
(1, '', 'Home', '#', NULL, NULL, 1, '', 1),
(2, NULL, 'Menu', '#', NULL, NULL, 1, '', 2),
(3, NULL, 'Master Data', '#', NULL, NULL, 1, NULL, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_menu_item`
--

CREATE TABLE `user_menu_item` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `page_name` varchar(128) DEFAULT NULL,
  `title` varchar(128) NOT NULL,
  `url` varchar(128) NOT NULL,
  `sequence` int(11) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `icon` varchar(128) DEFAULT NULL,
  `sortir_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_menu_item`
--

INSERT INTO `user_menu_item` (`id`, `menu_id`, `page_name`, `title`, `url`, `sequence`, `description`, `is_active`, `icon`, `sortir_number`) VALUES
(1, 2, NULL, 'Fleet', '#', NULL, NULL, 1, 'fa-toolbox', 2),
(2, 2, NULL, 'Transport', '#', NULL, NULL, 1, 'fa-truck', 1),
(3, 2, NULL, 'Report', '#', NULL, NULL, 1, 'fa-print', 3),
(4, 3, NULL, 'Master', '#', NULL, NULL, 1, 'fa-database', 1),
(5, 2, 'user_profile', 'User Profile', 'home/user_profile', NULL, NULL, 0, 'fa-circle', NULL),
(6, 2, 'logout', 'Logout', 'login/logout', NULL, NULL, 0, 'fa-circle', NULL),
(7, 2, NULL, 'Setting', '#', NULL, NULL, 1, 'fa-gear', 4),
(8, 1, NULL, 'Dashboard', '/dashboard', NULL, NULL, 1, 'fa-tachometer-alt', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_role`
--

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `role` varchar(128) NOT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_date` longtext DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_date` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_role`
--

INSERT INTO `user_role` (`id`, `role`, `is_active`, `created_by`, `created_date`, `updated_by`, `updated_date`) VALUES
(1, 'Admin General', 1, 119, NULL, 119, NULL),
(2, 'PPIC', 1, 119, NULL, 119, NULL),
(3, 'Supervisor Transport', 1, 119, NULL, 119, NULL),
(4, 'Admin POD', 1, 119, NULL, 119, NULL),
(5, 'Admin Monitoring', 1, 119, NULL, 119, NULL),
(6, 'Sales', 1, 119, NULL, 119, NULL),
(7, 'System Administrator', 1, 119, NULL, 142, '2024-07-28 14:09:09'),
(13, 'Admin Company', 1, 119, '2022-06-14 11:20:47', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_sub_menu`
--

CREATE TABLE `user_sub_menu` (
  `id` int(11) NOT NULL,
  `menu_item_id` int(11) NOT NULL,
  `page_name` varchar(128) NOT NULL,
  `title` varchar(128) NOT NULL,
  `url` varchar(128) NOT NULL,
  `sequence` int(11) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `icon` varchar(128) DEFAULT NULL,
  `sortir_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_sub_menu`
--

INSERT INTO `user_sub_menu` (`id`, `menu_item_id`, `page_name`, `title`, `url`, `sequence`, `description`, `is_active`, `icon`, `sortir_number`) VALUES
(1, 2, 'trucking_order', 'Trucking Order', 'transport/trucking_order', NULL, NULL, 1, NULL, 1),
(2, 2, 'transport_order', 'Transport Order', 'transport/transport_order', NULL, NULL, 1, NULL, 2),
(3, 2, 'route_planning', 'Route Planning', 'transport/route_planning', NULL, NULL, 1, NULL, 3),
(4, 2, 'traffic_monitoring', 'Traffic Monitoring', 'transport/traffic_monitoring', NULL, NULL, 1, NULL, 4),
(5, 2, 'pod', 'POD', 'transport/pod', NULL, NULL, 1, NULL, 5),
(6, 2, 'self_billing', 'Account Payable', 'transport/self_billing', NULL, NULL, 1, NULL, 6),
(7, 2, 'sales_invoice', 'Account Receivable', 'transport/sales_invoice', NULL, NULL, 1, NULL, 7),
(8, 3, '', 'Transport Report', '#', NULL, NULL, 0, NULL, NULL),
(9, 3, 'daily_monitoring_report', 'Daily Monitoring', 'report/daily_monitoring_report', NULL, NULL, 1, NULL, 1),
(10, 4, 'area', 'Areas', 'master_data/area', NULL, NULL, 1, NULL, 1),
(11, 4, 'customer', 'Master Address', 'master_data/customer', NULL, NULL, 1, NULL, 6),
(12, 4, 'vehicle', 'Vehicles', 'master_data/vehicle', NULL, NULL, 1, NULL, 10),
(13, 4, 'vehicle_type', 'Vehicle Types', 'master_data/vehicle_type', NULL, NULL, 1, NULL, 2),
(14, 4, 'transporter', 'Transporters', 'master_data/transporter', NULL, NULL, 1, NULL, 7),
(15, 4, 'driver', 'Drivers', 'master_data/driver', NULL, NULL, 1, NULL, 9),
(16, 4, 'transporter_rate', 'Transporter Rates', 'master_data/transporter_rate', NULL, NULL, 1, NULL, 13),
(17, 4, 'client_rate', 'Client Rates', 'master_data/client_rate', NULL, NULL, 1, NULL, 12),
(18, 4, 'client', 'Clients', 'master_data/client', NULL, NULL, 1, NULL, 5),
(19, 1, 'truck_accident', 'Truck Accident', 'fleet/truck_accident', NULL, NULL, 1, NULL, 2),
(20, 1, 'service_order', 'Service Order', 'fleet/service_order', NULL, NULL, 1, NULL, 1),
(21, 4, 'service_task', 'Service Tasks', 'master_data/service_task', NULL, NULL, 1, NULL, 14),
(22, 4, 'vendor', 'Vendor Services', 'master_data/vendor', NULL, NULL, 1, NULL, 4),
(23, 4, 'mechanic', 'Mechanics', 'master_data/mechanic', NULL, NULL, 1, NULL, 8),
(24, 3, 'operational_cost_report', 'Operational Cost', 'report/operational_cost_report', NULL, NULL, 1, NULL, 2),
(25, 2, 'tracking_driver', 'Tracking Driver', 'transport/tracking_driver', NULL, NULL, 1, NULL, 8),
(27, 7, 'mobile_version', 'Mobile Version', 'setting/mobile_version', NULL, NULL, 1, NULL, 3),
(28, 7, 'banner', 'Banner', 'setting/banner', NULL, NULL, 1, NULL, 6),
(29, 3, 'report_kbh_kirim_truck', 'KBH Kirim Truck', 'nxa/report_kbh_kirim_truck', NULL, NULL, 0, NULL, NULL),
(30, 3, 'profit_and_loss_report', 'P&L', 'report/profit_and_loss_report', NULL, NULL, 1, NULL, 4),
(31, 7, 'role', 'Role', 'setting/role', NULL, NULL, 1, NULL, 5),
(32, 7, 'master_user', 'User', 'setting/user', NULL, NULL, 1, NULL, 4),
(33, 7, 'company', 'Company', 'setting/company', NULL, NULL, 1, NULL, 7),
(34, 7, 'master_setting', 'Master Setting', 'setting/master_setting', NULL, '', 1, NULL, 1),
(35, 7, 'master_uom', 'Master UoM', 'setting/master_uom', NULL, NULL, 1, NULL, 2),
(36, 4, 'cost_component', 'Cost Component', 'master_data/cost_component', NULL, NULL, 1, NULL, 3),
(37, 2, 'add_invoice_retail', 'Add Invoice Retail', '#', NULL, '', 0, NULL, NULL),
(38, 2, 'add_self_billing_retail', 'Add Self Billing Retail', '#', NULL, '', 0, NULL, NULL),
(39, 4, 'accident_type', 'Accident Type', 'master_data/accident_type', NULL, NULL, 1, NULL, 11),
(40, 4, 'owned', 'Owned', 'master_data/owned', NULL, NULL, 1, NULL, 15),
(41, 3, 'kbh_send_truck_report', 'KBH Kirim Truck', 'report/kbh_send_truck_report', NULL, NULL, 1, NULL, 3),
(42, 4, 'dedicated_rate', 'Dedicated Rates', 'master_data/dedicated_rate', NULL, NULL, 1, NULL, 16);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indeks untuk tabel `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`currency_id`);

--
-- Indeks untuk tabel `tb_accident_type`
--
ALTER TABLE `tb_accident_type`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_areas`
--
ALTER TABLE `tb_areas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_areas_FK` (`id_company`);

--
-- Indeks untuk tabel `tb_banner`
--
ALTER TABLE `tb_banner`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_banner_FK` (`id_company`);

--
-- Indeks untuk tabel `tb_clients`
--
ALTER TABLE `tb_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_client_rates`
--
ALTER TABLE `tb_client_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_client_rates_FK` (`client_id`),
  ADD KEY `tb_client_rates_FK_1` (`origin_id`),
  ADD KEY `tb_client_rates_FK_2` (`destination_id`),
  ADD KEY `tb_client_rates_FK_3` (`type_id`);

--
-- Indeks untuk tabel `tb_company`
--
ALTER TABLE `tb_company`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_component`
--
ALTER TABLE `tb_component`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_component_entries`
--
ALTER TABLE `tb_component_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_component_entries_FK` (`id_manifest`),
  ADD KEY `tb_component_entries_FK_1` (`id_cost_component`);

--
-- Indeks untuk tabel `tb_customers`
--
ALTER TABLE `tb_customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_customers_FK` (`area_id`);

--
-- Indeks untuk tabel `tb_dedicated_rate`
--
ALTER TABLE `tb_dedicated_rate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_dedicated_rate_tb_vehicles_fk` (`id_vehicle`),
  ADD KEY `tb_dedicated_rate_tb_transporters_fk` (`id_transporter`),
  ADD KEY `tb_dedicated_rate_tb_clients_fk` (`client_id`);

--
-- Indeks untuk tabel `tb_drivers`
--
ALTER TABLE `tb_drivers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_drivers_FK` (`transporter_id`);

--
-- Indeks untuk tabel `tb_history_change_load`
--
ALTER TABLE `tb_history_change_load`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_history_change_load_FK` (`id_manifest`);

--
-- Indeks untuk tabel `tb_manifests`
--
ALTER TABLE `tb_manifests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_manifests_FK` (`vehicle_id`),
  ADD KEY `tb_manifests_FK_1` (`tr_id`),
  ADD KEY `tb_manifests_FK_2` (`driver_id`),
  ADD KEY `tb_manifests_FK_3` (`co_driver_id`),
  ADD KEY `tb_manifests_FK_4` (`mode`),
  ADD KEY `tb_manifests_FK_5` (`manifest_status`),
  ADD KEY `tb_manifests_FK_6` (`id_purchase_invoice`),
  ADD KEY `tb_manifests_FK_7` (`id_sales_invoice`);

--
-- Indeks untuk tabel `tb_manifest_status`
--
ALTER TABLE `tb_manifest_status`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_mechanics`
--
ALTER TABLE `tb_mechanics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_mechanics_FK` (`vendor_id`);

--
-- Indeks untuk tabel `tb_mobile_apps_version`
--
ALTER TABLE `tb_mobile_apps_version`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_notification`
--
ALTER TABLE `tb_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_pod`
--
ALTER TABLE `tb_pod`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_pod_FK` (`transport_order_id`),
  ADD KEY `tb_pod_FK_1` (`code`),
  ADD KEY `tb_pod_FK_2` (`pending_code`);

--
-- Indeks untuk tabel `tb_pod_code`
--
ALTER TABLE `tb_pod_code`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_pt_owned`
--
ALTER TABLE `tb_pt_owned`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_pt_owned_FK` (`taxable`);

--
-- Indeks untuk tabel `tb_purchase_invoice`
--
ALTER TABLE `tb_purchase_invoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_purchase_invoice_FK` (`transporter_id`),
  ADD KEY `tb_purchase_invoice_FK_1` (`client_id`);

--
-- Indeks untuk tabel `tb_ring_code`
--
ALTER TABLE `tb_ring_code`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_sales_invoice`
--
ALTER TABLE `tb_sales_invoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_sales_invoice_FK_1` (`client_id`);

--
-- Indeks untuk tabel `tb_service_order`
--
ALTER TABLE `tb_service_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_service_order_FK` (`vehicle_id`),
  ADD KEY `tb_service_order_FK_1` (`vendor_id`);

--
-- Indeks untuk tabel `tb_service_order_status`
--
ALTER TABLE `tb_service_order_status`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_service_order_type`
--
ALTER TABLE `tb_service_order_type`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_service_tasks`
--
ALTER TABLE `tb_service_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_service_task_entries`
--
ALTER TABLE `tb_service_task_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_service_task_entries_FK` (`id_service_order`),
  ADD KEY `tb_service_task_entries_FK_1` (`id_service_task`);

--
-- Indeks untuk tabel `tb_setting`
--
ALTER TABLE `tb_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_status_traffic_monitoring`
--
ALTER TABLE `tb_status_traffic_monitoring`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_tracking_driver`
--
ALTER TABLE `tb_tracking_driver`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_tracking_driver_FK_1` (`id_manifest`),
  ADD KEY `tb_tracking_driver_FK_2` (`id_driver`);

--
-- Indeks untuk tabel `tb_traffic_monitoring`
--
ALTER TABLE `tb_traffic_monitoring`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_traffic_monitoring_FK` (`transport_order_id`),
  ADD KEY `tb_traffic_monitoring_FK_1` (`point_id`),
  ADD KEY `tb_traffic_monitoring_FK_2` (`tm_status`);

--
-- Indeks untuk tabel `tb_transporters`
--
ALTER TABLE `tb_transporters`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_transporter_rates`
--
ALTER TABLE `tb_transporter_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_transporter_rates_FK` (`client_id`),
  ADD KEY `tb_transporter_rates_FK_1` (`transporter_id`),
  ADD KEY `tb_transporter_rates_FK_2` (`origin_id`),
  ADD KEY `tb_transporter_rates_FK_3` (`destination_id`),
  ADD KEY `tb_transporter_rates_FK_4` (`type_id`);

--
-- Indeks untuk tabel `tb_transport_mode`
--
ALTER TABLE `tb_transport_mode`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_transport_order`
--
ALTER TABLE `tb_transport_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_transport_order_FK` (`manifest_id`),
  ADD KEY `tb_transport_order_FK_1` (`client_id`),
  ADD KEY `tb_transport_order_FK_2` (`uom`),
  ADD KEY `tb_transport_order_FK_3` (`uom_v2`),
  ADD KEY `tb_transport_order_FK_4` (`origin_id`),
  ADD KEY `tb_transport_order_FK_5` (`dest_id`),
  ADD KEY `tb_transport_order_FK_6` (`order_type`);

--
-- Indeks untuk tabel `tb_trucking_order`
--
ALTER TABLE `tb_trucking_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_trucking_order_FK` (`client_id`),
  ADD KEY `tb_trucking_order_FK_1` (`transport_mode`),
  ADD KEY `tb_trucking_order_FK_2` (`origin_id`),
  ADD KEY `tb_trucking_order_FK_3` (`dest_id`),
  ADD KEY `tb_trucking_order_FK_4` (`origin_area_id`),
  ADD KEY `tb_trucking_order_FK_5` (`dest_area_id`);

--
-- Indeks untuk tabel `tb_truck_accident`
--
ALTER TABLE `tb_truck_accident`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_truck_accident_FK` (`vehicle_id`),
  ADD KEY `tb_truck_accident_FK_1` (`driver_id`),
  ADD KEY `tb_truck_accident_FK_2` (`client_id`),
  ADD KEY `tb_truck_accident_FK_3` (`accident_type`);

--
-- Indeks untuk tabel `tb_type_taxable`
--
ALTER TABLE `tb_type_taxable`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_uom`
--
ALTER TABLE `tb_uom`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_vehicles`
--
ALTER TABLE `tb_vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_vehicles_FK` (`driver`),
  ADD KEY `tb_vehicles_FK_1` (`co_driver`),
  ADD KEY `tb_vehicles_FK_2` (`transporter_id`),
  ADD KEY `tb_vehicles_FK_3` (`type`),
  ADD KEY `tb_vehicles_FK_4` (`subcon`);

--
-- Indeks untuk tabel `tb_vehicle_types`
--
ALTER TABLE `tb_vehicle_types`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_vendors`
--
ALTER TABLE `tb_vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_FK` (`id_company`),
  ADD KEY `user_FK_1` (`role_id`);

--
-- Indeks untuk tabel `user_access_menu`
--
ALTER TABLE `user_access_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_access_menu_FK` (`role_id`),
  ADD KEY `user_access_menu_FK_1` (`menu_id`);

--
-- Indeks untuk tabel `user_access_menu_item`
--
ALTER TABLE `user_access_menu_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_access_menu_item_FK` (`role_id`),
  ADD KEY `user_access_menu_item_FK_1` (`menu_item_id`);

--
-- Indeks untuk tabel `user_access_sub_menu`
--
ALTER TABLE `user_access_sub_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_access_sub_menu_FK` (`role_id`),
  ADD KEY `user_access_sub_menu_FK_1` (`sub_menu_id`);

--
-- Indeks untuk tabel `user_menu`
--
ALTER TABLE `user_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user_menu_item`
--
ALTER TABLE `user_menu_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_menu_item_FK` (`menu_id`);

--
-- Indeks untuk tabel `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_sub_menu_FK` (`menu_item_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `currency`
--
ALTER TABLE `currency`
  MODIFY `currency_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `tb_accident_type`
--
ALTER TABLE `tb_accident_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_areas`
--
ALTER TABLE `tb_areas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_banner`
--
ALTER TABLE `tb_banner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_clients`
--
ALTER TABLE `tb_clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_client_rates`
--
ALTER TABLE `tb_client_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_company`
--
ALTER TABLE `tb_company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_component`
--
ALTER TABLE `tb_component`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_component_entries`
--
ALTER TABLE `tb_component_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_customers`
--
ALTER TABLE `tb_customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_dedicated_rate`
--
ALTER TABLE `tb_dedicated_rate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_drivers`
--
ALTER TABLE `tb_drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_history_change_load`
--
ALTER TABLE `tb_history_change_load`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_manifests`
--
ALTER TABLE `tb_manifests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_manifest_status`
--
ALTER TABLE `tb_manifest_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tb_mechanics`
--
ALTER TABLE `tb_mechanics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_mobile_apps_version`
--
ALTER TABLE `tb_mobile_apps_version`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_notification`
--
ALTER TABLE `tb_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_pod`
--
ALTER TABLE `tb_pod`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_pod_code`
--
ALTER TABLE `tb_pod_code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_pt_owned`
--
ALTER TABLE `tb_pt_owned`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_purchase_invoice`
--
ALTER TABLE `tb_purchase_invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_ring_code`
--
ALTER TABLE `tb_ring_code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `tb_sales_invoice`
--
ALTER TABLE `tb_sales_invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_service_order`
--
ALTER TABLE `tb_service_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_service_order_status`
--
ALTER TABLE `tb_service_order_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tb_service_order_type`
--
ALTER TABLE `tb_service_order_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tb_service_tasks`
--
ALTER TABLE `tb_service_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_service_task_entries`
--
ALTER TABLE `tb_service_task_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_setting`
--
ALTER TABLE `tb_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `tb_status_traffic_monitoring`
--
ALTER TABLE `tb_status_traffic_monitoring`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `tb_tracking_driver`
--
ALTER TABLE `tb_tracking_driver`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_traffic_monitoring`
--
ALTER TABLE `tb_traffic_monitoring`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_transporters`
--
ALTER TABLE `tb_transporters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_transporter_rates`
--
ALTER TABLE `tb_transporter_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_transport_mode`
--
ALTER TABLE `tb_transport_mode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tb_transport_order`
--
ALTER TABLE `tb_transport_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_trucking_order`
--
ALTER TABLE `tb_trucking_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_truck_accident`
--
ALTER TABLE `tb_truck_accident`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_type_taxable`
--
ALTER TABLE `tb_type_taxable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tb_uom`
--
ALTER TABLE `tb_uom`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `tb_vehicles`
--
ALTER TABLE `tb_vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_vehicle_types`
--
ALTER TABLE `tb_vehicle_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_vendors`
--
ALTER TABLE `tb_vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `user_access_menu`
--
ALTER TABLE `user_access_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `user_access_menu_item`
--
ALTER TABLE `user_access_menu_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `user_access_sub_menu`
--
ALTER TABLE `user_access_sub_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `user_menu`
--
ALTER TABLE `user_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `user_menu_item`
--
ALTER TABLE `user_menu_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT untuk tabel `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tb_client_rates`
--
ALTER TABLE `tb_client_rates`
  ADD CONSTRAINT `tb_client_rates_FK` FOREIGN KEY (`client_id`) REFERENCES `tb_clients` (`id`),
  ADD CONSTRAINT `tb_client_rates_FK_1` FOREIGN KEY (`origin_id`) REFERENCES `tb_areas` (`id`),
  ADD CONSTRAINT `tb_client_rates_FK_2` FOREIGN KEY (`destination_id`) REFERENCES `tb_areas` (`id`),
  ADD CONSTRAINT `tb_client_rates_FK_3` FOREIGN KEY (`type_id`) REFERENCES `tb_vehicle_types` (`id`);

--
-- Ketidakleluasaan untuk tabel `tb_component_entries`
--
ALTER TABLE `tb_component_entries`
  ADD CONSTRAINT `tb_component_entries_FK` FOREIGN KEY (`id_manifest`) REFERENCES `tb_manifests` (`id`),
  ADD CONSTRAINT `tb_component_entries_FK_1` FOREIGN KEY (`id_cost_component`) REFERENCES `tb_component` (`id`);

--
-- Ketidakleluasaan untuk tabel `tb_customers`
--
ALTER TABLE `tb_customers`
  ADD CONSTRAINT `tb_customers_FK` FOREIGN KEY (`area_id`) REFERENCES `tb_areas` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Ketidakleluasaan untuk tabel `tb_dedicated_rate`
--
ALTER TABLE `tb_dedicated_rate`
  ADD CONSTRAINT `tb_dedicated_rate_tb_clients_fk` FOREIGN KEY (`client_id`) REFERENCES `tb_clients` (`id`),
  ADD CONSTRAINT `tb_dedicated_rate_tb_transporters_fk` FOREIGN KEY (`id_transporter`) REFERENCES `tb_transporters` (`id`),
  ADD CONSTRAINT `tb_dedicated_rate_tb_vehicles_fk` FOREIGN KEY (`id_vehicle`) REFERENCES `tb_vehicles` (`id`);

--
-- Ketidakleluasaan untuk tabel `tb_drivers`
--
ALTER TABLE `tb_drivers`
  ADD CONSTRAINT `tb_drivers_FK` FOREIGN KEY (`transporter_id`) REFERENCES `tb_transporters` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Ketidakleluasaan untuk tabel `tb_history_change_load`
--
ALTER TABLE `tb_history_change_load`
  ADD CONSTRAINT `tb_history_change_load_FK` FOREIGN KEY (`id_manifest`) REFERENCES `tb_manifests` (`id`);

--
-- Ketidakleluasaan untuk tabel `tb_manifests`
--
ALTER TABLE `tb_manifests`
  ADD CONSTRAINT `tb_manifests_FK` FOREIGN KEY (`vehicle_id`) REFERENCES `tb_vehicles` (`id`),
  ADD CONSTRAINT `tb_manifests_FK_1` FOREIGN KEY (`tr_id`) REFERENCES `tb_trucking_order` (`id`),
  ADD CONSTRAINT `tb_manifests_FK_2` FOREIGN KEY (`driver_id`) REFERENCES `tb_drivers` (`id`),
  ADD CONSTRAINT `tb_manifests_FK_3` FOREIGN KEY (`co_driver_id`) REFERENCES `tb_drivers` (`id`),
  ADD CONSTRAINT `tb_manifests_FK_4` FOREIGN KEY (`mode`) REFERENCES `tb_transport_mode` (`id`),
  ADD CONSTRAINT `tb_manifests_FK_5` FOREIGN KEY (`manifest_status`) REFERENCES `tb_manifest_status` (`id`),
  ADD CONSTRAINT `tb_manifests_FK_6` FOREIGN KEY (`id_purchase_invoice`) REFERENCES `tb_purchase_invoice` (`id`),
  ADD CONSTRAINT `tb_manifests_FK_7` FOREIGN KEY (`id_sales_invoice`) REFERENCES `tb_sales_invoice` (`id`);

--
-- Ketidakleluasaan untuk tabel `tb_mechanics`
--
ALTER TABLE `tb_mechanics`
  ADD CONSTRAINT `tb_mechanics_FK` FOREIGN KEY (`vendor_id`) REFERENCES `tb_vendors` (`id`);

--
-- Ketidakleluasaan untuk tabel `tb_pod`
--
ALTER TABLE `tb_pod`
  ADD CONSTRAINT `tb_pod_FK` FOREIGN KEY (`transport_order_id`) REFERENCES `tb_transport_order` (`id`),
  ADD CONSTRAINT `tb_pod_FK_1` FOREIGN KEY (`code`) REFERENCES `tb_pod_code` (`id`),
  ADD CONSTRAINT `tb_pod_FK_2` FOREIGN KEY (`pending_code`) REFERENCES `tb_pod_code` (`id`);

--
-- Ketidakleluasaan untuk tabel `tb_pt_owned`
--
ALTER TABLE `tb_pt_owned`
  ADD CONSTRAINT `tb_pt_owned_FK` FOREIGN KEY (`taxable`) REFERENCES `tb_type_taxable` (`id`);

--
-- Ketidakleluasaan untuk tabel `tb_purchase_invoice`
--
ALTER TABLE `tb_purchase_invoice`
  ADD CONSTRAINT `tb_purchase_invoice_FK` FOREIGN KEY (`transporter_id`) REFERENCES `tb_transporters` (`id`),
  ADD CONSTRAINT `tb_purchase_invoice_FK_1` FOREIGN KEY (`client_id`) REFERENCES `tb_clients` (`id`);

--
-- Ketidakleluasaan untuk tabel `tb_sales_invoice`
--
ALTER TABLE `tb_sales_invoice`
  ADD CONSTRAINT `tb_sales_invoice_FK_1` FOREIGN KEY (`client_id`) REFERENCES `tb_clients` (`id`);

--
-- Ketidakleluasaan untuk tabel `tb_service_order`
--
ALTER TABLE `tb_service_order`
  ADD CONSTRAINT `tb_service_order_FK` FOREIGN KEY (`vehicle_id`) REFERENCES `tb_vehicles` (`id`),
  ADD CONSTRAINT `tb_service_order_FK_1` FOREIGN KEY (`vendor_id`) REFERENCES `tb_vendors` (`id`);

--
-- Ketidakleluasaan untuk tabel `tb_service_task_entries`
--
ALTER TABLE `tb_service_task_entries`
  ADD CONSTRAINT `tb_service_task_entries_FK` FOREIGN KEY (`id_service_order`) REFERENCES `tb_service_order` (`id`),
  ADD CONSTRAINT `tb_service_task_entries_FK_1` FOREIGN KEY (`id_service_task`) REFERENCES `tb_service_tasks` (`id`);

--
-- Ketidakleluasaan untuk tabel `tb_tracking_driver`
--
ALTER TABLE `tb_tracking_driver`
  ADD CONSTRAINT `tb_tracking_driver_FK_1` FOREIGN KEY (`id_manifest`) REFERENCES `tb_manifests` (`id`),
  ADD CONSTRAINT `tb_tracking_driver_FK_2` FOREIGN KEY (`id_driver`) REFERENCES `tb_drivers` (`id`);

--
-- Ketidakleluasaan untuk tabel `tb_traffic_monitoring`
--
ALTER TABLE `tb_traffic_monitoring`
  ADD CONSTRAINT `tb_traffic_monitoring_FK` FOREIGN KEY (`transport_order_id`) REFERENCES `tb_transport_order` (`id`),
  ADD CONSTRAINT `tb_traffic_monitoring_FK_1` FOREIGN KEY (`point_id`) REFERENCES `tb_customers` (`id`),
  ADD CONSTRAINT `tb_traffic_monitoring_FK_2` FOREIGN KEY (`tm_status`) REFERENCES `tb_status_traffic_monitoring` (`id`);

--
-- Ketidakleluasaan untuk tabel `tb_transporter_rates`
--
ALTER TABLE `tb_transporter_rates`
  ADD CONSTRAINT `tb_transporter_rates_FK` FOREIGN KEY (`client_id`) REFERENCES `tb_clients` (`id`),
  ADD CONSTRAINT `tb_transporter_rates_FK_1` FOREIGN KEY (`transporter_id`) REFERENCES `tb_transporters` (`id`),
  ADD CONSTRAINT `tb_transporter_rates_FK_2` FOREIGN KEY (`origin_id`) REFERENCES `tb_areas` (`id`),
  ADD CONSTRAINT `tb_transporter_rates_FK_3` FOREIGN KEY (`destination_id`) REFERENCES `tb_areas` (`id`),
  ADD CONSTRAINT `tb_transporter_rates_FK_4` FOREIGN KEY (`type_id`) REFERENCES `tb_vehicle_types` (`id`);

--
-- Ketidakleluasaan untuk tabel `tb_transport_order`
--
ALTER TABLE `tb_transport_order`
  ADD CONSTRAINT `tb_transport_order_FK` FOREIGN KEY (`manifest_id`) REFERENCES `tb_manifests` (`id`),
  ADD CONSTRAINT `tb_transport_order_FK_1` FOREIGN KEY (`client_id`) REFERENCES `tb_clients` (`id`),
  ADD CONSTRAINT `tb_transport_order_FK_2` FOREIGN KEY (`uom`) REFERENCES `tb_uom` (`id`),
  ADD CONSTRAINT `tb_transport_order_FK_3` FOREIGN KEY (`uom_v2`) REFERENCES `tb_uom` (`id`),
  ADD CONSTRAINT `tb_transport_order_FK_4` FOREIGN KEY (`origin_id`) REFERENCES `tb_customers` (`id`),
  ADD CONSTRAINT `tb_transport_order_FK_5` FOREIGN KEY (`dest_id`) REFERENCES `tb_customers` (`id`),
  ADD CONSTRAINT `tb_transport_order_FK_6` FOREIGN KEY (`order_type`) REFERENCES `tb_ring_code` (`id`);

--
-- Ketidakleluasaan untuk tabel `tb_trucking_order`
--
ALTER TABLE `tb_trucking_order`
  ADD CONSTRAINT `tb_trucking_order_FK` FOREIGN KEY (`client_id`) REFERENCES `tb_clients` (`id`),
  ADD CONSTRAINT `tb_trucking_order_FK_1` FOREIGN KEY (`transport_mode`) REFERENCES `tb_transport_mode` (`id`),
  ADD CONSTRAINT `tb_trucking_order_FK_2` FOREIGN KEY (`origin_id`) REFERENCES `tb_customers` (`id`),
  ADD CONSTRAINT `tb_trucking_order_FK_3` FOREIGN KEY (`dest_id`) REFERENCES `tb_customers` (`id`),
  ADD CONSTRAINT `tb_trucking_order_FK_4` FOREIGN KEY (`origin_area_id`) REFERENCES `tb_areas` (`id`),
  ADD CONSTRAINT `tb_trucking_order_FK_5` FOREIGN KEY (`dest_area_id`) REFERENCES `tb_areas` (`id`);

--
-- Ketidakleluasaan untuk tabel `tb_truck_accident`
--
ALTER TABLE `tb_truck_accident`
  ADD CONSTRAINT `tb_truck_accident_FK` FOREIGN KEY (`vehicle_id`) REFERENCES `tb_vehicles` (`id`),
  ADD CONSTRAINT `tb_truck_accident_FK_1` FOREIGN KEY (`driver_id`) REFERENCES `tb_drivers` (`id`),
  ADD CONSTRAINT `tb_truck_accident_FK_2` FOREIGN KEY (`client_id`) REFERENCES `tb_clients` (`id`),
  ADD CONSTRAINT `tb_truck_accident_FK_3` FOREIGN KEY (`accident_type`) REFERENCES `tb_accident_type` (`id`);

--
-- Ketidakleluasaan untuk tabel `tb_vehicles`
--
ALTER TABLE `tb_vehicles`
  ADD CONSTRAINT `tb_vehicles_FK` FOREIGN KEY (`driver`) REFERENCES `tb_drivers` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `tb_vehicles_FK_1` FOREIGN KEY (`co_driver`) REFERENCES `tb_drivers` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `tb_vehicles_FK_2` FOREIGN KEY (`transporter_id`) REFERENCES `tb_transporters` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `tb_vehicles_FK_3` FOREIGN KEY (`type`) REFERENCES `tb_vehicle_types` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `tb_vehicles_FK_4` FOREIGN KEY (`subcon`) REFERENCES `tb_transporters` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Ketidakleluasaan untuk tabel `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_FK` FOREIGN KEY (`id_company`) REFERENCES `tb_company` (`id`),
  ADD CONSTRAINT `user_FK_1` FOREIGN KEY (`role_id`) REFERENCES `user_role` (`id`);

--
-- Ketidakleluasaan untuk tabel `user_access_menu`
--
ALTER TABLE `user_access_menu`
  ADD CONSTRAINT `user_access_menu_FK` FOREIGN KEY (`role_id`) REFERENCES `user_role` (`id`),
  ADD CONSTRAINT `user_access_menu_FK_1` FOREIGN KEY (`menu_id`) REFERENCES `user_menu` (`id`);

--
-- Ketidakleluasaan untuk tabel `user_access_menu_item`
--
ALTER TABLE `user_access_menu_item`
  ADD CONSTRAINT `user_access_menu_item_FK` FOREIGN KEY (`role_id`) REFERENCES `user_role` (`id`),
  ADD CONSTRAINT `user_access_menu_item_FK_1` FOREIGN KEY (`menu_item_id`) REFERENCES `user_menu_item` (`id`);

--
-- Ketidakleluasaan untuk tabel `user_access_sub_menu`
--
ALTER TABLE `user_access_sub_menu`
  ADD CONSTRAINT `user_access_sub_menu_FK` FOREIGN KEY (`role_id`) REFERENCES `user_role` (`id`),
  ADD CONSTRAINT `user_access_sub_menu_FK_1` FOREIGN KEY (`sub_menu_id`) REFERENCES `user_sub_menu` (`id`);

--
-- Ketidakleluasaan untuk tabel `user_menu_item`
--
ALTER TABLE `user_menu_item`
  ADD CONSTRAINT `user_menu_item_FK` FOREIGN KEY (`menu_id`) REFERENCES `user_menu` (`id`);

--
-- Ketidakleluasaan untuk tabel `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  ADD CONSTRAINT `user_sub_menu_FK` FOREIGN KEY (`menu_item_id`) REFERENCES `user_menu_item` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
