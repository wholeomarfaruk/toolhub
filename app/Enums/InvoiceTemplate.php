<?php

namespace App\Enums;

enum InvoiceTemplate: string
{
    case Basic = 'basic';           // Simple, clean
    case Modern = 'modern';         // Contemporary design
    case Corporate = 'corporate';   // Professional branding

    public function label(): string
    {
        return match($this) {
            self::Basic     => 'Basic',
            self::Modern    => 'Modern',
            self::Corporate => 'Corporate',
        };
    }

    public function description(): string
    {
        return match($this) {
            self::Basic     => 'Simple and clean invoice template',
            self::Modern    => 'Contemporary design with modern typography',
            self::Corporate => 'Professional corporate branding template',
        };
    }

    /**
     * Returns the plan required to EXPORT this template.
     * Free users can preview all, but can only export Basic + Modern.
     */
    public function requiredPlanForExport(): PlanTier
    {
        return match($this) {
            self::Basic     => PlanTier::Free,
            self::Modern    => PlanTier::Free,
            self::Corporate => PlanTier::Pro,
        };
    }
}
