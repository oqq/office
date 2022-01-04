<?php

declare(strict_types=1);

use Dompdf\Dompdf;

require __DIR__ . '/../vendor/autoload.php';

const INVOICE_DIRECTORY = __DIR__. '/../data/invoices';
const INVOICE_TEMPLATE_FILE = __DIR__. '/../templates/pdf/invoice.html';

$options = [
    #'debugKeepTemp' => true,
    #'debugCss' => true,
    #'debugLayout' => true,
];

$dompdf = new Dompdf($options);
$dompdf->setPaper('A4');
$dompdf->loadHtml(\file_get_contents(INVOICE_TEMPLATE_FILE));
$dompdf->render();

if (!\is_dir(INVOICE_DIRECTORY)) {
    if (!mkdir(INVOICE_DIRECTORY, recursive: true) && !is_dir(INVOICE_DIRECTORY)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', INVOICE_DIRECTORY));
    }
}

\file_put_contents(__DIR__ . '/../data/invoices/invoice.pdf', $dompdf->output());
