<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE FUNCTION GetAccountNature(chart_of_account_id INT)
            RETURNS VARCHAR(2)
            DETERMINISTIC
            BEGIN
                DECLARE nature VARCHAR(2);

                SELECT 
                    CASE 
                        WHEN account_type_id = 1 THEN "DR"
                        WHEN account_type_id = 2 THEN "CR"
                        WHEN account_type_id = 3 THEN "CR"
                        WHEN account_type_id = 4 THEN "DR"
                        WHEN account_type_id = 5 THEN "CR"
                        ELSE ""
                    END INTO nature
                FROM chart_of_accounts
                WHERE id = chart_of_account_id
                LIMIT 1;

                RETURN nature;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP FUNCTION IF EXISTS GetAccountNature');
    }
};
