<?php

function env(string $key, $fallback = null) {
    return $_ENV[$key] ?? $fallback;
}