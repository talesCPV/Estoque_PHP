
# Script by Carlos Augusto Malucelli (nopp)
HOST='ftp.flexibus.com.br'
USER='sistema@flexibus.com.br'
PASSWD='Xspider0 ' 
FILE='/opt/lampp/htdocs/system/sql.txt'

ftp -n $HOST <<END_SCRIPT
quote user $USER
quote PASS $PASSWD
put $FILE
quit
END_SCRIPT