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

#    docker login
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

docker_command_up ()
{
    dispatch docker "deps"
    out "<32>Creating and starting ALL services"
    docker-compose up -d
    out ""
}

docker_command_down()
{
    dispatch docker "deps"

    dispatch docker "stop"
    out "<32>Removing Containers"
    docker-compose rm
    out ""
}

dispatch env "$@"
