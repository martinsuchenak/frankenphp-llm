<?php
/**
 * Example 04: Structured Data Extraction
 *
 * Instructs the model to extract specific fields from unstructured text
 * and return them as JSON. Useful for parsing emails, support messages,
 * receipts, or any free-form content into structured records.
 *
 * Run: ./frankenphp-custom php-cli examples/04_extractor.php
 */

$model = __DIR__ . '/../models/SmolLM2-360M-Instruct-Q8_0.gguf';

if (!file_exists($model)) {
    echo "Model not found. Run: task models:download\n";
    exit(1);
}

// ── Extractor helper ─────────────────────────────────────────────────────────

function extractHelper(string $model, string $text, array $fields): array
{
    $fieldList    = implode(', ', $fields);
    $systemPrompt = "You are a data extraction assistant. "
        . "Extract the following fields from the text and return ONLY valid JSON with these keys: $fieldList. "
        . "Use null for any field that is not present in the text. "
        . "Output nothing except the JSON object.";

    $raw = frankenphp_llm_generate($model, $text, 120, 'greedy', 1.0, 1, 1.0, 1.0, 0, $systemPrompt);

    // Find the first JSON object in the response
    if (preg_match('/\{[^}]+\}/s', $raw, $m)) {
        $data = json_decode($m[0], true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $data;
        }
    }
    return [];
}

// ── Demo 1: Contact information extraction ───────────────────────────────────

echo "=== Contact Info Extraction ===\n\n";

$emails = [
    "Hi, I'm Sarah Johnson from Acme Corp. You can reach me at sarah.johnson@acmecorp.com or call +1-555-0142. Looking forward to our meeting on Thursday.",
    "Please send the invoice to accounts@startupxyz.io — our company is StartupXYZ Ltd, registered in Dublin. Contact: David O'Brien, CFO.",
    "This is an automated notification. No reply needed.",
];

foreach ($emails as $email) {
    echo "Input:\n  " . wordwrap($email, 70, "\n  ") . "\n";
    $data = extractHelper($model, $email, ['name', 'company', 'email', 'phone']);
    echo "Extracted:\n";
    foreach ($data as $key => $value) {
        printf("  %-8s %s\n", "$key:", $value ?? '(not found)');
    }
    echo "\n";
}

// ── Demo 2: Event details extraction ─────────────────────────────────────────

echo "=== Event Details Extraction ===\n\n";

$announcements = [
    "Join us for the annual PHP Conference Europe on June 14-16, 2025 in Berlin, Germany. Early bird tickets are €299. Register at phpce.eu.",
    "Team standup moved to 10:30 AM tomorrow (Wednesday) in Meeting Room B. Agenda: Q3 planning and sprint review.",
];

foreach ($announcements as $text) {
    echo "Input:\n  " . wordwrap($text, 70, "\n  ") . "\n";
    $data = extractHelper($model, $text, ['event', 'date', 'location', 'price']);
    echo "Extracted:\n";
    foreach ($data as $key => $value) {
        printf("  %-9s %s\n", "$key:", $value ?? '(not found)');
    }
    echo "\n";
}
