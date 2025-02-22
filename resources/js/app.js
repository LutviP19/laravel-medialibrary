import './bootstrap';

// Broadcasting Channels
Echo.channel(`testings`)
    .listen('TestingUpdateEvent', (e) => {
        console.log(e.data);
});
