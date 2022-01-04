<?php

declare(strict_types=1);

namespace Oqq\Office\Application;

use DateTimeImmutable;
use Oqq\Office\Jira\Tempo\IssueKey;
use Oqq\Office\Jira\Tempo\TimeSpentSeconds;
use Oqq\Office\Timeular\Activity;
use Oqq\Office\Util\Assert;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

final class ListTimeEntriesCommand extends Command
{
    private const ARGUMENT_MONTH = 'month';
    private const OPTION_SPACE = 'space';
    private const OPTION_SEND_WORKLOGS = 'send_worklogs';

    protected static $defaultName = 'worklogs:list';

    private WorklogResolver $worklogResolver;

    public function __construct(WorklogResolver $worklogResolver)
    {
        $this->worklogResolver = $worklogResolver;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('List worklogs');

        $this->addArgument(
            self::ARGUMENT_MONTH,
            InputArgument::OPTIONAL,
            'Month to display worklogs for. Defaults to last month.',
            $this->getLastMonth()
        );

        $this->addOption(
            self::OPTION_SPACE,
            InputArgument::OPTIONAL,
            InputOption::VALUE_OPTIONAL,
            'Space to display worklogs for. Defaults to "all spaces".'
        );

        $this->addOption(
            self::OPTION_SEND_WORKLOGS,
            '-s',
            InputOption::VALUE_NONE,
            'Should worklogs be send to jira?',
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $month = $this->getMonth($input);
        $space = $this->getSpace($input);
        $sendWorklogs = $this->getSendWorklogOption($input, $output);

        $tableHeaders = ['Date', 'Time Spent', 'Issue', 'Comment'];

        $table = new Table($output);
        $table->setHeaders($tableHeaders);

        $timeSpentMonth = 0;

        foreach ($this->worklogResolver->getWorklogsForMonthByWeek($month) as $week => $worklogs) {
            if (null !== $space) {
                $worklogs = $worklogs->filterBySpace($space);
            }

            $timeSpentMonth += $worklogs->timeSpent()->value();
            $this->addWeekSeparator($table, $week, $worklogs->timeSpent());
            $this->addWorklogRows($table, $worklogs, $sendWorklogs);
        }

        $table->addRow([$month->value(), $this->timeSpentFromSeconds(TimeSpentSeconds::fromInteger($timeSpentMonth))]);
        $table->render();

        return 0;
    }

    private function getMonth(InputInterface $input): Month
    {
        $month = $input->getArgument(self::ARGUMENT_MONTH);
        Assert::string($month);

        return Month::fromString($month);
    }

    private function getSpace(InputInterface $input): ?string
    {
        $space = $input->getOption(self::OPTION_SPACE);

        if (null === $space) {
            return null;
        }

        Assert::string($space);

        return $space;
    }

    private function getLastMonth(): string
    {
        $lastMonth = new DateTimeImmutable('first day of last month');

        return $lastMonth->format('Y-m');
    }

    private function addWeekSeparator(Table $table, Week $week, TimeSpentSeconds $timeSpentInWeek): void
    {
        $table->addRow(new TableSeparator());
        $table->addRow([$week->value(), $this->timeSpentFromSeconds($timeSpentInWeek)]);
        $table->addRow(new TableSeparator());
    }

    private function addWorklogRows(Table $table, MaybeWorklogs $worklogs, bool $sendWorklogs): void
    {
        /** @var MaybeWorklog $worklog */
        foreach ($worklogs as $worklog) {
            $table->addRow([
                $worklog->date()->format('Y-m-d'),
                $this->timeSpentFromSeconds($worklog->timeSpentSeconds()),
                $this->getIssueKeyTableField(
                    $worklog->activity(),
                    $worklog->optionalIssueKey(),
                ),
                $worklog->comment()->toString(),
            ]);

            if ($sendWorklogs && $worklog->hasIssueKey()) {
                $this->worklogResolver->createWorklog($worklog);
            }
        }
    }

    private function timeSpentFromSeconds(TimeSpentSeconds $timeSpentSeconds): string
    {
        $timeSpentMinutes = $timeSpentSeconds->value() / 60;

        return \sprintf(
            '%dh %02dm',
            floor($timeSpentMinutes / 60),
            $timeSpentMinutes % 60
        );
    }

    private function getIssueKeyTableField(Activity $activity, ?IssueKey $issueKey): string
    {
        if (null === $issueKey) {
            return '<error>???-0000</error>';
        }

        return \sprintf(
            '<href=%s;fg=%s>%s</>',
            $this->worklogResolver->createUrlForIssue($issueKey),
            $activity->color(),
            $issueKey->toString(),
        );
    }

    private function getSendWorklogOption(InputInterface $input, OutputInterface $output): bool
    {
        $sendWorklogs = $input->getOption(self::OPTION_SEND_WORKLOGS);

        if (true !== $sendWorklogs) {
            return false;
        }

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Do you really want to send the worklogs? (y|N)', false);

        return true === $helper->ask($input, $output, $question);
    }
}
