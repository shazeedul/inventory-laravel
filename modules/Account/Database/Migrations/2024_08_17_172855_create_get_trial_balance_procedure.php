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
        DB::unprepared("
            CREATE PROCEDURE GetTrialBalance(
                IN start_date DATE,
                IN end_date DATE
            )
            BEGIN
                DECLARE done INT DEFAULT 0;
                DECLARE n_nature_id INT;
                DECLARE n_chart_of_account_name VARCHAR(191);
                DECLARE l_chart_of_account_id INT;
                DECLARE l_chart_of_account_name VARCHAR(191);
                DECLARE transaction_debit DOUBLE(15,2) DEFAULT 0.00;
                DECLARE transaction_credit DOUBLE(15,2) DEFAULT 0.00;
                DECLARE get_account_nature VARCHAR(2);

                DECLARE accounts_cursor CURSOR FOR
                    SELECT n.id AS nature_id, n.name AS nature_name, 
                        coa.id AS ledger_id, coa.name AS chart_of_account_name
                    FROM chart_of_accounts AS coa
                    LEFT JOIN chart_of_accounts AS n ON coa.parent_id = n.id
                    WHERE coa.head_level = 4 AND coa.is_active = 1
                    ORDER BY nature_name;

                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

                -- Use a temporary table to avoid locking issues with permanent tables
                CREATE TEMPORARY TABLE IF NOT EXISTS temp_table_tb (
                    chart_of_account_name VARCHAR(191),
                    debit DOUBLE(15,2) DEFAULT 0.00,
                    credit DOUBLE(15,2) DEFAULT 0.00
                );

                OPEN accounts_cursor;

                account_loop: LOOP
                    FETCH accounts_cursor INTO n_nature_id, n_chart_of_account_name, l_chart_of_account_id, l_chart_of_account_name;

                    IF done THEN
                        LEAVE account_loop;
                    END IF;

                    -- Get account nature (DR/CR)
                    SELECT GetAccountNature(l_chart_of_account_id) INTO get_account_nature;

                    -- Retrieve opening, closing, and transaction balances using the helper procedure
                    CALL GetOpeningClosingTransaction(l_chart_of_account_id, start_date, end_date, @OpeningBalance, @ClosingBalance, @TransactionBalance);

                    -- Calculate debit and credit based on account nature
                    IF get_account_nature = 'DR' THEN
                        SET transaction_debit = @ClosingBalance;
                        SET transaction_credit = 0.00;
                    ELSEIF get_account_nature = 'CR' THEN
                        SET transaction_debit = 0.00;
                        SET transaction_credit = @ClosingBalance;
                    END IF;

                    -- Insert calculated values into the temporary table
                    INSERT INTO temp_table_tb (chart_of_account_name, debit, credit)
                    VALUES (l_chart_of_account_name, transaction_debit, transaction_credit);

                END LOOP;

                CLOSE accounts_cursor;

                CREATE TEMPORARY TABLE temp_table_tb_copy AS SELECT * FROM temp_table_tb;

                -- Calculate the total debit and credit
                INSERT INTO temp_table_tb_copy (chart_of_account_name, debit, credit)
                SELECT 'Total', SUM(debit), SUM(credit)
                FROM temp_table_tb;

                -- Return the result
                SELECT * FROM temp_table_tb_copy;

                -- Clean up temporary tables
                DROP TEMPORARY TABLE IF EXISTS temp_table_tb;
                DROP TEMPORARY TABLE IF EXISTS temp_table_tb_copy;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP PROCEDURE IF EXISTS GetTrialBalance');
    }
};
