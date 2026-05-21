<?php
/**
 * Example 03: Text Classifier
 *
 * Uses a tight system prompt to constrain the model to output a single
 * label — turning a generative LLM into a zero-shot classifier.
 *
 * Use cases: sentiment analysis, intent detection, content moderation,
 * support ticket routing.
 *
 * Run: ./frankenphp-custom php-cli examples/03_classifier.php
 */

$model = __DIR__ . '/../models/SmolLM2-360M-Instruct-Q8_0.gguf';

if (!file_exists($model)) {
    echo "Model not found. Run: task models:download\n";
    exit(1);
}

// ── Classifier helper ────────────────────────────────────────────────────────

function classify(string $model, string $text, array $labels, string $task): string
{
    $labelList    = implode(', ', $labels);
    $systemPrompt = "You are a classifier. $task "
        . "Reply with ONLY one of these labels — no explanation, no punctuation: $labelList";

    $raw = frankenphp_llm_generate($model, $text, 8, 'greedy', 1.0, 1, 1.0, 1.0, 0, $systemPrompt);

    // Find whichever label appears first in the response
    $raw = strtoupper(trim($raw));
    foreach ($labels as $label) {
        if (str_contains($raw, strtoupper($label))) {
            return $label;
        }
    }
    return 'UNKNOWN';
}

// ── Demo 1: Sentiment analysis ───────────────────────────────────────────────

echo "=== Sentiment Analysis ===\n\n";

$sentimentSamples = [
    "This product completely changed my life — I can't imagine working without it.",
    "Arrived three weeks late and was broken out of the box. Absolutely terrible.",
    "It works as described. Nothing special but does the job.",
    "Worst purchase I've ever made. Returning immediately.",
    "Exceeded all my expectations. Five stars without hesitation!",
];

foreach ($sentimentSamples as $text) {
    $label = classify($model, $text, ['POSITIVE', 'NEGATIVE', 'NEUTRAL'], 'Classify the sentiment of this review.');
    $icon  = match ($label) {
        'POSITIVE' => '✅',
        'NEGATIVE' => '❌',
        default    => '➖',
    };
    printf("%s [%s] %s\n", $icon, $label, $text);
}

// ── Demo 2: Support ticket routing ───────────────────────────────────────────

echo "\n=== Support Ticket Routing ===\n\n";

$tickets = [
    "I can't log in — it keeps saying my password is wrong even after reset.",
    "I was charged twice for my subscription this month.",
    "How do I export my data as a CSV file?",
    "The mobile app crashes every time I try to upload a photo.",
    "I'd like to cancel my account and get a refund.",
];

$departments = ['BILLING', 'TECHNICAL', 'ACCOUNT', 'GENERAL'];

foreach ($tickets as $ticket) {
    $dept = classify($model, $ticket, $departments, 'Route this customer support message to the correct department.');
    printf("[%-9s] %s\n", $dept, $ticket);
}
