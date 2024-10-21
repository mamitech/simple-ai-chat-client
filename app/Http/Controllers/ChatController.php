<?php

namespace App\Http\Controllers;

use AzureOpenAI;
use Illuminate\Http\Request;
use OpenAI;

class ChatController extends Controller
{
    public const USER_ROLE = 'user';
    public const AI_ROLE = 'assistant';

    public const MESSAGES_KEY = 'messages';

    public function index()
    {
        // $model = config('openai.model');
        $model = config('openai.azure_deployment');
        return view('chat', compact('model'));
    }

    public function getMessages()
    {
        $messages = $this->getMessagesFromStorage();
        $messages = $this->transformMessages($messages);

        return response()->json(compact('messages'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);
        $message = htmlspecialchars($request->input('message'));

        $messages = $this->getMessagesFromStorage();
        $messages[] = ['content' => $message, 'role' => self::USER_ROLE];

        try {
            $aiResponse = $this->getAiResponse($messages);
        } catch (\Exception $e) {
            $aiResponse = 'Sorry, I am not feeling well right now. Please try again later. ' . $e->getMessage();
        }

        $messages[] = ['content' => $aiResponse, 'role' => self::AI_ROLE];

        session([self::MESSAGES_KEY => $messages]);

        return response()->json(['success' => true]);
    }

    public function clearMessages()
    {
        session()->forget(self::MESSAGES_KEY);

        return response()->json(['success' => true]);
    }

    private function getMessagesFromStorage(): array
    {
        return session(self::MESSAGES_KEY, []);
    }

    private function transformMessages(array $messages): string
    {
        $transformedMessage = '';
        foreach ($messages as $message) {
            $transformedMessage .= $this->formatMessage($message['content'], $message['role']);
        }
        return $transformedMessage;
    }

    private function formatMessage(string $content, string $role): string
    {
        $username = $role === self::AI_ROLE ? 'AI' : 'You';
        $formattedMessage =
            '<div class="message"><span class="username">' . $username . ':</span> ' . $content . '</div>' . PHP_EOL;
        return $formattedMessage;
    }

    private function getAiResponse(array $messages)
    {
        // $result = OpenAI::chat()->create([
        //     'model' => config()->get('openai.model'),
        //     'messages' => $messages,
        // ]);
        $result = AzureOpenAI::chat()->create([
            'model' => config()->get('openai.azure_deployment'),
            'messages' => $messages,
        ]);

        return trim($result->choices[0]->message->content);
    }
}
