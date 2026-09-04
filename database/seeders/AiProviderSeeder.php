<?php

namespace Database\Seeders;

use App\Models\AiProvider;
use Illuminate\Database\Seeder;

class AiProviderSeeder extends Seeder
{
    public function run(): void
    {
        AiProvider::updateOrCreate(
            ['slug' => 'openrouter'],
            [
                'name' => 'OpenRouter',
                'description' => 'Unified API access to many models (OpenAI, Anthropic, Google, and more) through a single key.',
                'is_active' => false,
                'config' => [
                    'api_key' => env('OPENROUTER_API_KEY'),
                    'base_url' => 'https://openrouter.ai/api/v1',
                    'default_model' => 'openai/gpt-4o-mini',
                    'max_tokens' => 2000,
                    'timeout' => 60,
                ],
                'icon_url' => null,
                'sort_order' => 1,
            ]
        );

        AiProvider::updateOrCreate(
            ['slug' => 'anthropic'],
            [
                'name' => 'Claude (Anthropic)',
                'description' => 'Anthropic\'s Claude models, called directly via the Messages API.',
                'is_active' => false,
                'config' => [
                    'api_key' => null,
                    'base_url' => 'https://api.anthropic.com/v1',
                    'default_model' => 'claude-sonnet-5',
                    'max_tokens' => 2000,
                    'timeout' => 60,
                ],
                'icon_url' => null,
                'sort_order' => 2,
            ]
        );

        AiProvider::updateOrCreate(
            ['slug' => 'openai'],
            [
                'name' => 'ChatGPT (OpenAI)',
                'description' => 'OpenAI\'s GPT models, called directly via the Chat Completions API.',
                'is_active' => false,
                'config' => [
                    'api_key' => null,
                    'base_url' => 'https://api.openai.com/v1',
                    'default_model' => 'gpt-4o-mini',
                    'max_tokens' => 2000,
                    'timeout' => 60,
                ],
                'icon_url' => null,
                'sort_order' => 3,
            ]
        );

        AiProvider::updateOrCreate(
            ['slug' => 'gemini'],
            [
                'name' => 'Gemini',
                'description' => 'Google\'s Gemini models, called directly via the generateContent API.',
                'is_active' => false,
                'config' => [
                    'api_key' => null,
                    'base_url' => 'https://generativelanguage.googleapis.com/v1beta',
                    'default_model' => 'gemini-2.0-flash',
                    'max_tokens' => 2000,
                    'timeout' => 60,
                ],
                'icon_url' => null,
                'sort_order' => 4,
            ]
        );

        AiProvider::updateOrCreate(
            ['slug' => 'deepseek'],
            [
                'name' => 'DeepSeek',
                'description' => 'DeepSeek models, called via an OpenAI-compatible Chat Completions API.',
                'is_active' => false,
                'config' => [
                    'api_key' => null,
                    'base_url' => 'https://api.deepseek.com/v1',
                    'default_model' => 'deepseek-chat',
                    'max_tokens' => 2000,
                    'timeout' => 60,
                ],
                'icon_url' => null,
                'sort_order' => 5,
            ]
        );
    }
}
