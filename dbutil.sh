#! /bin/bash

if [[ -z $1 ]]; then
    echo "Please supply an argument (export or import)."
    exit 1
fi

username=reserver
dbname=reserver
dir=$(dirname "$(readlink -f "$0")")

case $1 in
    export)
        mysqldump -u $username -p $dbname > $dir/reserver.sql; exit 1 ;;
    import)
        mysql -u $username -p $dbname < $dir/reserver.sql; exit 1 ;;
    *)
        echo Invalid command; exit 1 ;;
esac
