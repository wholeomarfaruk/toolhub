<?php

namespace App\Enums;

enum ToolCategory: string
{
    case Calculator = 'calculator';
    case Generator  = 'generator';
    case Converter  = 'converter';
    case Formatter  = 'formatter';

    public function label(): string
    {
        return match($this) {
            self::Calculator => 'Calculators',
            self::Generator  => 'Generators',
            self::Converter  => 'Converters',
            self::Formatter  => 'Formatters',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Calculator => 'bx bx-calculator',
            self::Generator  => 'bx bx-rocket',
            self::Converter  => 'bx bx-transfer',
            self::Formatter  => 'bx bx-code-alt',
        };
    }
}
