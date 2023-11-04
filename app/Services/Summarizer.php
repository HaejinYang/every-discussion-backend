<?php

namespace App\Services;

class Summarizer
{
    public function summarize(string $plainText)
    {
        $apiKey = config('app.chatgpt_key') ?? '';

        $data = [
            "model" => "gpt-3.5-turbo",
            'messages' => [
                [
                    "role" => "system",
                    "content" => "Summarize content you are provided with for a second-grade student  with 20 words limit"
                ],
                [
                    "role" => "user",
                    "content" => "${plainText}"
                ]
            ],
            "max_tokens" => 200,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $headers = [];
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . $apiKey;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $response = json_decode($response, true);

        return $response['choices'][0]['message']['content'];
    }
}
