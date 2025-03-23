<?php
declare(strict_types=1);

namespace D4B7\OpenAI;

use RuntimeException;

class ApiClient
{
    private array $messages = [];

    private float $temperature = 1;

    private string $model = 'gpt-4o-mini';

    private string $apiUrl = 'https://api.openai.com/v1/chat/completions';

    public function __construct(public string $key, public string $organisation, public string $project)
    {
    }

    private function resetMessages(): void
    {
        $this->messages = [];
    }

    public function using(string $model): self
    {
        $this->resetMessages();
        $this->model = $model;

        return $this;
    }

    public function withTemperature(float $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    private function withMessage(string $role, string $message): self
    {
        $this->messages[] = ['role' => $role, 'content' => $message];

        return $this;
    }

    public function withUserPrompt(string $message): self
    {
        return $this->withMessage('user', $message);
    }

    public function withSystemPrompt(string $message): self
    {
        return $this->withMessage('system', $message);
    }


    /**
     * @return array{
     *     id: string,
     *     object: string,
     *     model: string,
     *     choices: array{
     *         array{
     *             index: int,
     *             message: array {
     *                  role: string,
     *                  content: string,
     *                  refusal: string|null,
     *                  annotations: array,
     *              },
     *             logprobs: null,
     *             finish_reason: string
     *         }
     *     },
     *     usage: array{
     *         prompt_tokens: int,
     *         completion_tokens: int,
     *         total_tokens: int,
     *         prompt_tokens_details: array{
     *             cached_tokens: int,
     *             audio_tokens: int,
     *         },
     *         comletion_tokens_details: array{
     *              reasoning_tokens: int,
     *              audio_tokens: int,
     *              accepted_predication_tokens: int,
     *              rejected_predication_tokens: int,
     *         }
     *     },
     *     service_tier: string,
     *     system_fingerprint: string
     * }
     */
    public function execute(): array
    {
        $data = [
            'model'       => $this->model,
            'temperature' => $this->temperature,
            'messages'    => $this->messages,
        ];
        $ch   = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL            => $this->apiUrl,
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => [
                "ApiClient-Project: {$this->project}",
                "ApiClient-Organization: {$this->organisation}",
                "Authorization: Bearer {$this->key}",
                "Content-Type: application/json", // Ensure proper content type
            ],
            CURLOPT_POSTFIELDS     => json_encode($data), // Send payload as JSON
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new RuntimeException('cURL Error: '.curl_error($ch));
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Convert response to array or throw exception based on HTTP response
        if ($httpCode >= 400) {
            throw new RuntimeException("HTTP request failed with code {$httpCode}: {$response}");
        }

        // Parse JSON response and return it
        return json_decode($response, true);

    }

}
