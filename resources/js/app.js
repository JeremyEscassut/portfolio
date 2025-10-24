// File: resources/js/app.js
// Purpose: Application entry with Alpine.js initialization and Laravel bootstrap.

import './bootstrap';

// Alpine.js initialization
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Ambient halo: small, non-intrusive background glow updated on scroll/mousemove
import './halo';
