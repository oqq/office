<!DOCTYPE html>
<html lang="de">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    <style>
        @page {
            margin: 1.5cm;
        }
        body {
            font-family: helvetica, arial, sans-serif;
            font-size: 10pt;
            line-height: 1.3;
        }
        .large {
            font-size: 14pt;
        }
        .small {
            font-size: 9pt;
        }
        .tiny {
            font-size: 7pt;
        }
        .bold {
            font-weight: bold;
        }
        .light {
            font-weight: lighter;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
        }
        td {
            padding: 2px;
        }
        tr.bottom-line td, tr.bottom-line th {
            border-bottom: .5px #818181 solid;
        }
        .top-line {
            border-top: 2px #487c9f solid;
        }
        .blue {
            color: #487c9f;
        }
        .grey {
            color: #515151;
        }
        .left {
            text-align: left;
        }
        .right {
            text-align: right;
        }
        .paddy td, .paddy th {
            padding: 5px 0;
        }
    </style>
</head>

<body>

    <div class="bold large blue top-line" style="padding: 5px 0">
        <?=$data->company->name?>
    </div>

    <div class="light" style="margin-top: 1.5cm">
        <table style="width: 40%; float: left;">
            <tr class="bottom-line">
                <td class="tiny">
                    <?=$data->company->address->header?>
                </td>
            </tr>
            <tr>
                <td><?=$data->customer->name?></td>
            </tr>
            <tr>
                <td><?=$data->customer->address->street?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                    <?=$data->customer->address->postcode?>
                    <?=$data->customer->address->city?>
                </td>
            </tr>
        </table>

        <table style="width: 40%; float: right;" class="small">
            <tr class="bold">
                <td>Rechnungsdatum</td>
                <td class="right"><?=$data->invoice_date?></td>
            </tr>
            <tr>
                <td>Leistungsdatum</td>
                <td class="right"><?=$data->performance_date?></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Rechungsnummer</td>
                <td class="right"><?=$data->invoice_number?></td>
            </tr>
            <tr>
                <td>Kundennummer</td>
                <td class="right"><?=$data->customer->number?></td>
            </tr>
            <tr>
                <td>Steuernummer</td>
                <td class="right"><?=$data->company->tax_number?></td>
            </tr>
        </table>
    </div>

    <div style="clear: both">&nbsp;</div>

    <div class="bold large" style="margin-top: 3cm">
        Rechnung <?=$data->invoice_number?>
    </div>

    <table class="grey paddy">
        <thead>
            <tr class="bottom-line">
                <th class="left">Beschreibung</th>
                <th class="right" style="width: 3cm;">Menge</th>
                <th class="right" style="width: 3cm;">Einzelpreis</th>
                <th class="right" style="width: 3cm;">Gesamt</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data->invoice_lines as $invoice_line): ?>
                <tr class="bottom-line">
                    <td class="left"><?=$invoice_line->description?></td>
                    <td class="right"><?=$invoice_line->amount?></td>
                    <td class="right"><?=$invoice_line->single_price?></td>
                    <td class="right"><?=$invoice_line->total_price?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="right">
                <td></td>
                <td></td>
                <td>Netto</td>
                <td><?=$data->net_price?></td>
            </tr>
            <tr class="right">
                <td></td>
                <td></td>
                <td><?=$data->tax_rate?> USt.</td>
                <td><?=$data->tax_price?></td>
            </tr>
            <tr class="bold right">
                <td></td>
                <td></td>
                <td>Gesamt</td>
                <td><?=$data->total_price?></td>
            </tr>
        </tfoot>
    </table>

    <p class="light" style="margin-top: 2cm">
        Bitte überweisen Sie den Gesamtbetrag ohne Abzüge <b><?=$data->payment_term?></b><br>
        auf mein Bankkonto <b><?=$data->company->bank->account_number?></b> bei der <b><?=$data->company->bank->name?></b>.<br>
        <br>
        Vielen Dank für Ihren Auftrag.<br>
        <br>
        Ich
    </p>

    <div style="position: absolute; bottom: 1cm; width: 100%;">
        <div class="tiny light top-line">
            <table style="width: 32%; float: left; text-align: left;">
                <tr>
                    <td><?=$data->company->name?></td>
                </tr>
                <tr>
                    <td><?=$data->company->address->street?></td>
                </tr>
                <tr>
                    <td>
                        <?=$data->company->address->postcode?>
                        <?=$data->company->address->city?>
                    </td>
                </tr>
            </table>

            <table style="width: 32%; float: left; text-align: center;">
                <tr>
                    <td><?=$data->company->phone_number?></td>
                </tr>
                <tr>
                    <td><?=$data->company->email_address?></td>
                </tr>
            </table>

            <table style="width: 32%; float: right; text-align: right;">
                <tr>
                    <td><?=$data->company->bank->name?></td>
                </tr>
                <tr>
                    <td><?=$data->company->bank->account_number?></td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
