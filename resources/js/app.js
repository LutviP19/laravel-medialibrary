import './bootstrap';


// Notifications
// console.log( window.userId );
Echo.private('App.Models.User.' + userId)
    .notification((notification) => {
        console.log(notification);
});

// console.log( window.teamId );
Echo.private('App.Models.UserTeam.' + teamId)
    .notification((notification) => {
        console.log(notification);
});

// Broadcasting Channels
Echo.channel(`testings`)
    .listen('TestingUpdateEvent', (e) => {
        console.log(e.testing);
});
