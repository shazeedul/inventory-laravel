<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
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
            CREATE PROCEDURE GetTrialBalanceFull(
                IN start_date DATE,
                IN end_date DATE
            )
            BEGIN
                DECLARE done INT DEFAULT 0;
                DECLARE n_nature_id INT;
                DECLARE n_chart_of_account_name VARCHAR(191);
                DECLARE g_group_id INT;
                DECLARE g_chart_of_account_name VARCHAR(191);
                DECLARE sub_group_id INT;
                DECLARE sub_group_chart_of_account_name VARCHAR(191);
                DECLARE l_chart_of_account_id INT;
                DECLARE l_chart_of_account_name VARCHAR(191);
                DECLARE transaction_debit DOUBLE(15,2);
                DECLARE transaction_credit DOUBLE(15,2);
                DECLARE get_account_nature VARCHAR(2);

                DECLARE accounts_cursor CURSOR FOR
                    SELECT n.id AS nature_id, n.name AS nature_name, 
                        g.id AS group_id, g.name AS group_name, 
                        sg.id AS sub_group_id, sg.name AS sub_group_name,
                        coa.id AS ledger_id, coa.name AS chart_of_account_name
                    FROM chart_of_accounts AS coa
                    LEFT JOIN chart_of_accounts AS sg ON coa.parent_id = sg.id
                    LEFT JOIN chart_of_accounts AS g ON sg.parent_id = g.id
                    LEFT JOIN chart_of_accounts AS n ON g.parent_id = n.id
                    WHERE coa.head_level = 4 AND coa.is_active = 1
                    ORDER BY n.id;

                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

                DROP TABLE IF EXISTS temp_table_tb_full;

                CREATE TABLE temp_table_tb_full (
                    nature_id INT,
                    nature_name VARCHAR(191),
                    group_id INT,
                    group_name VARCHAR(191),
                    sub_group_id INT,
                    sub_group_name VARCHAR(191),
                    ledger_id INT,
                    chart_of_account_name VARCHAR(191),
                    debit DOUBLE(15,2),
                    credit DOUBLE(15,2)
                );

                OPEN accounts_cursor;

                accounts_loop: LOOP
                    FETCH accounts_cursor INTO n_nature_id, n_chart_of_account_name, g_group_id, g_chart_of_account_name,
                        sub_group_id, sub_group_chart_of_account_name, l_chart_of_account_id, l_chart_of_account_name;

                    IF done THEN
                        LEAVE accounts_loop;
                    END IF;

                    SELECT GetAccountNature(l_chart_of_account_id) INTO get_account_nature;

                    CALL GetOpeningClosingTransaction(l_chart_of_account_id, start_date, end_date, @OpeningBalance, @ClosingBalance, @TransactionBalance);

                    IF get_account_nature = 'DR' THEN
                        SET transaction_debit = @ClosingBalance;
                        SET transaction_credit = 0.00;
                    ELSE
                        SET transaction_debit = 0.00;
                        SET transaction_credit = @ClosingBalance;
                    END IF;

                    INSERT INTO temp_table_tb_full (nature_id, nature_name, group_id, group_name, sub_group_id, sub_group_name, ledger_id, chart_of_account_name, debit, credit)
                    VALUES (n_nature_id, n_chart_of_account_name, g_group_id, g_chart_of_account_name, sub_group_id, sub_group_chart_of_account_name, l_chart_of_account_id, l_chart_of_account_name, transaction_debit, transaction_credit);

                    END LOOP;
                CLOSE accounts_cursor;

                SELECT t.nature_id, t.nature_name,
                    SUM(t.debit) AS nature_amount_debit, SUM(t.credit) AS nature_amount_credit,
                    t.group_id, t.group_name,
                    SUM(t.debit) AS group_amount_debit, SUM(t.credit) AS group_amount_credit,
                    t.sub_group_id, t.sub_group_name,
                    SUM(t.debit) AS sub_group_amount_debit, SUM(t.credit) AS sub_group_amount_credit,
                    t.ledger_id, t.chart_of_account_name AS ledger_name,
                    t.debit, t.credit
                FROM temp_table_tb_full AS t
                GROUP BY t.nature_id, t.nature_name, 
                        t.group_id, t.group_name, 
                        t.sub_group_id, t.sub_group_name, 
                        t.ledger_id, t.chart_of_account_name, 
                        t.debit, t.credit;

                DROP TABLE IF EXISTS temp_table_tb_full;
            END
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP PROCEDURE IF EXISTS GetTrialBalanceFull');
    }
};
