#!/usr/bin/env bash

config_ ()
{
    dispatch config "help"
}

config_option_h ()
{
    dispatch config "help"
}

config_option_help ()
{
    dispatch config "help"
}

config_command_help ()
{
    logo
    out "
<33>Usage:<0>
  ./env.sh config [command] [options]

<33>Options:
  <32;1>--help<0> (-h)               Display this help message

<33>Available commands:
  <32;1>composer-install<0>          Runs composer install for project
  <32;1>db<0>                        Configure Database Migrations and Seeds
  <32;1>new<0>                       Configure new environment <35>(set keys, db and composer)
  <32;1>keys<0>                      Configure Application key and JWT key
  <32;1>optimize<0>                  Run Artisan Optimize
"
#    docker exec -it app /bin/bash
}

config_command_check-environment ()
{
    # sudo if not root
    if [ ! $(id -u) -eq 0 ]; then
        SUDO=sudo
    else
        SUDO=
    fi

    out "<32>Setting up system groups..."
    $SUDO usermod -aG docker $(whoami)

    out "<32>Setting up domain..."
    command -v cid > /dev/null && {
        $SUDO cid usersgrp > /dev/null 2>&1 && {
            out "    <92>Configuring CID user group..."
            $SUDO cid usersgrp add $(whoami) > /dev/null 2>&1 || true
        } || {
            out "CID: there's no usersgrp command available, so there's nothing to do, moving on..."
        }
    }

    ismac && {
        command -v brew > /dev/null || {
            /usr/bin/ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"
        }
        command -v curl > /dev/null || {
            brew install --with-openssl curl
        }
    }

    out "<32>Checking docker..."
    command -v docker > /dev/null && {
        out "$(docker --version)"
    } || {
        ismac && {
            out "Please install Docker Desktop for Mac and rerun $0 $*"
            out ""
            out "https://docs.docker.com/docker-for-mac/install/"
            exit 1
        }

        iswindows && {
            out "Please install Docker Desktop for Windows and rerun $0 $*"
            out ""
            out "https://docs.docker.com/docker-for-windows/install/"
            exit 1
        }

        out "<33>You don't have docker. Installing..."
        command -v curl > /dev/null && {
            curl -sSL https://get.docker.com/ | sh
        } || {
            wget -qO - https://get.docker.com/ | sh
        }
    }

    out "<32>Checking docker-compose..."
    command -v docker-compose > /dev/null && {
        out "$(docker-compose --version)"
    } || {
        ismac && {
            out "Please install Docker Desktop for Mac and rerun $0 $*"
            out ""
            out "https://docs.docker.com/docker-for-mac/install/"
            exit 1
        }

        iswindows && {
            out "Please install Docker Desktop for Windows and rerun $0 $*"
            out ""
            out "https://docs.docker.com/docker-for-windows/install/"
            exit 1
        }

        out "<33>You don't have docker-compose. Installing..."
        command -v curl > /dev/null && {
            DOCKER_LATEST=$(curl -L -H "Accept: application/json" https://github.com/docker/compose/releases/latest | \
                        cut -d , -f 2 | \
                        cut -d : -f 2 | \
                        tr -d \"
                        )
            $SUDO curl -L "https://github.com/docker/compose/releases/download/${DOCKER_LATEST}/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
        } || {
            DOCKER_LATEST=$(wget --header="Accept: application/json" -qO- https://github.com/docker/compose/releases/latest | \
                        cut -d , -f 2 | \
                        cut -d : -f 2 | \
                        tr -d \"
                        )
            $SUDO wget "https://github.com/docker/compose/releases/download/${DOCKER_LATEST}/docker-compose-$(uname -s)-$(uname -m)" -qO /usr/local/bin/docker-compose
        }
    }

    out "<32>Checking PHP..."
    command -v php > /dev/null && {
        out "$(php --version)"
    } || {
        if islinux; then
            $SUDO apt-get install php-cli php-dev php-curl
        elif ismac; then
            brew install --with-homebrew-curl php
        else
            out "Please install php-cli and php-curl and rerun $0 $*"
            exit 1
        fi
    }

    ## Set hosts file
    ###############################################################################

    ## Extract hosts from docker-compose.yml
#    HOSTS=$(
#        cat docker-compose*.yml | \
#        grep VIRTUAL_HOST | \
#        cut -d'=' -f2 | \
#        sed 's/,/\n/g' | \
#        sed 's/^\.//g' | \
#        envsubst | \
#        eval echo $(cat -) | \
#        sort | uniq
#    )
#
#    ## Extract TLDs (just for grouping)
#    DOMAINS=$(
#        echo $HOSTS | \
#        sed 's/ /\n/g' | \
#        sed -E 's/.*\.([a-z]+)$/\1/g' | \
#        sort | uniq | xargs
#    )
#
#    ## Generate hosts' entries
#    HOSTSENTRIES=
#    for d in $DOMAINS; do
#        HOSTSLINE=$(
#            echo $HOSTS | \
#            sed 's/ /\n/g' | \
#            grep \.$d | \
#            xargs | \
#            echo "127.0.0.1 $(cat -)"
#        )
#
#        HOSTSENTRIES+="\n$HOSTSLINE\n"
#    done
#
#    # remove old baks
#    ls /etc/hosts.bak.* | sort -hr | tail -n+3 | xargs $SUDO rm
#
#    # Black-magic add/replace ;)
#    $SUDO cp /etc/hosts "/etc/hosts.bak.$(date +%Y%m%d%H%M%S)" && \
#        (
#            sed -n '1,/^# DFTECH BEGIN/{/^# DFTECH BEGIN/!p;}' /etc/hosts; \
#            echo "# DFTECH BEGIN"; \
#            echo -e $HOSTSENTRIES; \
#            echo "# DFTECH END"; \
#            sed -n '/^# DFTECH END/,${/^# DFTECH END/!p;}' /etc/hosts; \
#        ) | \
#        $SUDO tee /etc/hosts.new > /dev/null | \
#        sed -n '/^# DFTECH BEGIN/,/^# DFTECH END/p' && \
#        $SUDO mv /etc/hosts.new /etc/hosts
}

