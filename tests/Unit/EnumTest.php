<?php

namespace Tests\Unit;

use App\Enums\ProcurementStatus;
use App\Enums\UserRole;
use PHPUnit\Framework\TestCase;

class EnumTest extends TestCase
{
    public function test_user_role_options_returns_value_label_pairs(): void
    {
        $options = UserRole::options();

        $this->assertSame('Admin', $options['admin']);
        $this->assertSame('Sekretaris', $options['sekretaris']);
    }

    public function test_procurement_status_has_label_and_badge(): void
    {
        $this->assertSame('Menunggu', ProcurementStatus::MENUNGGU->label());
        $this->assertNotEmpty(ProcurementStatus::DISETUJUI->badge());
    }
}
