<?php

return [

    'backup_path' => env(
        'GEMEOS_BACKUP_PATH',
        'E:/Backups Gemeos'
    ),

    'mysqldump_path' => env(
        'MYSQLDUMP_PATH',
        'C:/Program Files/MySQL/MySQL Server 8.0/bin/mysqldump.exe'
    ),

];