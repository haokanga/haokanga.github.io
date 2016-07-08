# Schema
CREATE DATABASE IF NOT EXISTS presort_it_db;
USE presort_it_db;
DROP TABLE IF EXISTS `presort_it_table`;
CREATE TABLE `presort_it_table` (
    `index_col1` BIGINT NOT NULL,
    `index_col2` INT,
    `content_that_matters` TEXT NOT NULL
)  ENGINE=MYISAM;
ALTER TABLE `presort_it_table` ADD INDEX your_index (`index_col1`,`index_col2`);

# PreparedStatement
SELECT `content_that_matters` FROM `presort_it_table` WHERE (`index_col1` BETWEEN ? AND ?) AND (`index_col2` BETWEEN ? AND ?);

# sort data files by `index_col1`, `index_col2` before loading them