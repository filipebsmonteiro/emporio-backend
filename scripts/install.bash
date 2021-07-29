install ()
{
    logo

    out "<33>Setting up prerequisites"
    dispatch config "programs"
    out ""

    out "<33>Cloning projects"
    dispatch config "clone-all"
    out ""

    out "<33>Configuring projects"
    dispatch config "fullstack"
    out ""

#    cd ${pwd}

    out "<33>Up'ing all services"
    dispatch docker "up"
    out ""

    out "<33>Generating Backend Keys"
    dispatch config "generate-keys"
    out ""

    out "<33>Configuring Database"
    dispatch config "database-import"
    out ""

#    Time for waiting buckets compression
#    sleep 20

#    out "<33>Configuring projects"
#    dispatch config "projects"
#    out ""

#    out "<33>Stopping Docker containers"
#    dispatch docker "stop"
#    out ""

    out "<33>Installation Finished"
    out ""
}
