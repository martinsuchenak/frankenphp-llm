<?php
/**
 * Example 02: Multi-Turn Chat
 *
 * Demonstrates KV-cache sessions: each follow-up message in the same
 * conversation only processes new tokens — prior context is reused from
 * the cache, making responses progressively faster.
 *
 * Run: ./frankenphp-custom php-cli examples/02_multi_turn_chat.php
 */

$model   = __DIR__ . '/../models/SmolLM2-360M-Instruct-Q8_0.gguf';
$session = 'chat-' . uniqid();

if (!file_exists($model)) {
    echo "Model not found. Run: task models:download\n";
    exit(1);
}

$systemPrompt = "You are a friendly and concise assistant. Keep answers under three sentences.";

// Helper: send a message and print timings
function chat(string $model, string $message, string $session, string $systemPrompt = ''): string
{
    global $turnNumber;
    $turnNumber = ($turnNumber ?? 0) + 1;

    $json   = frankenphp_llm_generate_with_stats(
        $model, $message, 80, 'greedy', 1.0, 50, 0.9, 1.15, 64,
        $systemPrompt, '', $session
    );
    $result = json_decode($json, true);
    $text   = trim($result['text']);

    printf(
        "[Turn %d] %d tokens in %.0f ms (%.1f t/s)\n",
        $turnNumber,
        $result['generated_tokens'],
        $result['decode_ms'],
        $result['tokens_per_second']
    );

    return $text;
}

echo "=== Multi-Turn Chat ===\n";
echo "Session: $session\n";
echo "Note: Turn 1 loads and prefills the full context.\n";
echo "      Later turns only process new tokens — watch the timings.\n\n";

$turns = [
    "Hi! My name is Alex and I'm learning about large language models.",
    "What's the key difference between GPT-style and BERT-style models?",
    "Can you remind me of my name from earlier in our conversation?",
];

foreach ($turns as $i => $message) {
    echo "You: $message\n";
    $reply = chat($model, $message, $session, $i === 0 ? $systemPrompt : '');
    echo "Bot: $reply\n\n";
}

// Clean up the session to free memory
frankenphp_llm_clear_session($model, $session);
echo "[Session cleared]\n";
