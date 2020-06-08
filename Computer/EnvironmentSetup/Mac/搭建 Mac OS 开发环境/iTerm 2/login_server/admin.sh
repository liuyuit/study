set port 37650
set user root
set host 122.112.230.232
set password juf123.#
set timeout -1

spawn ssh -p$port $user@$host
expect "*assword:*"
send "$password\r"
interact
expect eof
