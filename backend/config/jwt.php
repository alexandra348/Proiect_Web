<?php

return [
    "secret" => $_ENV['JWT_SECRET'] ?? 'super_secret',
    "issuer" => "softdrinks-api",
    "expiry" => 3600
];