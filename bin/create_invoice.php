<?php

declare(strict_types=1);

use Dompdf\Dompdf;

require __DIR__ . '/../vendor/autoload.php';

const PDF_TEMPLATE = __DIR__ . '/../templates/pdf/invoice.php';
const INVOICE_DIRECTORY = __DIR__. '/../data/invoices';

$renderTemplate = static function (string $template, array $values): string {
    $data = \json_decode(\json_encode($values));

    \ob_start();
    include $template;
    $content = \ob_get_clean();

    if (false === $content) {
        throw new RuntimeException('error parsing template');
    }

    return $content;
};

$renderPdf = static function (string $content): string {
    $dompdf = new Dompdf([
        #'debugKeepTemp' => true,
        #'debugCss' => true,
        #'debugLayout' => true,
    ]);

    $dompdf->setPaper('A4');
    $dompdf->loadHtml($content);

    $errorReportingLevel = \error_reporting(E_ALL & ~E_DEPRECATED);
    $dompdf->render();
    \error_reporting($errorReportingLevel);

    return $dompdf->output();
};

$hours = static fn (float $value): string => \number_format($value, 2, ',', '.') . ' h';
$euro = static fn (float $value): string => \number_format($value, 2, ',', '.') . ' €';
$percent = static fn (float $value): string => \number_format($value, 2, ',', '') . ' %';

$invoiceValues = [
    'company' => [
        'name' => 'Ich AG | IT-Beratungen',
        'phone_number' => '+49 123 12345678',
        'email_address' => 'foo@bar.de',
        'tax_number' => '101/11/1234',
        'address' => [
            'header' => 'Ich AG | Hauptstraße 2, 01234 Hauptstadt',
            'street' => 'Hauptstraße 1',
            'postcode' => '01234',
            'city' => 'Hauptstadt',
        ],
        'bank' => [
            'name' => 'XYZ Bank',
            'account_number' => 'DEXXXXXXXXXXXXXXXXXXXX',
        ],
    ],
    'customer' => [
        'name' => 'Kundenname',
        'number' => '2021001',
        'address' => [
            'street' => 'Hauptstraße 1',
            'postcode' => '01234',
            'city' => 'Hauptstadt',
        ],
    ],
    'invoice_date' => '03.01.2022',
    'performance_date' => 'Dezember 2021',
    'invoice_number' => '20220101',
    'payment_term' => 'innerhalb von 14 Tagen',
    'invoice_lines' => [],
    'net_price' => '',
    'tax_rate' => '',
    'tax_price' => '',
    'total_price' => '',
];

$items = [
    [
        'description' => 'Umsetzung Projekt "diverses"',
        'hours' => 84.50,
        'single_price' => 100.00,
    ],
    [
        'description' => 'Umsetzung Projekt "verschiedenes"',
        'hours' => 8.00,
        'single_price' => 100.00,
    ],
];

$netPrice = 0.00;

foreach ($items as $item) {
    $totalPrice = $item['hours'] * $item['single_price'];
    $netPrice += $totalPrice;

    $invoiceValues['invoice_lines'][] = [
        'description' => $item['description'],
        'amount' => $hours($item['hours']),
        'single_price' => $euro($item['single_price']),
        'total_price' => $euro($totalPrice),
    ];
}

$taxRate = 19.00;
$taxPrice = $netPrice * $taxRate / 100;
$totalPrice = $netPrice + $taxPrice;

$invoiceValues['net_price'] = $euro($netPrice);
$invoiceValues['tax_rate'] = $percent($taxRate);
$invoiceValues['tax_price'] = $euro($taxPrice);
$invoiceValues['total_price'] = $euro($taxPrice);

$invoiceContent = $renderTemplate(PDF_TEMPLATE, $invoiceValues);

if (!\is_dir(INVOICE_DIRECTORY)) {
    if (!mkdir(INVOICE_DIRECTORY, recursive: true) && !is_dir(INVOICE_DIRECTORY)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', INVOICE_DIRECTORY));
    }
}

\file_put_contents(INVOICE_DIRECTORY . '/invoice.pdf', $renderPdf($invoiceContent));
