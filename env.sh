#! /bin/bash

pwd=$(pwd)

source "${pwd}/scripts/dispatch.bash"
source "${pwd}/scripts/out.bash"

source "${pwd}/scripts/logo.bash"

source "${pwd}/scripts/checks.bash"

source "${pwd}/scripts/config.bash"
source "${pwd}/scripts/docker.bash"


env_ ()
{
    dispatch env "help"
}

env_call_ ()
{
    out "<31>Command not found"
}

env_option_h ()
{
    dispatch env "help"
}

env_option_help ()
{
    dispatch env "help"
}

env_command_help ()
{
#    command -v python > /dev/null && {
#        local PYTHON_SCRIPT="from yaml import load as loadYAML; print(' '.join(list(loadYAML(open(\"${pwd}/docker-compose.yml\"))[\"services\"].keys())))"
#        local SERVICES=$(python -c "$PYTHON_SCRIPT")
#    }

    logo
    out "
<33>Usage:<0>
  ./emporio.sh [command] [options]

<33>Options:
  <32;1>--help<0> (-h)               Display this help message

<33>Available commands:
  <32;1>up<0>                       Start containers
  <32;1>down<0>                     Stop containers
  <32;1>config<0>                   Configure This Project <35>(see help command)
"
}

env_command_config ()
{
    dispatch config "${@}"
}

env_command_optimize ()
{
    docker exec -it app php artisan optimize
}

env_command_start ()
{
    docker exec -it app php artisan serve --host=0.0.0.0
}

env_command_up ()
{
    dispatch docker "up"
}

env_command_down ()
{
    dispatch docker "down"
}

dispatch env "$@"
