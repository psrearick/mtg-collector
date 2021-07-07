#!/bin/sh

echo 'Importing Scryfall data...';
php artisan import:scryfall

echo 'Generating api map...';
php artisan generate:apimap

echo 'Importing Scryfall pricing data...';
php artisan import:providerprices

echo 'Completed Running';
