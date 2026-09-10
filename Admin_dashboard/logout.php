<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';

Auth::logout();
redirect('/index.php');