config_command_new ()
{
    dispatch config "check-environment"

    if [ ! "$(docker ps -q -f name=app)" ]; then
        out "<31>Container app must be UP"
        return 1
    fi

    dispatch config "composer-install"

    dispatch config "keys"

    dispatch config "db"
}

config_command_keys ()
{
    cp .env.example ./.env
    docker exec -it app php artisan optimize
    docker exec -it app php artisan key:generate
    docker exec -it app php artisan jwt:secret
    docker exec -it app php artisan vendor:publish --tag="cors"
    docker exec -it app php artisan optimize
}

config_command_optimize ()
{
    docker exec -it app php artisan cache:clear
    docker exec -it app php artisan route:clear
    docker exec -it app php artisan config:clear
    docker exec -it app php artisan optimize
}

config_command_db ()
{
    docker exec -it app php artisan optimize
    docker exec -it app php artisan migrate
    docker exec -it app php artisan migrate --path=database/migrations/loja
    docker exec -it app php artisan db:seed
    docker exec -it app php artisan optimize
}

config_command_composer-install ()
{
#    repo=${1}
#    out "<92>Running composer install (your repo's password will be asked): "
#    cp -ra "$HOME/.ssh" "${pwd}/tmp/ssh"
#
#    docker run -d --name composer-container -w /app/ -v "${pwd}/../${repo}":/app -v "${pwd}/tmp/ssh":/root/.ssh --entrypoint sleep gfgit/php-ci:7.1 3600
#
#    CONFIG=/root/.ssh/config
#
#    if [ -f "${pwd}/tmp/ssh/config" ]; then
#        docker exec -it composer-container bash -c 'chmod 400 /root/.ssh/config && chown root.root /root/.ssh/config'
#    fi
#
#    docker exec -it composer-container bash -c 'eval $(ssh-agent) && ssh-add && composer install --no-scripts --ignore-platform-reqs'
#
#    docker rm -f composer-container
#
#    [ -d "${pwd}/tmp/ssh" ] && rm -rf "${pwd}/tmp/ssh"

    sudo rm -Rf vendor
    out "<92>Downloading / Installing vendors packages..."
    docker exec -it app composer install --no-interaction --no-cache
    docker exec -it app php artisan optimize
}

config_command_clone ()
{
    ## Main repositories for the projects
    project_emporio="github"
    project_emporio_backend="github"

    vcshost_github="git@github.com"
    vcspath_github=":filipebsmonteiro/"

    repo=${1}

    case "${2,,}" in
        github)
            vcs="github"
            ;;
        ""|*)
            vcs="project_${repo//-/_}"
            vcs=${!vcs:="bitbucket"}
            ;;
    esac

    vcshost="vcshost_$vcs"
    vcshost=${!vcshost}

    vcspath="vcspath_$vcs"
    vcspath=${!vcspath}

    [[ ! $(ssh -T ${vcshost} 2>&1 | grep denied | wc -l) -eq 0 ]] && {
        out "<31>Error, please check your ${vcshost##*@} settings"
        exit 1;
    }

    out ""
    out "<32>Cloning ${repo} repository"

    test -d ../${repo} || {
        git clone ${vcshost}${vcspath}${repo}.git ../${repo} || {
            echo "<31>Error, you don't have permission to fetch ${repo} repository from ${vcshost##*@}<0>"
            exit 1;
        }
    }
}

config_command_clone-all ()
{
    dispatch config "clone emporio"
    dispatch config "clone emporio-backend"
}

config_chmod_777 ()
{
    PATH_CHMOD=$1

    if [ $(stat -c "%a" $PATH_CHMOD) -ne 777 ]; then
        sudo chmod -R 777 "$PATH_CHMOD"
    fi

}
