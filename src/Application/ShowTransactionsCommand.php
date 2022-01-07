<?php

declare(strict_types=1);

namespace Oqq\Office\Application;

use Fhp\Model\SEPAAccount;
use Oqq\Office\Service\Banking;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableCellStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ShowTransactionsCommand extends Command
{
    protected static $defaultName = 'banking:transactions';

    private Banking $banking;
    private SEPAAccount $account;

    public function __construct(Banking $banking, SEPAAccount $account)
    {
        $this->banking = $banking;
        $this->account = $account;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $from = new \DateTimeImmutable('first day of this month');
        $to = new \DateTimeImmutable();

        $transactions = $this->banking->getTransactions($this->account, $from, $to);

        $table = new Table($output);
        $table->setHeaders(['date', 'amount', 'name', 'description']);


        foreach ($transactions->toArray() as $transaction) {
            $table->addRow([
                $transaction['date'],
                new TableCell(
                    \number_format($transaction['amount'], 2, ',', '.') . ' €',
                    ['style' => new TableCellStyle(['align' => 'right', 'fg' => $transaction['amount'] >= 0 ? 'green' : 'red'])],
                ),
                $transaction['name'],
                \substr($transaction['description'], 0, 150),
            ]);
        }

        $table->render();

        return self::SUCCESS;
    }
}
