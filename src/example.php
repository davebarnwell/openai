<?php
declare(strict_types=1);

use D4B7\OpenAI\ApiClient;

include '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createMutable('..'.DIRECTORY_SEPARATOR);
try {
    $dotenv->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    echo "Error loading the .env file: ".$e->getMessage();
    exit(1);
}

$openAIClient = new ApiClient(
    $_ENV['OPENAI_API_KEY'],
    $_ENV['OPENAI_API_ORGANISATION'],
    $_ENV['OPENAI_API_PROJECT']
);

// You can set the system prompt and multiple user prompts
// ->using($model) clears out previous messages
$response = $openAIClient->using($_ENV['OPENAI_API_MODEL'])
                         ->withTemperature((float) $_ENV['OPENAI_API_TEMPERATURE'])
//                         ->withSystemPrompt('Acting as a comedian') // pre o1 models
                         ->withDeveloperPrompt('Acting as a comedian') // o1 models on, system role prompts were replaced with developer role prompts
                         ->withUserPrompt('Tell me a knock knock joke')
                         ->withUserPrompt('Actually tell me two such jokes')
                         ->withAssistantPrompt('Are you ready? Here they come ready or not')
                         ->execute();

// var_dump($response);
echo $response['choices'][0]['message']['content'].PHP_EOL;
