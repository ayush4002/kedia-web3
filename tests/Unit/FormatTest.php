<?php

namespace Tests\Unit;

use App\Support\Format;
use PHPUnit\Framework\TestCase;

class FormatTest extends TestCase
{
    public function test_indian_digit_grouping(): void
    {
        $this->assertSame('0', Format::inr(0));
        $this->assertSame('999', Format::inr(999));
        $this->assertSame('1,000', Format::inr(1000));
        $this->assertSame('1,00,000', Format::inr(100000));
        $this->assertSame('31,48,750', Format::inr(3148750));
        $this->assertSame('1,04,31,720', Format::inr(10431720));
        $this->assertSame('-25,000', Format::inr(-25000));
    }

    public function test_lakh_and_crore_labels(): void
    {
        $this->assertSame('₹ 29.50 Lakh', Format::lakhCrore(2950000));
        $this->assertSame('₹ 1.04 Cr', Format::lakhCrore(10431720));
    }
}
