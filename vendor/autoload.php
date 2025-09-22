<?php

// Simple autoload pour WC Bookings Customizer
// Autoload simplifié pour le namespace TBWeb\WCBookingsCustomizer

spl_autoload_register(function ($class_name) {
    // Vérifier si c'est notre namespace
    if (strpos($class_name, 'TBWeb\\WCBookingsCustomizer\\') === 0) {
        // Convertir namespace en chemin de fichier
        $relative_class = str_replace('TBWeb\\WCBookingsCustomizer\\', '', $class_name);
        $file_path = __DIR__ . '/../src/' . str_replace('\\', '/', $relative_class) . '.php';
        
        // Charger le fichier s'il existe
        if (file_exists($file_path)) {
            require_once $file_path;
            return true;
        }
    }
    return false;
});
