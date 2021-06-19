$(document).ready(function() {
    $('form').on('submit', function (e) {
        $('#submit').attr('disabled', true);
        e.preventDefault();
        $.ajax({
            url: 'get_server_info.php',
            method: 'GET',
            data: $(this).serialize()
        }).done(function (data) {
            data = JSON.parse(data);

            let host = $('#host').val();
            let version = data.version.name;
            let maxPlayers = data.players.max;
            let onlinePlayers = data.players.online;
            let ip = data.ip;

            $('#message').html(`<h2 class="server_title">${host}</h2>`)
            $('#message').append(`<p>IPv4 address: ${ip}</p>`)
            $('#message').append(`<p>Version: ${version}</p>`)
            $('#message').append(`<p>Max players: ${maxPlayers}</p>`)
            $('#message').append(`<p>Online: ${onlinePlayers}</p>`)

        }).fail(function () {
            $('#message').html(
                '<p>Failed to connect to the specified server. ' +
                'Perhaps a non-existent address is specified or the server is temporarily unavailable</p>'
            );
        }).always(function () {
            $('#submit').removeAttr('disabled');
        });
        $('#message').html('<p>Getting info...</p>');
    })
});