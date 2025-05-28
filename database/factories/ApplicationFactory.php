<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ApplicationFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sei' => fake()->lastName(),
            'mei' => fake()->firstName(),
            'sei_kana' => $this->generateKanaName(),
            'mei_kana' => $this->generateKanaName(),
            'sex' => rand(1, 4),
            'age' => rand(18, 65),
            'tel' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'zip21' => str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT),
            'zip22' => str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
            'pref21' => fake()->prefecture(),
            'address21' => fake()->city(),
            'street21' => fake()->streetAddress(),
        ];
    }

    /**
     * Generate random katakana name.
     *
     * @return string
     */
    private function generateKanaName(): string
    {
        $kana = ['ア', 'イ', 'ウ', 'エ', 'オ', 'カ', 'キ', 'ク', 'ケ', 'コ', 'サ', 'シ', 'ス', 'セ', 'ソ', 'タ', 'チ', 'ツ', 'テ', 'ト', 'ナ', 'ニ', 'ヌ', 'ネ', 'ノ', 'ハ', 'ヒ', 'フ', 'ヘ', 'ホ', 'マ', 'ミ', 'ム', 'メ', 'モ', 'ヤ', 'ユ', 'ヨ', 'ラ', 'リ', 'ル', 'レ', 'ロ', 'ワ', 'ヲ', 'ン'];
        return implode('', array_rand(array_flip($kana), rand(3, 5)));
    }
}
