<?php
echo "=== Test 1: Simple Generation ===\n";
$modelPath = __DIR__ . '/models/SmolLM2-360M-Instruct-Q8_0.gguf';
$prompt = "2 + 2 =";
$result = frankenphp_llm_generate($modelPath, $prompt, 10);
echo "Prompt: $prompt\n";
echo "Result: $result\n\n";

echo "=== Test 2: Session-based Context ===\n";
$sessionID = "session-123";
$prompt1 = "My name is Martin.";
$result1 = frankenphp_llm_generate($modelPath, $prompt1, 15, "greedy", 1.0, 50, 0.9, 1.15, 64, "", "", $sessionID);
echo "Prompt 1: $prompt1\n";
echo "Result 1: $result1\n\n";

$prompt2 = "What is my name?";
$result2 = frankenphp_llm_generate($modelPath, $prompt2, 15, "greedy", 1.0, 50, 0.9, 1.15, 64, "", "", $sessionID);
echo "Prompt 2: $prompt2\n";
echo "Result 2: $result2\n\n";

echo "=== Test 3: Clear Session ===\n";
frankenphp_llm_clear_session($modelPath, $sessionID);
$result3 = frankenphp_llm_generate($modelPath, $prompt2, 15, "greedy", 1.0, 50, 0.9, 1.15, 64, "", "", $sessionID);
echo "Prompt after clearing session: $prompt2\n";
echo "Result after clearing session: $result3\n\n";

echo "=== Test 4: Generation with Statistics ===\n";
$prompt4 = "Explain in one sentence what gravity is.";
$jsonResponse = frankenphp_llm_generate_with_stats($modelPath, $prompt4, 30);
echo "Raw JSON response:\n$jsonResponse\n\n";

$data = json_decode($jsonResponse, true);
if (json_last_error() === JSON_ERROR_NONE) {
    echo "Parsed Stats:\n";
    echo "- Text: " . trim($data['text']) . "\n";
    echo "- Prompt Tokens: " . $data['prompt_tokens'] . "\n";
    echo "- Generated Tokens: " . $data['generated_tokens'] . "\n";
    echo "- Prefill Duration: " . round($data['prefill_ms'], 2) . " ms\n";
    echo "- Decode Duration: " . round($data['decode_ms'], 2) . " ms\n";
    echo "- Speed: " . round($data['tokens_per_second'], 2) . " tokens/second\n\n";
} else {
    echo "Error parsing JSON stats response!\n\n";
}

echo "=== All Tests Completed! ===\n";

echo "== Peak Memory Usage: " . memory_get_peak_usage(true) . " bytes\n";