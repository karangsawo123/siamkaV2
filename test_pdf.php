<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();
$dompdf->loadHtml('<h1>Halo Dunia PDF!</h1>');
$dompdf->render();
$dompdf->stream("contoh.pdf", ["Attachment" => false]);
