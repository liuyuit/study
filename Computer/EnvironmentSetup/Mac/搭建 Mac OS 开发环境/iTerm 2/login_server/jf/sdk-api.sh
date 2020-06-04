set port 22
set user root
set host 122.112.137.125
set password juf123.#
set timeout -1

spawn ssh -p$port $user@$host
expect "*assword:*"
send "$password\r"
interact
expect eof
