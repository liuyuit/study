<?php
// php socket 测试
$host = '0.0.0.0';
$port = 99999;
// 创建一个tcp socket
$listenSocket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
// 将socket bind到 IP:Port
socket_bind($listenSocket, $host, $port);
//  监听socket
socket_listen($listenSocket);

// 进入while循环，不用担心循环死机，因为程序会阻塞在 socket_accept
while(true){
    // 此处将会阻塞住，一直到有客户端来连接服务器。阻塞状态的进程是不会占据CPU的
    // 所以你不用担心while循环会将机器拖垮，不会的
    $connection_socket = socket_accept($listenSocket);
    // 向客户端发送消息
    $msg = 'hello world ! \r\n';
    socket_write($connection_socket, $msg, strlen($msg));
    socket_close($connection_socket);
}
socket_close($connection_socket);

