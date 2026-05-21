<?php
/**
 * Example 01: Hello LLM
 *
 * The simplest possible usage — generate text from a prompt and display
 * performance statistics to verify the extension is working.
 *
 * Run: ./frankenphp-custom php-cli examples/01_hello_llm.php
 */

$model = __DIR__ . '/../models/SmolLM2-360M-Instruct-Q8_0.gguf';

if (!file_exists($model)) {
    echo "Model not found. Download one first:\n";
    echo "  task models:download\n";
    exit(1);
}

echo "=== Hello LLM ===\n\n";
echo "Model: " . basename($model) . "\n\n";

$prompt = "Write a haiku about running AI locally on your own machine.";
echo "Prompt: $prompt\n\n";

$json   = frankenphp_llm_generate_with_stats($model, $prompt, 60, "greedy", 1.0, 50, 0.9, 1.15, 64);
$result = json_decode($json, true);

echo "Response:\n";
echo trim($result['text']) . "\n\n";

echo "--- Performance ---\n";
printf("Prompt tokens:    %d\n",   $result['prompt_tokens']);
printf("Generated tokens: %d\n",   $result['generated_tokens']);
printf("Prefill:          %.0f ms\n", $result['prefill_ms']);
printf("Decode:           %.0f ms\n", $result['decode_ms']);
printf("Speed:            %.1f tokens/sec\n", $result['tokens_per_second']);
