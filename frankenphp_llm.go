package main

/*
#include <stdlib.h>
#include "php.h"
*/
import "C"

import (
	"unsafe"

	caddycmd "github.com/caddyserver/caddy/v2/cmd"
	_ "github.com/caddyserver/caddy/v2/modules/standard"
	"github.com/dunglas/frankenphp"
	_ "github.com/dunglas/frankenphp/caddy"
)

//export_php:function frankenphp_llm_generate(string $modelPath, string $prompt, int $maxTokens = 100, string $strategy = "greedy", float $temperature = 1.0, int $topK = 50, float $topP = 0.9, float $repeatPenalty = 1.15, int $repeatLastN = 64, string $systemPrompt = "", string $template = "", string $session = ""): string
func frankenphp_llm_generate(
	modelPath *C.zend_string,
	prompt *C.zend_string,
	maxTokens int64,
	strategy *C.zend_string,
	temperature float64,
	topK int64,
	topP float64,
	repeatPenalty float64,
	repeatLastN int64,
	systemPrompt *C.zend_string,
	template *C.zend_string,
	session *C.zend_string,
) unsafe.Pointer {
	var mPath, prmpt, strat, sysPrompt, tmpl, sess string

	if modelPath != nil {
		mPath = frankenphp.GoString(unsafe.Pointer(modelPath))
	}
	if prompt != nil {
		prmpt = frankenphp.GoString(unsafe.Pointer(prompt))
	}

	strat = "greedy"
	if strategy != nil {
		strat = frankenphp.GoString(unsafe.Pointer(strategy))
	}

	if systemPrompt != nil {
		sysPrompt = frankenphp.GoString(unsafe.Pointer(systemPrompt))
	}
	if template != nil {
		tmpl = frankenphp.GoString(unsafe.Pointer(template))
	}
	if session != nil {
		sess = frankenphp.GoString(unsafe.Pointer(session))
	}

	res, err := FrankenPHPGenerate(
		mPath,
		prmpt,
		int(maxTokens),
		strat,
		temperature,
		int(topK),
		topP,
		repeatPenalty,
		int(repeatLastN),
		sysPrompt,
		tmpl,
		sess,
	)
	if err != nil {
		return frankenphp.PHPString("", false)
	}

	return frankenphp.PHPString(res, false)
}

//export_php:function frankenphp_llm_generate_with_stats(string $modelPath, string $prompt, int $maxTokens = 100, string $strategy = "greedy", float $temperature = 1.0, int $topK = 50, float $topP = 0.9, float $repeatPenalty = 1.15, int $repeatLastN = 64, string $systemPrompt = "", string $template = "", string $session = ""): string
func frankenphp_llm_generate_with_stats(
	modelPath *C.zend_string,
	prompt *C.zend_string,
	maxTokens int64,
	strategy *C.zend_string,
	temperature float64,
	topK int64,
	topP float64,
	repeatPenalty float64,
	repeatLastN int64,
	systemPrompt *C.zend_string,
	template *C.zend_string,
	session *C.zend_string,
) unsafe.Pointer {
	var mPath, prmpt, strat, sysPrompt, tmpl, sess string

	if modelPath != nil {
		mPath = frankenphp.GoString(unsafe.Pointer(modelPath))
	}
	if prompt != nil {
		prmpt = frankenphp.GoString(unsafe.Pointer(prompt))
	}

	strat = "greedy"
	if strategy != nil {
		strat = frankenphp.GoString(unsafe.Pointer(strategy))
	}

	if systemPrompt != nil {
		sysPrompt = frankenphp.GoString(unsafe.Pointer(systemPrompt))
	}
	if template != nil {
		tmpl = frankenphp.GoString(unsafe.Pointer(template))
	}
	if session != nil {
		sess = frankenphp.GoString(unsafe.Pointer(session))
	}

	res, err := FrankenPHPGenerateWithStats(
		mPath,
		prmpt,
		int(maxTokens),
		strat,
		temperature,
		int(topK),
		topP,
		repeatPenalty,
		int(repeatLastN),
		sysPrompt,
		tmpl,
		sess,
	)
	if err != nil {
		return frankenphp.PHPString("", false)
	}

	return frankenphp.PHPString(res, false)
}

//export_php:function frankenphp_llm_clear_session(string $modelPath, string $session): bool
func frankenphp_llm_clear_session(modelPath *C.zend_string, session *C.zend_string) bool {
	var mPath, sess string

	if modelPath != nil {
		mPath = frankenphp.GoString(unsafe.Pointer(modelPath))
	}
	if session != nil {
		sess = frankenphp.GoString(unsafe.Pointer(session))
	}

	FrankenPHPClearSession(mPath, sess)
	return true
}

func main() {
	caddycmd.Main()
}

