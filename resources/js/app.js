import './bootstrap';
import '../css/app.css';
import './status.js';
import './alerts.js';

import Alpine from 'alpinejs';
window.Alpine = Alpine;

import './init-alpine.js';

document.addEventListener('DOMContentLoaded', () => {
    Alpine.start();
});
