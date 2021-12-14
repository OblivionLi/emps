<?php

namespace app;

class Config {
    // db credentials
    const DB_HOST = 'localhost';
    const DB_NAME = 'emps';
    const DB_USER = 'root';
    const DB_PASS = '';
    const SHOW_ERRORS = true;

    // email credentials
    const MAIL_HOST = 'smtp.mailtrap.io';
    const MAIL_PORT = 2525;
    const MAIL_USER = '5bc86603778757';
    const MAIL_PASS = '4a0a3ac4538ace';
    const MAIL_OFFICIAL_EMAIL = 'emps@emps.com';
}