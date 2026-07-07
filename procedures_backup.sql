-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: arkadialp_dwh
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping routines for database 'arkadialp_dwh'
--
/*!50003 DROP PROCEDURE IF EXISTS `JalankanPipaETL` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `JalankanPipaETL`()
BEGIN
    -- 1. Matikan proteksi foreign key sementara
    SET SESSION FOREIGN_KEY_CHECKS = 0;
    SET SESSION sql_mode = '';

    -- 2. Kosongkan Tabel Fakta dan Dimensi Waktu
    TRUNCATE TABLE arkadialp_dwh.dwh_fact_penjualan;
    TRUNCATE TABLE arkadialp_dwh.dwh_dim_waktu;

    -- 3. Sinkronisasi Dimensi Produk & Cabang dari OLTP
    INSERT IGNORE INTO arkadialp_dwh.dwh_dim_produk (id_dim_produk, nama_produk)
    SELECT id, nama FROM arkadialp_oltp.laptops;

    INSERT IGNORE INTO arkadialp_dwh.dwh_dim_cabang (id_dim_cabang, nama_cabang)
    VALUES 
    (1, 'Parigi'),
    (2, 'Palu'),
    (3, 'Donggala');

    -- 4. Sinkronisasi Dimensi Waktu
    INSERT INTO arkadialp_dwh.dwh_dim_waktu (id_waktu, tanggal, hari, bulan, nama_bulan, kuartal, tahun)
    SELECT 
        ROW_NUMBER() OVER (ORDER BY tanggal ASC) AS id_waktu,
        tanggal,
        CASE DAYNAME(tanggal)
            WHEN 'Sunday' THEN 'Minggu' WHEN 'Monday' THEN 'Senin'
            WHEN 'Tuesday' THEN 'Selasa' WHEN 'Wednesday' THEN 'Rabu'
            WHEN 'Thursday' THEN 'Kamis' WHEN 'Friday' THEN 'Jumat'
            WHEN 'Saturday' THEN 'Sabtu'
        END AS hari,
        MONTH(tanggal) AS bulan,
        CASE MONTH(tanggal)
            WHEN 1 THEN 'Januari' WHEN 2 THEN 'Februari' WHEN 3 THEN 'Maret'
            WHEN 4 THEN 'April' WHEN 5 THEN 'Mei' WHEN 6 THEN 'Juni'
            WHEN 7 THEN 'Juli' WHEN 8 THEN 'Agustus' WHEN 9 THEN 'September'
            WHEN 10 THEN 'Oktober' WHEN 11 THEN 'November' WHEN 12 THEN 'Desember'
        END AS nama_bulan,
        QUARTER(tanggal) AS kuartal,
        YEAR(tanggal) AS tahun
    FROM (
        SELECT DISTINCT DATE(tanggal) AS tanggal 
        FROM arkadialp_oltp.penjualan
        WHERE tanggal IS NOT NULL
    ) AS unik_tanggal;

    -- 5. Masukkan Data ke Tabel Fakta Penjualan
    INSERT INTO arkadialp_dwh.dwh_fact_penjualan (
        id_waktu, id_dim_produk, id_dim_cabang, id_penjualan, metode_pembayaran,
        qty, harga_jual, harga_modal, subtotal, profit, created_at
    )
    SELECT 
        COALESCE(w.id_waktu, 1) AS id_waktu,
        dp.id_produk AS id_dim_produk,
        COALESCE(u.id_cabang, 1) AS id_dim_cabang,
        dp.id_penjualan,
        p.metode_pembayaran,
        dp.qty AS qty,
        l.harga AS harga_jual,
        (l.harga * 0.80) AS harga_modal,
        (l.harga * dp.qty) AS subtotal,
        ((l.harga - (l.harga * 0.80)) * dp.qty) AS profit,
        NOW()
    FROM arkadialp_oltp.detail_penjualan dp
    JOIN arkadialp_oltp.laptops l ON dp.id_produk = l.id
    JOIN arkadialp_oltp.penjualan p ON dp.id_penjualan = p.id_penjualan
    LEFT JOIN arkadialp_oltp.users u ON p.id_user = u.id_user
    LEFT JOIN arkadialp_dwh.dwh_dim_waktu w ON DATE(p.tanggal) = w.tanggal;

    -- 6. Nyalakan kembali proteksi foreign key
    SET SESSION FOREIGN_KEY_CHECKS = 1;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-07  3:43:57
