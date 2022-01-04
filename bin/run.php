<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Oqq\Office\Application\DeleteWorklogCommand;
use Oqq\Office\Application\LabelIssueKeyResolver;
use Oqq\Office\Application\WorklogResolver;
use Oqq\Office\Jira\GuzzleJiraApi;
use Oqq\Office\Application\ListTimeEntriesCommand;
use Oqq\Office\Jira\JiraUser;
use Oqq\Office\Timeular\GuzzleTimeularApi;
use Oqq\Office\Util\Json;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';

$timeularApiKey = getenv('TIMEULAR_API_KEY');
$timeularApiSecret = getenv('TIMEULAR_API_SECRET');
$jiraBaseUrl = getenv('JIRA_BASE_URL');
$jiraUsername = getenv('JIRA_USERNAME');
$jiraPassword = getenv('JIRA_PASSWORD');

$getJsonResponse = static fn (ResponseInterface $response): array => Json::decode((string) $response->getBody());


$timeularClient = new Client([
    'base_uri' => 'https://api.timeular.com/'
]);

$response = $timeularClient->request('POST', '/api/v3/developer/sign-in', [
    RequestOptions::JSON => [
        'apiKey' => $timeularApiKey,
        'apiSecret' => $timeularApiSecret,
    ],
]);

$token = $getJsonResponse($response)['token'];

$timeularClient = new Client([
    'base_uri' => 'https://api.timeular.com/',
    RequestOptions::HEADERS => [
        'Authorization' => "Bearer $token"
    ],
]);

$timeularApi = new GuzzleTimeularApi($timeularClient);


$jiraClient = new Client([
    'base_uri' => $jiraBaseUrl,
    RequestOptions::VERIFY => false,
    RequestOptions::AUTH => [$jiraUsername, $jiraPassword],
]);

$jiraApi = new GuzzleJiraApi($jiraClient);
$jiraUser = JiraUser::fromString($jiraUsername);

$worklogResolver = new WorklogResolver(
    $timeularApi,
    new LabelIssueKeyResolver(),
    $jiraApi,
    $jiraUser,
    $jiraBaseUrl
);

$commands= [];
$commands[] = new ListTimeEntriesCommand($worklogResolver);
$commands[] = new DeleteWorklogCommand($jiraApi, $jiraUser);

$application = new Application();
$application->addCommands($commands);
$application->run();
