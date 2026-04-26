-- setup.sql

CREATE DATABASE IF NOT EXISTS data_analyst_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE data_analyst_db;

CREATE TABLE IF NOT EXISTS applicants (
    applicant_id    INT             NOT NULL AUTO_INCREMENT,   -- PK (Column 1)
    first_name      VARCHAR(50)     NOT NULL,                  -- Column 2
    last_name       VARCHAR(50)     NOT NULL,                  -- Column 3
    email           VARCHAR(100)    NOT NULL UNIQUE,           -- Column 4
    phone_number    VARCHAR(20)     NOT NULL,                  -- Column 5
    years_experience TINYINT UNSIGNED NOT NULL DEFAULT 0,      -- Column 6
    programming_language VARCHAR(50) NOT NULL,                 -- Column 7
    highest_degree  ENUM(
                        'High School Diploma',
                        "Associate's Degree",
                        "Bachelor's Degree",
                        "Master's Degree",
                        'Doctorate (Ph.D.)'
                    ) NOT NULL,                                -- Column 8
    date_added      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP, -- Column 9
    PRIMARY KEY (applicant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO applicants
    (first_name, last_name, email, phone_number, years_experience, programming_language, highest_degree)
VALUES
    ('Maria', 'Santos',  'maria.santos@email.com',  '09171234567', 3, 'Python',  "Bachelor's Degree"),
    ('Juan',  'Reyes',   'juan.reyes@email.com',    '09281234567', 5, 'R',       "Master's Degree"),
    ('Ana',   'Cruz',    'ana.cruz@email.com',       '09391234567', 1, 'SQL',     "Bachelor's Degree");
