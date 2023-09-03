# PhpStorm 

## Linux

Instructions:
- Install `PHP` on your machine
- Install `xdebug` on your machine (see https://xdebug.org/wizard) 

PhpStorm configuration:
- `File > Settings`
  - `PHP > CLI Interpreter > ...`:
    - `+ > /usr/bin/php`
    - Configuration options:
      - xdebug.mode=debug
      - xdebug.client_host=YOUR_HOST_MACHINE_IP (e.g. 192.168.178.200)
  - `PHP > Debug`:
    - Uncheck `Break at first line in PHP scripts`
    - Uncheck `Force break at first line when no path mapping specified`
    - Uncheck `Force break at first line when a script is outside the project`
- Run Configuration for Tests:
  - PhpUnit
  - Defined in the configuration file (`phpunit.xml`)
  - Test Runner options: `--coverage-html tests/coverage`
  - Preferred Coverage Engine: XDebug
  - Environment variables: `XDEBUG_MODE=debug,coverage`


## Windows

Should be similar to Linux.


# CLI

## Linux

- `XDEBUG_MODE=debug,coverage composer run-script test-coverage`


## Windows

Cmd:
- `set XDEBUG_MODE=debug,coverage && composer run-script test-coverage`

Powershell:
- `$env:XDEBUG_MODE="debug,coverage"; composer run-script test-coverage`
