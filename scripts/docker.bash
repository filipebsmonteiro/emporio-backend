docker_command_build ()
{
    dispatch docker "deps"

    out "<32>Build images<0>"

    if [ "${1}" = "--with-dbg" ]; then
        out "<32;1>Bob with Xdebug<0>"
        [[ -f ./containers/bob-dbg/Dockerfile ]] \
            && docker build -q -t gfgit/bob ./containers/bob-dbg \
            || out "No Dockerfile found in ./containers/bob-dbg - ignoring"
    else
        out "<32;1>Bob<0>"
        [[ -f ./containers/bob/Dockerfile ]] \
            && docker build -q -t gfgit/bob ./containers/bob \
            || out "No Dockerfile found in ./containers/bob - ignoring"
    fi

    out "<32;1>Alice<0>"
    [[ -f ./containers/alice/Dockerfile ]] \
        && docker build -q -t gfgit/alice ./containers/alice \
        || out "No Dockerfile found in ./containers/alice - ignoring"

    out "<32;1>Mobileapi<0>"
    docker build -q -t gfgit/mobileapi ./containers/mobileapi

    out "<32;1>Catalog-search<0>"
    docker build -q -t gfgit/catalog-search ./containers/catalog-search

    out "<32;1>Catalog-front<0>"
    docker build --no-cache -q -t gfgit/catalog-front -f $PWD/containers/catalog-front/Dockerfile $PWD/containers/catalog-front/

    out ""
}

docker_command_deps ()
{
    if ! type "docker" > /dev/null; then
        out "<31>Please install Docker"
        exit 1
    fi

    if ! type "docker-compose" > /dev/null; then
        out "<31>Please install Docker Compose"
        exit 1
    fi

    docker login
}

docker_command_down()
{
    dispatch docker "deps"

    dispatch docker "stop"
    out "<32>Removing Containers"
    docker-compose rm
    out ""
}

docker_command_reload ()
{
    dispatch docker "deps"

    out "<32>Reloading Containers"
    dispatch docker "stop"
    dispatch docker "start"
    out ""
}

docker_command_restart ()
{
    dispatch docker "deps"

    out "<32>Restarting Containers"
    dispatch docker "down"
    dispatch docker "up"
    out ""
}

docker_command_start()
{
    dispatch docker "deps"
    out "<32>Starting containers"
    docker-compose start
    out ""
}

docker_command_stop()
{
    dispatch docker "deps"
    out "<32>Stopping Containers"
    docker-compose stop
    out ""
}

docker_command_up-all ()
{
    dispatch docker "deps"
    out "<32>Creating and starting ALL services"
    docker-compose up -d
    out ""
}

docker_command_up ()
{
    dispatch docker "deps"

    if [[ $# -eq 1 ]]; then
        out "<31>BEWARE:<0> I'll ups all containers, which use way too much RAM.\n"
        read -r -p "Are you sure? [y/N] " response

        case "${response,,}" in
            no|n|"")
                exit
                ;;
            yes|y)
                dispatch docker "deps"
                out "<32>Creating and starting ALL services"
                docker-compose up -d
                out ""
                exit
                ;;
            *)
                out "Invalid option."
                exit
                ;;
        esac
    fi

    dispatch docker "deps"
    ARGS=($@)
    out "<32>Creating and starting ${ARGS%* } plus required services"
    docker-compose -f docker-compose.yml up -d ${*}
    out ""
}

dispatch env "$@"
