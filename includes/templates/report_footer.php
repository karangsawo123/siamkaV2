<?php
// =======================================================
// FILE: includes/templates/report_footer.php
// Fungsi: Footer laporan profesional (SIAMKA)
// =======================================================

if (!defined('SECURE')) {
    die('Direct access not allowed');
}

if (!function_exists('render_report_footer')) {
    /**
     * Render footer laporan SIAMKA
     */
    function render_report_footer()
    {
        ?>
        <div class="report-footer" style="margin-top: 60px;">

            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px;">
                <!-- Kiri -->
                <div style="width: 50%; font-size: 12px; color: #666;">
                    <p><strong>Dicetak oleh SIAMKA</strong></p>
                    <p>Sistem Informasi Aset Manajemen Kampus</p>
                    <p><?= date('d M Y, H:i') ?></p>
                </div>

                <!-- Kanan (Tanda tangan) -->
                <div style="width: 40%; text-align: center; font-size: 12px; color: #333;">
                    <p><strong>Disetujui oleh,</strong></p>
                    <br><br><br>
                    <p style="font-weight: bold; text-decoration: underline; margin-bottom: 0;">Kepala Bagian Aset</p>
                    <p style="margin-top: 2px;">Universitas Hogwarts</p>
                </div>
            </div>

            <!-- Garis bawah + nomor halaman -->
            <hr style="border: none; border-top: 1px solid #aaa; margin-bottom: 5px;">
            <!-- <div style="text-align: center; font-size: 11px; color: #777;">
                Halaman <span class="page-number"></span> dari <span class="total-pages"></span>
            </div> -->
        </div>

        <!-- <script type="text/php">
            // =====================================================
            // SCRIPT DOMPDF: Menambahkan nomor halaman otomatis
            // =====================================================
            if (isset($pdf)) {
                $font = $fontMetrics->get_font("Helvetica", "normal");
                $size = 9;
                $y = $pdf->get_height() - 28;
                $x = $pdf->get_width() / 2 - 30;
                $pdf->page_text($x, $y, "Halaman {PAGE_NUM} dari {PAGE_COUNT}", $font, $size, [0, 0, 0]);
            }
        </script> -->
        <?php
    }
}
?>
