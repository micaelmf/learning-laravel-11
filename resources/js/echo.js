// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';

// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: '8e29956be4ac4ee5a3f241f9ac2feb79',  // Chave do laravel-echo-server.json
//     wsHost: window.location.hostname,
//     wsPort: 6001,
//     wssPort: 6001,
//     forceTLS: false,
//     disableStats: true,
//     enabledTransports: ['ws', 'wss'],
//     cluster: 'sa1'
// });

// window.Echo.channel('notifications')
//     .listen('TaskOverdue', (e) => {
//         console.log(e);
//         alert('Task Overdue');
//     });


// ---------------------------


// Config 2 (M)
// import Echo from 'laravel-echo';

// window.io = require('socket.io-client');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     wsHost: window.location.hostname + ':6001',
//     wsPort: 6001,
//     forceTLS: false,
//     disableStats: true,
//     encrypted: false,
// });


// Config 3 (exemplo inicial da documentação)
// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'reverb',
//     key: import.meta.env.VITE_REVERB_APP_KEY,
//     wsHost: import.meta.env.VITE_REVERB_HOST,
//     wsPort: import.meta.env.VITE_REVERB_PORT,
//     wssPort: import.meta.env.VITE_REVERB_PORT,
//     forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });


import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

window.Pusher = Pusher;

// Configuração opcional do Toastr
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-bottom-center",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
}

const userId = window.Laravel.user.id; // Obter o ID do usuário da variável global
const token = getCookie('XSRF-TOKEN'); // Substitua 'auth_token' pelo nome do cookie que contém o token

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    encrypted: true,
    authEndpoint: '/broadcasting/auth', // Certifique-se de que este endpoint está correto
    auth: {
        headers: {
            Authorization: 'Bearer ' + token, // Certifique-se de que o token de autenticação está correto
        },
    },
});

// funciona como canal publico
// window.Echo.channel('notifications')
//     .listen('TaskOverdue', (e) => {
//         console.log(e);
//         toastr.info(`<a href="/tasks/${e.data.task.id}/edit">${e.data.task.name}</a>`, 'Lembrete de Tarefa', { allowHtml: true });

//         const audio = new Audio('/audio/notification.mp3');
//         audio.play().catch(error => {
//             console.error('Erro ao reproduzir o áudio:', error);
//         });
//     });

window.Echo.private('notifications.' + userId)
    .listen('TaskOverdue', (e) => {
        toastr.info(`<a href="/tasks/${e.data.task.id}/edit">${e.data.task.name}</a>`, 'Lembrete de Tarefa', { allowHtml: true });

        const audio = new Audio('/audio/notification.mp3');
        audio.play().catch(error => {
            console.error('Erro ao reproduzir o áudio:', error);
        });
    });
