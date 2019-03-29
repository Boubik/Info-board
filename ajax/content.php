<?php

$type = filter_input(INPUT_GET, 'p');
switch ($type) {
    case 'rozvrh':
        echo '<h1>Rozvrh tříd</h1>';
        for ($i = 0; $i < 400; $i++) {
            echo mt_rand(100, 499) . ' ';
        }
        break;
    case 'aktuality':
        echo '<h1>Aktuality na škole</h1>';
        for ($i = 0; $i < 400; $i++) {
            echo mt_rand(500, 999) . ' ';
        }
        break;
    default:
        echo '<h1>?!?</h1>';
        break;
}
