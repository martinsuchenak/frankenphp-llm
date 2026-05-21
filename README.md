# frankenphp-llm

Auto-generated PHP extension from Go code to run local LLM inference in FrankenPHP using the `scriptling-llm-lib` engine.

## 🛠️ Building & Setup

To compile the custom FrankenPHP binary with the `frankenphp_llm` extension preloaded, follow the instructions below.

### 1. Prerequisites (macOS ARM64 example)

- **Go**: Version 1.22 or higher.
- **PHP-ZTS (Zend Thread Safe)**: Standard PHP installations do not ship with thread-safety enabled or the `php_embed.h` headers needed to compile FrankenPHP. Install the thread-safe version using Homebrew:
  ```bash
  brew tap shivammathur/php
  brew install php-zts
  ```
- **Task** (optional but recommended): Install the [Task](https://taskfile.dev) runner for the convenience commands below:
  ```bash
  brew install go-task
  ```

### 2. Compilation

The `Taskfile.yml` auto-detects the PHP-ZTS path from Homebrew:

```bash
task build
```

This is equivalent to running:

```bash
CGO_CFLAGS="-I$(brew --prefix php-zts)/include/php ..." \
CGO_LDFLAGS="-L$(brew --prefix php-zts)/lib -lphp" \
go build -tags nowatcher -o frankenphp-custom
```

Other tasks:

| Command | Description |
|---------|-------------|
| `task build` | Compile `frankenphp-custom` |
| `task test` | Build then run `test.php` with the custom binary |
| `task run` | Build then start FrankenPHP server |
| `task tidy` | Tidy `go.mod` |
| `task clean` | Remove the compiled binary |
| `task models:download` | Download SmolLM2 models from HuggingFace (~5 GB) |
| `task models:download:extra` | Download Llama 3.2, Qwen2.5, Qwen3 models |
| `task models:clean` | Remove all downloaded `.gguf` model files |

*Note: The `-tags nowatcher` flag avoids compilation errors with C++ filesystem watcher dependencies on some systems.*

## Functions

### frankenphp_llm_generate

```php
frankenphp_llm_generate(string $modelPath, string $prompt, int $maxTokens = 100, string $strategy = "greedy", float $temperature = 1.0, int $topK = 50, float $topP = 0.9, float $repeatPenalty = 1.15, int $repeatLastN = 64, string $systemPrompt = "", string $template = "", string $session = ""): string
```

**Parameters:**

- `modelPath` (string)
- `prompt` (string)
- `maxTokens` (int) (default: 100)
- `strategy` (string) (default: greedy)
- `temperature` (float) (default: 1.0)
- `topK` (int) (default: 50)
- `topP` (float) (default: 0.9)
- `repeatPenalty` (float) (default: 1.15)
- `repeatLastN` (int) (default: 64)
- `systemPrompt` (string) (default: )
- `template` (string) (default: )
- `session` (string) (default: )

**Returns:** string

### frankenphp_llm_generate_with_stats

Runs inference similar to `frankenphp_llm_generate`, but returns a JSON-serialized string containing the generated text along with detailed performance statistics.

```php
frankenphp_llm_generate_with_stats(string $modelPath, string $prompt, int $maxTokens = 100, string $strategy = "greedy", float $temperature = 1.0, int $topK = 50, float $topP = 0.9, float $repeatPenalty = 1.15, int $repeatLastN = 64, string $systemPrompt = "", string $template = "", string $session = ""): string
```

**Parameters:**
Same parameters as `frankenphp_llm_generate`.

**Returns:** A JSON string structured as follows:
```json
{
  "text": "The generated response text...",
  "prompt_tokens": 29,
  "generated_tokens": 30,
  "prefill_ms": 525.03,
  "decode_ms": 1039.42,
  "tokens_per_second": 28.86
}
```

### frankenphp_llm_clear_session

```php
frankenphp_llm_clear_session(string $modelPath, string $session): bool
```

**Parameters:**

- `modelPath` (string)
- `session` (string)

**Returns:** bool

## Testing & Verification

A test script `test.php` is provided to verify simple inference, session-based context retention, and session clearing.

### Prerequisites

Make sure you have a GGUF format LLM model in the `models/` directory. By default, the test script looks for `models/SmolLM2-360M-Instruct-Q8_0.gguf`.

```bash
mkdir -p models
# Place your GGUF model here (e.g. SmolLM2-360M-Instruct-Q8_0.gguf)
```

### Running the Test

Run the verification test script using the custom compiled FrankenPHP binary's built-in PHP CLI:

```bash
./frankenphp-custom php-cli test.php
```

### Expected Output

When running the tests, the output will demonstrate the following features in sequence:
1. **Simple Generation**: Checks simple query-response generation.
2. **Session-based Context**: Retains conversation history using session IDs, proving that the model remembers your previous questions in the session.
3. **Session Clearing**: Clears the session to demonstrate that context history has been successfully reset.



