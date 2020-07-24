#!/bin/bash
# Upload files to Github - https://github.com/talesCPV/Estoque_PHP.git
git init

git add .

git commit -m "by_script"

git remote add origin "https://github.com/talesCPV/Estoque_PHP.git"

git commit -m "by_script"

git push -f origin master


