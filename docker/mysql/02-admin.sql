-- admin / admin — смените пароль после первого входа
INSERT IGNORE INTO `core_users` (`u_login`, `u_pass`, `visible`, `is_admin_sw`, `date_added`)
VALUES ('admin', 'ad7123ebca969de21e49c12a7d69ce25', 'Y', 'Y', NOW());
