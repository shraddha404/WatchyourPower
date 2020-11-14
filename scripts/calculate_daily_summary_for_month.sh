#!/bin/bash

read -p "Month - " MONTH #give month number like 01,11, 12
read -p "Days - " DAYS

max1=9
max2=$DAYS
for i in `seq 1 $max1`
do
#echo "0$i-$MONTH-2020"
#php -q /var/www/html/knowledge/shellscripts/get_dates_and_display.php "0$i-$MONTH-2020"
php -q /home/watchyou/scripts/daily_summary_backup_current_date.php "0$i-$MONTH-2020"
echo "\n";
done

#for i in `seq 10 $max2`
#do
#echo "$i-$MONTH-2020"
#done


