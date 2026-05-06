<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared("
            CREATE TRIGGER trg_prevent_invalid_closing_reading
            BEFORE UPDATE ON shift_readings
            FOR EACH ROW
            BEGIN
                -- Block if closing_reading is being set for the first time
                IF NEW.closing_reading IS NOT NULL THEN

                    -- Must not be less than opening_reading
                    IF NEW.closing_reading < NEW.opening_reading THEN
                        SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'ERROR: Closing reading cannot be less than opening reading. Possible totalizer rollback detected.';
                    END IF;

                    -- Must not be zero if opening_reading is already greater than 0
                    IF NEW.closing_reading = 0 AND NEW.opening_reading > 0 THEN
                        SIGNAL SQLSTATE '45000'
                        SET MESSAGE_TEXT = 'ERROR: Closing reading cannot be zero when opening reading is already set.';
                    END IF;

                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_prevent_invalid_closing_reading");
    }
};