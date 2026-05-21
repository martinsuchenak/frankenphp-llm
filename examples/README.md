# Examples

Practical PHP examples showing how to use the `frankenphp_llm` extension for real-world tasks with small local LLMs.

All examples are run through the custom FrankenPHP binary:

```bash
./frankenphp-custom php-cli examples/<script>.php
```

Or with the Taskfile shortcut:

```bash
task example -- examples/01_hello_llm.php
```

---

## Prerequisites

Download at least one model before running the examples:

```bash
task models:download        # SmolLM2 135M / 360M / 1.7B + TinyLlama
task models:download:extra  # Llama 3.2 1B, Qwen2.5 0.5B, Qwen3 1.7B
```

---

## Examples

### [01_hello_llm.php](01_hello_llm.php)

**Basic generation with stats.**

The simplest starting point — generate a short text and display prefill/decode timing and tokens per second. Good for verifying the extension is installed correctly.

```
Model: SmolLM2-360M-Instruct-Q8_0.gguf

Prompt: Write a haiku about running AI locally on your own machine.

Response:
  Circuits hum softly,
  Data flows through local paths,
  Mind meets silicon.

--- Performance ---
Prompt tokens:    20
Generated tokens: 17
Prefill:          412 ms
Decode:           542 ms
Speed:            31.4 tokens/sec
```

---

### [02_multi_turn_chat.php](02_multi_turn_chat.php)

**Multi-turn conversation with KV-cache sessions.**

Each message in the same session reuses the cached attention state from prior turns, so only new tokens are processed. This example prints per-turn timing so you can see the speedup on subsequent turns.

```
[Turn 1] 42 tokens in 1420 ms (29.6 t/s)   ← full context prefill
[Turn 2] 38 tokens in  890 ms (42.7 t/s)   ← only new tokens processed
[Turn 3] 12 tokens in  310 ms (38.7 t/s)   ← very fast, short answer
```

---

### [03_classifier.php](03_classifier.php)

**Zero-shot text classifier via system prompts.**

Constrains the model to output a single label with no explanation — turning a generative LLM into a fast, dependency-free classifier. Demonstrates:

- **Sentiment analysis** on product reviews (POSITIVE / NEGATIVE / NEUTRAL)
- **Support ticket routing** to departments (BILLING / TECHNICAL / ACCOUNT / GENERAL)

No training data or fine-tuning required.

---

### [04_extractor.php](04_extractor.php)

**Structured data extraction from free-form text.**

Instructs the model to output valid JSON, then parses and displays the extracted fields. Useful for:

- Parsing contact information from emails
- Extracting event details from announcements
- Processing receipts, invoices, or any unstructured input into structured records

---

### [05_benchmark.php](05_benchmark.php)

**Side-by-side model performance comparison.**

Runs the same prompt against every `.gguf` file in the `models/` directory and prints a ranked table of prefill time, decode time, and tokens per second. Useful for choosing the best model for your latency/quality trade-off.

```
─────────────────────────────────────────────────────────────────────
Model                                         Tokens  Prefill   Decode      T/s
─────────────────────────────────────────────────────────────────────
SmolLM2-135M-Instruct-Q8_0.gguf                  38    210ms    790ms     48.1
SmolLM2-360M-Instruct-Q8_0.gguf                  40    410ms   1280ms     31.2
SmolLM2-1.7B-Instruct-Q8_0.gguf                  39   1840ms   5910ms      6.6
─────────────────────────────────────────────────────────────────────
```

---

## Tips

- **Session IDs** should be unique per user/conversation — use `uniqid()` or a UUID.
- **Call `frankenphp_llm_clear_session()`** when a conversation ends to free the KV-cache memory.
- **Greedy** strategy is deterministic and fastest. Use `top_p` with `temperature < 1.0` for more varied creative output.
- Small models (135M–360M) excel at classification and short-form extraction. For longer reasoning or complex instructions, try 1.7B+ models.
