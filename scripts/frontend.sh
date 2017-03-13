#!/usr/bin/env bash
set -e

DEPLOYMENT_ENV='development'
BASE_PATH=$1

if [[  -z "${BASE_PATH// }" ]]; then
 echo Please specify the path of the installation
 exit 1
fi

#if [[ ! -d BASE_PATH ]]; then
# echo ${BASE_PATH} is not a valid path
# exit 1
#fi

cd ${BASE_PATH}

while getopts 'p' flag; do
  case "${flag}" in
    p) DEPLOYMENT_ENV="production" ;;
    \?) echo "Unknown option: -$OPTARG" >&2; exit 1;;
    :) echo "Missing option argument for -$OPTARG" >&2; exit 1;;
    *) echo "Unimplemented option: -$OPTARG" >&2; exit 1;;
  esac
done

printf '....Running %s build....\n' ${DEPLOYMENT_ENV}

# Consider it in installation
#echo "Composer install"
#args=('--no-scripts')
#[[ $DEPLOYMENT_ENV == 'production' ]] && args+=( '--no-dev' )
#composer install "${args[@]}" > /dev/null
#
#echo "Running artisan commands"
#php artisan clear-compiled
#php artisan optimize
#
#args=();
#[[ $DEPLOYMENT_ENV == 'production' ]] && args+=( '--force' )
#php artisan migrate "${args[@]}"
#
#echo "Clearing caches"
#php artisan cache:clear >/dev/null
#
#echo "Restarting queues"
#php artisan queue:restart >/dev/null

echo "Building frontend"
args=()
[[ $DEPLOYMENT_ENV == 'production' ]] && args+=( '--production' )
yarn install "${args[@]}" >/dev/null
bower install "${args[@]}" >/dev/null

gulp dev:be >/dev/null
gulp dev:fe >/dev/null

exit 0;