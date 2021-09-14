# ASPTest

[![asciicast](https://asciinema.org/a/435776.svg)](https://asciinema.org/a/435776)

## How to install
    make install
    make up

## How to execute USER:CREATE
    docker-compose run --rm app php ASP-TEST USER:CREATE 'Miguel' 'Oliveira' 'miguel@test.com' 99

## How to execute USER:CREATE-PWD
    docker-compose run  --rm app php ASP-TEST USER:CREATE-PWD 1 'aA1!aA1!' 'aA1!aA1!'

## How to run tests
First make sure that you executed `make up` after that, you should execute `make test`.