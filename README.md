# OpenAI PHP client

A Fluent PHP client fo4 the OpenAI chat completions API.

Very much version 0.1 right now.

# Usage

Quick example of how to use it...

```php
declare(strict_types=1);
include 'vendor/autoload.php';

$openAIClient = new \DBarnwell\OpenAI(
    'OPEN_API_KEY',
    'OPEN_API_ORGANISATION',
    'OPEN_API_PROJECT',
);

// You can set the system prompt and multiple user prompts
// ->using($model) clears out previous messages
$response = $openAIClient->using('gpt-4o-mini')
                         ->withTemperature(1)
                         ->withSystemPrompt('Acting as a comedian')
                         ->withUserPrompt('Tell me a knock knock joke')
                         ->withUserPrompt('Actually tell me two such jokes')
                         ->execute();

var_dump($response);
```