#!/usr/bin/env bash

user=$(id -u)
group=$(id -g)

php_cli=0
dc_cli="docker-compose-php-cli.yml"
dir=$(basename ${PWD%})
cli_name="cli"

print_help() {
    echo "Usage: $0 [option...] {install|build|dev|exec|down|cmd} [params...]" >&2
    echo "Options:                                                           "
    echo "  -c, --cli                   run php-cli container"
    echo "  -h, --help                  Print this help      "
    echo
    echo "Commands:"
    echo "  install                     install composer dependencies         "
    echo "  build                       build container                       "
    echo "  dev                         run container                         "
    echo "  exec                        execute command inside container      "
    echo "  down                        stop container, close network         "
    echo "  cmd                         cmd docker-compose (run,exec,up...)   "
    echo
    exit 1
}

dc() {
    if [ $php_cli -eq 1 ]; then
        USER_ID=$user GROUP_ID=$group docker-compose -f $dc_cli -p "$dir$cli_name" $@
        return 0
    fi
    USER_ID=$user GROUP_ID=$group docker-compose $@
}

dr() {
    dc run --rm $@
}

de() {
    dc exec $@
}

dcc() {
    #if [ ! -f composer.lock ] || [ ! -f vendor/autoload.php ]; then
    dr --no-deps php composer install
    #fi
}

################################
# Check if parameters options  #
# are given on the commandline #
################################
while :; do
    case "$1" in
    -c | --cli)
        php_cli=$((php_cli = 1))
        shift 1
        ;;
    -h | --help)
        print_help # Call your function
        exit 0
        ;;
    --) # End of all options
        shift
        break
        ;;
    -*)
        echo "Error: Unknown option: $1" >&2
        print_help
        exit 1
        ;;
    *) # No more options
        break
        ;;
    esac
done

if [ $1 ]; then

    case "$1" in
    install)
        echo "Installation des dépendances Composer install"
        if [ ! -f composer.lock ] || [ ! -f vendor/autoload.php ]; then
            dcc
        fi
        ;;
    build)
        shift 1
        echo "Build du container $@"
        dc build $@
        ;;
    dev)
        shift 1
        echo "Start des containers"
        if [ ! -f composer.lock ] || [ ! -f vendor/autoload.php ]; then
            dcc
        fi
        dc up
        ;;
    exec)
        shift 1
        echo "execution de $@"
        de $@
        ;;
    down)
        shift 1
        echo "Arrèt des containers"
        dc down
        ;;
    cmd)
        shift 1
        echo "Lancement d'une commande"
        dc $@
        ;;
    *)
        print_help
        exit 1
        ;;
    esac

else
    echo "pas d'argument à ce script"
    print_help
    exit 1
fi
exit 0
