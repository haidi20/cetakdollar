
#!/bin/bash

php artisan migrate:refresh --path=database/migrations/2023_06_24_211243_create_salary_advance_details_table.php
php artisan migrate:refresh --path=database/migrations/2023_06_24_211649_add_importtan_detail_to_detail_payrolls_table.php
php artisan migrate:refresh --path=database/migrations/2023_06_26_005700_add_last_excel_to_period_payrolls_table.php
php artisan migrate:refresh --path=database/migrations/2023_06_26_010735_add_is_thr_to_salary_advances_table.php
# php artisan migrate:refresh --path=database/migrations/2023_06_24_123710_create_attendance_table.php
