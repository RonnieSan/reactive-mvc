<?php

$app->get('/test/tester/:name', function ($name) {
    echo "I'd rather do this one, $name";
});

