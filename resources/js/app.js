import Echo from 'laravel-echo';
import axios from 'axios';

window.axios = axios;

window.Echo = new Echo({
    broadcaster: 'reverb',
    host: window.location.hostname + ':8080',
});

console.log('Echo loaded');
