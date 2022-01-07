<?php

declare(strict_types=1);

namespace Oqq\Office\Service;

use Fhp\Action\GetStatementOfAccount;
use Fhp\BaseAction;
use Fhp\FinTs;
use Fhp\Model\SEPAAccount;
use Fhp\Model\StatementOfAccount\Transaction;
use Oqq\Office\Service\Banking\Transactions;

final class Banking
{
    public function __construct(
        private readonly FinTs $finTs
    ) {
    }

    public function getTransactions(SEPAAccount $account, \DateTimeImmutable $from, \DateTimeImmutable $to): Transactions
    {
        $login = $this->finTs->login();

        if ($login->needsTan()) {
            $this->handleStrongAuthentication($login);
        }

        $getStatement = GetStatementOfAccount::create(
            $account,
            \DateTime::createFromImmutable($from),
            \DateTime::createFromImmutable($to)
        );

        $this->finTs->execute($getStatement);

        if ($getStatement->needsTan()) {
            $this->handleStrongAuthentication($getStatement);
        }

        $transactions = [];

        $soa = $getStatement->getStatement();
        foreach ($soa->getStatements() as $statement) {
            foreach ($statement->getTransactions() as $transaction) {
                $transactions[] = [
                    'date' => $statement->getDate()->format('Y-m-d'),
                    'name' => $transaction->getName(),
                    'description' => $transaction->getMainDescription(),
                    'amount' => match ($transaction->getCreditDebit()) {
                        default => \abs($transaction->getAmount()),
                        Transaction::CD_DEBIT => 0.00 - \abs($transaction->getAmount()),
                    },
                ];
            }
        }

        return Transactions::fromPayload($transactions);
    }

    private function handleStrongAuthentication(BaseAction $action): void
    {
        $tanMode = $this->finTs->getSelectedTanMode();
        $tanRequest = $action->getTanRequest();

        if (!$tanMode || !$tanRequest) {
            echo 'invalid authentication state';
            return;
        }

        echo 'The bank requested authentication on another device.';
        if ($tanRequest->getChallenge() !== null) {
            echo ' Instructions: ' . $tanRequest->getChallenge();
        }

        echo "\n";
        if ($tanRequest->getTanMediumName() !== null) {
            echo 'Please check this device: ' . $tanRequest->getTanMediumName() . "\n";
        }
    
        // IMPORTANT: In your real application, you don't have to use sleep() in PHP. You can persist the state in the same
        // way as in handleTan() and restore it later. This allows you to use some other timer mechanism (e.g. in the user's
        // browser). This PHP sample code just serves to show the *logic* of the polling. Alternatively, you can even do
        // without polling entirely and just let the user confirm manually in all cases (i.e. only implement the `else`
        // branch below).
        if ($tanMode->allowsAutomatedPolling()) {
            echo "Polling server to detect when the decoupled authentication is complete.\n";
            sleep($tanMode->getFirstDecoupledCheckDelaySeconds());
            for ($attempt = 0;
                 $tanMode->getMaxDecoupledChecks() === 0 || $attempt < $tanMode->getMaxDecoupledChecks();
                 ++$attempt
            ) {
                if ($this->finTs->checkDecoupledSubmission($action)) {
                    echo "Confirmed.\n";
                    return;
                }
                echo "Still waiting...\n";
                sleep($tanMode->getPeriodicDecoupledCheckDelaySeconds());
            }
            throw new \RuntimeException("Not confirmed after $attempt attempts, which is the limit.");
        } elseif ($tanMode->allowsManualConfirmation()) {
            do {
                echo "Please type 'done' and hit Return when you've completed the authentication on the other device.\n";
                while (trim(fgets(STDIN)) !== 'done') {
                    echo "Try again.\n";
                }
                echo "Confirming that the action is done.\n";
            } while (!$this->finTs->checkDecoupledSubmission($action));
            echo "Confirmed\n";
        } else {
            throw new \AssertionError('Server allows neither automated polling nor manual confirmation');
        }
    }
}
