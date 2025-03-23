# OpenAI PHP client

A Fluent PHP client for the OpenAI chat completions API.

Very much version 0.1 right now. Intent to to expand overtime, help appreciated,
inspired by various Javascript/Typescipt OpenAPI clients.

# Usage

Quick example of how to use it...

```php
declare(strict_types=1);

use D4B7\OpenAI\ApiClient;

include '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createMutable('..'.DIRECTORY_SEPARATOR);
try {
    $dotenv->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    echo "Error loading the .env file: " . $e->getMessage();
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
                         ->withSystemPrompt('Acting as a comedian')
                         ->withUserPrompt('Tell me a knock knock joke')
                         ->withUserPrompt('Actually tell me two such jokes')
                         ->execute();

// var_dump($response);
echo $response['choices'][0]['message']['content'].PHP_EOL;
```