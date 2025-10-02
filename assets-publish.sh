#!/bin/bash

rm -rf ./web/assets/* || true

./yii asset config/assets.php /dev/null