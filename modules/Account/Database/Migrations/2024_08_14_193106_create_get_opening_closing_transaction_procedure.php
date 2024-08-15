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
            CREATE PROCEDURE GetOpeningClosingTransaction(
                IN chart_of_account_id INT,
                IN start_date DATE,
                IN end_date DATE,
                OUT OpeningBalance DOUBLE,
                OUT ClosingBalance DOUBLE,
                OUT TransactionBalance DOUBLE
            )
            BEGIN
                DECLARE opening_date DATE;
                DECLARE opening_debit DOUBLE DEFAULT 0;
                DECLARE opening_credit DOUBLE DEFAULT 0;
                DECLARE opening_balance DOUBLE DEFAULT 0;
                DECLARE opening_financial_year_id INT;
                DECLARE get_account_nature VARCHAR(2);
                DECLARE done INT DEFAULT 0;
                
                DECLARE voucher_id INT;
                DECLARE voucher_no VARCHAR(10);
                DECLARE reverse_account_name VARCHAR(191);
                DECLARE voucher_remarks TEXT;
                DECLARE transaction_date DATE;
                DECLARE transaction_debit DOUBLE DEFAULT 0;
                DECLARE transaction_credit DOUBLE DEFAULT 0;
                
                -- Cursor to fetch transactions
                DECLARE transaction_cursor CURSOR FOR
                    SELECT t.id, t.voucher_no, t.voucher_date, IFNULL(t.narration, '') AS narration,
                        IFNULL(t.debit, 0.00) AS debit, IFNULL(t.credit, 0.00) AS credit
                    FROM `account_transactions` AS t
                    INNER JOIN `chart_of_accounts` AS coas ON coas.id = t.reverse_code
                    WHERE t.chart_of_account_id = chart_of_account_id
                    AND t.voucher_date BETWEEN (
                        SELECT start_date 
                        FROM financial_years AS f 
                        WHERE f.status = 1 AND f.start_date <= start_date 
                        ORDER BY f.start_date DESC 
                        LIMIT 1
                    ) AND end_date
                    ORDER BY t.voucher_date ASC, t.id ASC;
                
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
                
                -- Check for opening balances and get the relevant data
                IF EXISTS (SELECT 1 FROM `account_opening_balances` AS op WHERE op.chart_of_account_id = chart_of_account_id) THEN
                    SELECT
                        CASE
                        WHEN o.opening_date IS NULL OR o.opening_date = start_date THEN start_date
                        ELSE o.opening_date
                        END AS opening_date,
                        IFNULL(o.debit, 0.00) AS debit,
                        IFNULL(o.credit, 0.00) AS credit,
                        o.financial_year_id
                    INTO
                        opening_date,
                        opening_debit, 
                        opening_credit, 
                        opening_financial_year_id
                    FROM `account_opening_balances` AS o
                    INNER JOIN `financial_years` f ON f.id = o.financial_year_id
                    WHERE f.end_date = (
                        SELECT DATE_ADD(f.start_date, INTERVAL -1 DAY)  
                        FROM `financial_years` AS f 
                        WHERE f.status = 1 
                        AND f.start_date <= start_date 
                        ORDER BY f.start_date DESC LIMIT 1
                    )
                    AND o.chart_of_account_id = chart_of_account_id
                    LIMIT 1;
                ELSE
                    SET opening_date = start_date;
                    SET opening_debit = 0.00;
                    SET opening_credit = 0.00;
                    SET opening_financial_year_id = 0;
                END IF;
                
                -- Get the account nature (DR/CR)
                SELECT GetAccountNature(chart_of_account_id) INTO get_account_nature;
                
                -- Initialize opening balance
                IF get_account_nature = 'DR' THEN
                    SET opening_balance = IFNULL(opening_debit, 0.00);
                ELSEIF get_account_nature = 'CR' THEN
                    SET opening_balance = IFNULL(opening_credit, 0.00);
                END IF;

                SET OpeningBalance = opening_balance;

                -- Open the cursor and iterate through transactions
                OPEN transaction_cursor;
                transaction_loop: LOOP
                    FETCH transaction_cursor INTO voucher_id, voucher_no, transaction_date, voucher_remarks, transaction_debit, transaction_credit;
                    
                    IF done THEN
                        LEAVE transaction_loop;
                    END IF;

                    -- Adjust the opening balance based on transaction nature
                    IF get_account_nature = 'DR' THEN
                        SET opening_balance = opening_balance + IFNULL(transaction_debit, 0.00) - IFNULL(transaction_credit, 0.00);
                    ELSEIF get_account_nature = 'CR' THEN
                        SET opening_balance = opening_balance - IFNULL(transaction_debit, 0.00) + IFNULL(transaction_credit, 0.00);
                    END IF;
                END LOOP;
                CLOSE transaction_cursor;

                SET ClosingBalance = opening_balance;
                SET TransactionBalance = ClosingBalance - OpeningBalance;
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
        DB::statement('DROP PROCEDURE IF EXISTS GetOpeningClosingTransaction');
    }
};
