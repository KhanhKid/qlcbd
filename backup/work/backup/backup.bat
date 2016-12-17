del "C:\wamp\www\qlcbd\public\backup\work\backup\backup-qlcbd %DATE:~7,2%-%DATE:~4,2%-%DATE:~-4%*

cd C:\wamp\bin\mysql\mysql5.6.12\bin
mysqldump --default-character-set=utf8 --user=root --password="2014@uit" qlcbd > "C:\wamp\www\qlcbd\public\backup\work\backup\backup-qlcbd %DATE:~7,2%-%DATE:~4,2%-%DATE:~-4% ( %time:~0,2%.%time:~3,2%.%time:~6,2% ).sql"