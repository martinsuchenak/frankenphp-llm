/* This is a generated file, edit frankenphp_llm.stub.php instead.
 * Stub hash: c4a7e252915a6a156251b561628fc61f0e7d0fb1 */

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_frankenphp_llm_generate, 0, 2, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, modelPath, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, prompt, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, maxTokens, IS_LONG, 0, "100")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, strategy, IS_STRING, 0, "\"greedy\"")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, temperature, IS_DOUBLE, 0, "1.0")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, topK, IS_LONG, 0, "50")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, topP, IS_DOUBLE, 0, "0.9")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, repeatPenalty, IS_DOUBLE, 0, "1.15")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, repeatLastN, IS_LONG, 0, "64")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, systemPrompt, IS_STRING, 0, "\"\"")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, template, IS_STRING, 0, "\"\"")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, session, IS_STRING, 0, "\"\"")
ZEND_END_ARG_INFO()

#define arginfo_frankenphp_llm_generate_with_stats arginfo_frankenphp_llm_generate

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_frankenphp_llm_clear_session, 0, 2, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, modelPath, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, session, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_FUNCTION(frankenphp_llm_generate);
ZEND_FUNCTION(frankenphp_llm_generate_with_stats);
ZEND_FUNCTION(frankenphp_llm_clear_session);

static const zend_function_entry ext_functions[] = {
	ZEND_FE(frankenphp_llm_generate, arginfo_frankenphp_llm_generate)
	ZEND_FE(frankenphp_llm_generate_with_stats, arginfo_frankenphp_llm_generate_with_stats)
	ZEND_FE(frankenphp_llm_clear_session, arginfo_frankenphp_llm_clear_session)
	ZEND_FE_END
};
