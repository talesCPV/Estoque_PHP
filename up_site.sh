#!/bin/sh
echo 'UPLOAD DO SISTEMA BACKUP EM www.flexibus.com.br/erp/system/login.php'
rm -rf .git/

mv -rf Estoque_PHP/ ../ 

HOST='ftp.flexibus.com.br' 
USER='erp@flexibus.com.br'
PASS='Xspider1977'
HDIR="/"
LDIR=$(pwd)

echo $LDIR

ncftpput -R -v -u $USER -p $PASS $HOST $HDIR $LDIR
