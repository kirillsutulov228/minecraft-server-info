<?php

/**
 * This function uses Minecraft Protocol.
 * For more info see {@link https://wiki.vg/Protocol}
 * @param string $host host name of the server
 * @param int $port port of the server (default is 25565)
 * @return array|false data array on success, otherwise false
 */
function get_minecraft_server_info(string $host, int $port = 25565) {

    $connection = socket_create(AF_INET, SOCK_STREAM, 0);

    if (!socket_connect($connection, $host, $port)) {
        return false;
    }
    /* Handshake packet */
    $handshake = "\x00"
        ."\x08"
        .pack('c', strlen($host)).$host
        .pack('n', $port)
        ."\x01";
    $handshake = pack('c', strlen($handshake)).$handshake;

    /* Status request */
    $request = "\x01\x00";

    socket_write($connection, $handshake);
    socket_write($connection,$request);

    sleep(1);

    $response = socket_read($connection, 100000);

    if (!isset($response) || empty($response)) {
        return false;
    }

    $response = json_decode(unpack('n/c/n/a*info', $response)['info'], true);

    socket_getpeername($connection, $ip);
    $response['ip'] = $ip;

    return $response;
}

error_reporting(0);

$host = $_REQUEST['host'];
$info = get_minecraft_server_info($host);

if (!$info) {
    sleep(2);
    http_response_code(404);
}
else {
    echo json_encode($info);
}