<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            CREATE PROCEDURE IF NOT EXISTS upload_bulk_enrollment(IN courseId INT)
            BEGIN
                DECLARE v_done INT DEFAULT 0;
                DECLARE v_email CHAR(255);
                DECLARE v_user_id INT;
                DECLARE email_cursor CURSOR FOR SELECT email FROM tmp_bulk_enrolls;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_done = 1;

                OPEN email_cursor;

                read_loop: LOOP
                    FETCH email_cursor INTO v_email;
                    IF v_done THEN
                        LEAVE read_loop;
                    END IF;

                    -- Validate the email format
                    IF v_email IS NOT NULL AND v_email != '' 
                    AND v_email REGEXP '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,}$' THEN
                        -- Check if the email exists in the users table
                        SELECT id INTO v_user_id 
                        FROM users 
                        WHERE email = v_email COLLATE utf8mb4_unicode_ci 
                        LIMIT 1;

                        -- If user exists, insert into enrolls table if not already enrolled
                        IF v_user_id IS NOT NULL THEN
                            INSERT IGNORE INTO enrolls (user_id, course_id, created_at, updated_at)
                            VALUES (v_user_id, courseId, NOW(), NOW());
                        END IF;
                    END IF;
                END LOOP;

                CLOSE email_cursor;
                DROP TEMPORARY TABLE tmp_bulk_enrolls;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS upload_bulk_enrollment');
    }
};
