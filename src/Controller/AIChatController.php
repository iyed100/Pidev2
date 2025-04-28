<?php
// src/Controller/AIChatController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;

class AIChatController extends AbstractController
{
    private $httpClient;
    private $geminiApiKey;
    private $logger;

    public function __construct(
        HttpClientInterface $httpClient,
        string $geminiApiKey,
        LoggerInterface $logger
    ) {
        $this->httpClient = $httpClient;
        $this->geminiApiKey = $geminiApiKey;
        $this->logger = $logger;
    }

    #[Route('/ai-chat', name: 'ai_chat')]
    public function chat(Request $request): Response
    {
        $userMessage = $request->request->get('message', '');

        if (!empty($userMessage)) {
            $aiResponse = $this->callGeminiAPI($userMessage);
            return $this->render('ai_chat/index.html.twig', [
                'user_message' => $userMessage,
                'ai_response' => $aiResponse ?? null
            ]);
        }

        return $this->render('ai_chat/index.html.twig');
    }

    private function callGeminiAPI(string $message): string
    {
        try {
            $response = $this->httpClient->request('POST', 
                'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=GEMINI_API_KEY', 
                [
                    'query' => ['key' => $this->geminiApiKey],
                    'json' => [
                        'contents' => [
                            'parts' => [
                                ['text' => $message]
                            ]
                        ]
                    ],
                    'headers' => [
                        'Content-Type' => 'application/json'
                    ]
                ]
            );
    
            $data = $response->toArray();
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No response from AI';
            
        } catch (\Exception $e) {
            $this->logger->error('Gemini API Error: '.$e->getMessage());
            return 'Error communicating with AI service. Please try again.';
        }
    }
}