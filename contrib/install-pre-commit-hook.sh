#!/usr/bin/env bash
DIR=$(pwd)
cp $DIR/contrib/pre-commit $DIR/.git/hooks/pre-commit && chmod +x $DIR/.git/hooks/pre-commit
