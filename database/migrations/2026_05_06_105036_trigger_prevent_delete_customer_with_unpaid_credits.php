<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE TRIGGER trg_prevent_delete_customer_with_unpaid_credits
            BEFORE DELETE ON customers
            FOR EACH ROW
            BEGIN
                DECLARE v_unpaid_count INT;

                -- Count unpaid and non-archived credits for this customer
                SELECT COUNT(*) INTO v_unpaid_count
                FROM credits
                WHERE CustomerID = OLD.CustomerID
                  AND status     = 'unpaid'
                  AND archived   = 0;

                IF v_unpaid_count > 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'ERROR: Cannot delete customer with unpaid credits. Please settle all outstanding balances first.';
                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_prevent_delete_customer_with_unpaid_credits");
    }
};