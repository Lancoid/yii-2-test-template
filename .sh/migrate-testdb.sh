#!/bin/sh

set -ex

./tests/bin/yii migrate/fresh --interactive=0 --compact=1
