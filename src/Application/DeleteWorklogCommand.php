<?php

declare(strict_types=1);

namespace Oqq\Office\Application;

use DateTimeImmutable;
use Oqq\Office\Jira\JiraApi;
use Oqq\Office\Jira\JiraUser;
use Oqq\Office\Util\Assertion;
use Oqq\Office\Util\DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

final class DeleteWorklogCommand extends Command
{
    private const ARGUMENT_MONTH = 'month';
    private const OPTION_DELETE_WORKLOGS = 'delete_worklogs';

    protected static $defaultName = 'worklogs:delete';

    private JiraApi $jiraApi;
    private JiraUser $jiraUser;

    public function __construct(JiraApi $jiraApi, JiraUser $jiraUser)
    {
        $this->jiraApi = $jiraApi;
        $this->jiraUser = $jiraUser;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Delete worklogs');

        $this->addArgument(
            self::ARGUMENT_MONTH,
            InputArgument::OPTIONAL,
            'Month to delete worklogs for. Defaults to last month.',
            $this->getLastMonth()
        );

        $this->addOption(
            self::OPTION_DELETE_WORKLOGS,
            '-d',
            InputOption::VALUE_NONE,
            'Should worklogs be deleted from jira?',
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $month = $this->getMonth($input);
        $deleteWorklogs = $this->getDeleteWorklogOption($input, $output);

        $start = DateTime::fromString($month->value() . '-01', 'Y-m-d|');
        $end = DateTime::fromString($start->format('Y-m-t') . 'T23:59:59.999999', 'Y-m-d\TH:i:s.u');

        $worklogs = $this->jiraApi->getWorklogs($this->jiraUser, $start, $end);

        foreach ($worklogs as $worklog) {
            echo \sprintf('Delete worklog for issue %s from %s',
                $worklog->issue()->issueKey()->toString(),
                $worklog->started()->format('Y-m-d'),
            ), \PHP_EOL;

            if ($deleteWorklogs) {
                $this->jiraApi->deleteWorkLog($worklog->worklogId());
            }
        }

        return 0;
    }

    private function getMonth(InputInterface $input): Month
    {
        $month = $input->getArgument(self::ARGUMENT_MONTH);
        Assertion::string($month);

        return Month::fromString($month);
    }

    private function getLastMonth(): string
    {
        $lastMonth = new DateTimeImmutable('first day of last month');

        return $lastMonth->format('Y-m');
    }

    private function getDeleteWorklogOption(InputInterface $input, OutputInterface $output): bool
    {
        $deleteWorklogs = $input->getOption(self::OPTION_DELETE_WORKLOGS);

        if (true !== $deleteWorklogs) {
            return false;
        }

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Do you really want to delete the worklogs? (y|N)', false);

        return true === $helper->ask($input, $output, $question);
    }
}
