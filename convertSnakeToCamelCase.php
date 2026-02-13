<?php

// Function to convert snake_case to camelCase
function toCamelCase($str)
{
    // Convert snake_case to camelCase
    $str = strtolower($str);
    $str = preg_replace_callback('/_([a-z])/', function ($matches) {
        return strtoupper($matches[1]);
    }, $str);
    return $str;
}

// Recursively go through all PHP files in specific directories
function convertSnakeToCamelCase($dir)
{
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

    foreach ($files as $file) {
        if ($file->isFile() && pathinfo($file->getFilename(), PATHINFO_EXTENSION) === 'php') {
            // Only process files inside Services, UseCases, and Controllers directories
            if (
                strpos($file->getRealPath(), 'Services') !== false ||
                strpos($file->getRealPath(), 'UseCases') !== false ||
                strpos($file->getRealPath(), 'Controllers') !== false
            ) {

                $content = file_get_contents($file->getRealPath());

                // 1. Convert constructor properties (e.g., protected $user_service)
                // Match class properties like protected $user_service;
                $content = preg_replace_callback('/\b(protected|private|public)\s+\$([a-z0-9_]+)\s*;/', function ($matches) {
                    // Convert the constructor property to camelCase
                    $camelCaseVar = toCamelCase($matches[2]);
                    return $matches[1] . ' $' . $camelCaseVar . ';';  // Replace with camelCase version
                }, $content);

                // 2. Convert constructor arguments inside __construct method (e.g., $user_service)
                // Match the constructor's arguments: e.g., function __construct($user_service, $role_service)
                $content = preg_replace_callback('/function\s+__construct\s*\(([^)]+)\)/', function ($matches) {
                    $args = $matches[1];

                    // Replace all arguments like $user_service with camelCase version
                    $args = preg_replace_callback('/\$([a-z0-9_]+)/', function ($matches) {
                        $camelCaseVar = toCamelCase($matches[1]);
                        return '$' . $camelCaseVar;  // Replace with camelCase version
                    }, $args);

                    return 'function __construct(' . $args . ')';  // Return the updated constructor method
                }, $content);

                // 3. Convert usage of variables inside methods (e.g., $this->user_service)
                // Match all usages of $this->variable and convert to camelCase
                $content = preg_replace_callback('/(\$this->)([a-z0-9_]+)\b/', function ($matches) {
                    // Convert class property usage to camelCase
                    $camelCaseVar = toCamelCase($matches[2]);
                    return $matches[1] . $camelCaseVar;  // Replace with camelCase version
                }, $content);

                // Only update the file if changes were made
                if ($content !== file_get_contents($file->getRealPath())) {
                    file_put_contents($file->getRealPath(), $content);
                    echo "Updated: " . $file->getRealPath() . "\n";
                } else {
                    echo "No changes for: " . $file->getRealPath() . "\n";
                }
            }
        }
    }
}

// Start conversion in the desired subdirectories (Services, UseCases, Controllers)
convertSnakeToCamelCase('app/Shared'); // Replace with the base folder if necessary
