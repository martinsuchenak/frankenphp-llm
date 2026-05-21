package main

import (
	"encoding/json"

	scriptlingllmlib "github.com/martinsuchenak/scriptling-llm-lib"
)

type GenerationResult struct {
	Text            string  `json:"text"`
	PromptTokens    int     `json:"prompt_tokens"`
	GeneratedTokens int     `json:"generated_tokens"`
	PrefillMs       float64 `json:"prefill_ms"`
	DecodeMs        float64 `json:"decode_ms"`
	TokensPerSecond float64 `json:"tokens_per_second"`
}

func FrankenPHPGenerate(
	modelPath string,
	prompt string,
	maxTokens int,
	strategy string,
	temperature float64,
	topK int,
	topP float64,
	repeatPenalty float64,
	repeatLastN int,
	systemPrompt string,
	templateName string,
	sessionID string,
) (string, error) {
	result, _, _, _, _, err := scriptlingllmlib.GenerateWithCache(
		modelPath, prompt, maxTokens, strategy, temperature,
		topK, topP, repeatPenalty, repeatLastN,
		systemPrompt, templateName, sessionID,
	)
	return result, err
}

func FrankenPHPGenerateWithStats(
	modelPath string,
	prompt string,
	maxTokens int,
	strategy string,
	temperature float64,
	topK int,
	topP float64,
	repeatPenalty float64,
	repeatLastN int,
	systemPrompt string,
	templateName string,
	sessionID string,
) (string, error) {
	result, nGen, nPrompt, prefillMs, decodeMs, err := scriptlingllmlib.GenerateWithCache(
		modelPath, prompt, maxTokens, strategy, temperature,
		topK, topP, repeatPenalty, repeatLastN,
		systemPrompt, templateName, sessionID,
	)
	if err != nil {
		return "", err
	}

	var tps float64
	if decodeMs > 0 {
		tps = float64(nGen) / (decodeMs / 1000.0)
	}

	genRes := GenerationResult{
		Text:            result,
		PromptTokens:    nPrompt,
		GeneratedTokens: nGen,
		PrefillMs:       prefillMs,
		DecodeMs:        decodeMs,
		TokensPerSecond: tps,
	}

	jsonBytes, err := json.Marshal(genRes)
	if err != nil {
		return "", err
	}

	return string(jsonBytes), nil
}

func FrankenPHPClearSession(modelPath string, sessionID string) {
	scriptlingllmlib.ClearSessionWithCache(modelPath, sessionID)
}
