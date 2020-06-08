set port 37650
set user root
set host 122.112.175.111
set password juf123.#
set timeout -1

spawn ssh -p$port $user@$host
expect "*assword:*"
send "$password\r"
interact
expect eof
