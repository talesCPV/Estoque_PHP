#!/bin/bash
# Clone files to Github - https://github.com/talesCPV/Estoque_PHP.git
git init

git clone https://github.com/talesCPV/Estoque_PHP.git 

cd Estoque_PHP/

cp -rf * ../

cd ..

rm -rf Estoque_PHP/
