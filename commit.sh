#!/bin/bash
# Upload files to Github - https://github.com/talesCPV/Estoque_PHP.git
# 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

git init

git add .

git commit -m "by_script"

git remote add origin "https://github.com/talesCPV/Estoque_PHP.git"

git commit -m "by_script"

git push -f origin master


