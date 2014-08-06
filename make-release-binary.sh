#!/bin/bash

DIR="$( pwd )"
VERSION=`cat VERSION.md`
echo $VERSION
rm -f $DIR/form_builder_mailer-*.zip
zip -r $DIR/form_builder_mailer-$VERSION.zip . -i "/src/*"
