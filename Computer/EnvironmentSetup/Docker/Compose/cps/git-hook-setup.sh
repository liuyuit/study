#!/bin/sh

cp -f ./git-pre-commit-hook .git/hooks/pre-commit
chmod +x .git/hooks/pre-commit

echo "Pre-commit git hook is installed!"
