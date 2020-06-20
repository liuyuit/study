alias composer='docker run -i -t --rm --privileged=true  -v $PWD:/app lnmp_composer composer'
alias artisan="docker run -i -t --rm --privileged=true -w "/data/www/$(basename `pwd`)"  -v $PWD:/data/www/"$(basename `pwd`)" lnmp_php7 php artisan"
alias ll='ls -l'
