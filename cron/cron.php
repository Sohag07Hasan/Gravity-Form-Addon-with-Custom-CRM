<?php

/*
 * This script runs by cron
 */

set_time_limit(300);

include '../../../../wp-load.php';
Offline_CRM :: process_schudle();
?>