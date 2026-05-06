<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── Backfill: capitalize existing customer names ─────────────────────
        DB::statement("
            UPDATE customers SET
                First_name  = CONCAT(UPPER(SUBSTRING(First_name, 1, 1)),  LOWER(SUBSTRING(First_name, 2))),
                Last_name   = CONCAT(UPPER(SUBSTRING(Last_name, 1, 1)),   LOWER(SUBSTRING(Last_name, 2))),
                Middle_name = CASE
                    WHEN Middle_name IS NOT NULL AND Middle_name <> ''
                    THEN CONCAT(UPPER(SUBSTRING(Middle_name, 1, 1)), LOWER(SUBSTRING(Middle_name, 2)))
                    ELSE Middle_name
                END
        ");

        // ── BEFORE INSERT trigger ─────────────────────────────────────────────
        DB::unprepared("
            CREATE TRIGGER trg_customer_format_before_insert
            BEFORE INSERT ON customers
            FOR EACH ROW
            BEGIN
                IF NEW.First_name IS NOT NULL THEN
                    SET NEW.First_name = CONCAT(
                        UPPER(SUBSTRING(NEW.First_name, 1, 1)),
                        LOWER(SUBSTRING(NEW.First_name, 2))
                    );
                END IF;

                IF NEW.Middle_name IS NOT NULL AND NEW.Middle_name <> '' THEN
                    SET NEW.Middle_name = CONCAT(
                        UPPER(SUBSTRING(NEW.Middle_name, 1, 1)),
                        LOWER(SUBSTRING(NEW.Middle_name, 2))
                    );
                END IF;

                IF NEW.Last_name IS NOT NULL THEN
                    SET NEW.Last_name = CONCAT(
                        UPPER(SUBSTRING(NEW.Last_name, 1, 1)),
                        LOWER(SUBSTRING(NEW.Last_name, 2))
                    );
                END IF;
            END
        ");

        // ── BEFORE UPDATE trigger ─────────────────────────────────────────────
        DB::unprepared("
            CREATE TRIGGER trg_customer_format_before_update
            BEFORE UPDATE ON customers
            FOR EACH ROW
            BEGIN
                IF NEW.First_name IS NOT NULL THEN
                    SET NEW.First_name = CONCAT(
                        UPPER(SUBSTRING(NEW.First_name, 1, 1)),
                        LOWER(SUBSTRING(NEW.First_name, 2))
                    );
                END IF;

                IF NEW.Middle_name IS NOT NULL AND NEW.Middle_name <> '' THEN
                    SET NEW.Middle_name = CONCAT(
                        UPPER(SUBSTRING(NEW.Middle_name, 1, 1)),
                        LOWER(SUBSTRING(NEW.Middle_name, 2))
                    );
                END IF;

                IF NEW.Last_name IS NOT NULL THEN
                    SET NEW.Last_name = CONCAT(
                        UPPER(SUBSTRING(NEW.Last_name, 1, 1)),
                        LOWER(SUBSTRING(NEW.Last_name, 2))
                    );
                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_customer_format_before_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_customer_format_before_update");
    }
};