<?php
/**
 * Example 05: Model Benchmark
 *
 * Runs the same prompt against every .gguf model found in the models/
 * directory and compares output quality and inference speed side-by-side.
 *
 * Download models first:
 *   task models:download
 *   task models:download:extra
 *
 * Run: ./frankenphp-custom php-cli examples/05_benchmark.php
 */

$modelsDir = __DIR__ . '/../models';
$models    = glob("$modelsDir/*.gguf") ?: [];

if (empty($models)) {
    echo "No models found in models/. Download some first:\n";
    echo "  task models:download\n";
    exit(1);
}

// ── Benchmark prompt ─────────────────────────────────────────────────────────

$prompt      = "Explain what a PHP extension is in one sentence.";
$maxTokens   = 40;
$systemPrompt = "You are a concise technical assistant.";

// ── Run benchmarks ───────────────────────────────────────────────────────────

echo "=== Model Benchmark ===\n";
echo "Prompt: $prompt\n";
printf("Models found: %d\n\n", count($models));

$results = [];

foreach ($models as $modelPath) {
    $name = basename($modelPath);
    echo "Running: $name ... ";
    flush();

    $json   = frankenphp_llm_generate_with_stats(
        $modelPath, $prompt, $maxTokens, 'greedy', 1.0,
        50, 0.9, 1.15, 64, $systemPrompt
    );
    $data   = json_decode($json, true);

    $results[] = [
        'name'       => $name,
        'text'       => trim($data['text']),
        'tokens'     => $data['generated_tokens'],
        'prefill_ms' => $data['prefill_ms'],
        'decode_ms'  => $data['decode_ms'],
        'tps'        => $data['tokens_per_second'],
    ];

    printf("done (%.1f t/s)\n", $data['tokens_per_second']);
}

// ── Results table ────────────────────────────────────────────────────────────

echo "\n";
$lineWidth = 100;
echo str_repeat('─', $lineWidth) . "\n";
printf("%-45s %6s %9s %9s %8s\n", 'Model', 'Tokens', 'Prefill', 'Decode', 'T/s');
echo str_repeat('─', $lineWidth) . "\n";

// Sort by tokens/sec descending
usort($results, fn($a, $b) => $b['tps'] <=> $a['tps']);

foreach ($results as $r) {
    printf(
        "%-45s %6d %8.0fms %8.0fms %7.1f\n",
        $r['name'],
        $r['tokens'],
        $r['prefill_ms'],
        $r['decode_ms'],
        $r['tps']
    );
}

echo str_repeat('─', $lineWidth) . "\n\n";

// ── Sample outputs ───────────────────────────────────────────────────────────

echo "=== Sample Outputs ===\n\n";
foreach ($results as $r) {
    echo basename($r['name']) . ":\n";
    echo "  " . wordwrap($r['text'], 80, "\n  ") . "\n\n";
}
