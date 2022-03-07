#!/bin/bash

echo "Waiting for database ..."
./wait-for-it.sh -t 0 $EM_DBHOST:3306
echo "Checking if table <db_version> exists ..."

# Check if table exists
if [[ $(mysql -h $EM_DBHOST -u$EM_DBUSER -p$EM_DBPASS -e "SHOW TABLES LIKE \"db_version\";" EatMan) ]]
then
    echo "Table exists ... Nothing to do."
else
    echo "Table does not exist ... Populating Database for first use"
    mysql -h $EM_DBHOST -u$EM_DBUSER -p$EM_DBPASS EatMan < createdb.sql
fi

docker-php-entrypoint $1