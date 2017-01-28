# Changelog

All notable changes to `laravel-router` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## Unreleased

### Changed

- Partially rewritten readme
- Tests and test environment

### Removed

- Laravel 5.1 and 5.2 support

## v2.1.2 (2017-01-24)

### Changed

- Stop testing PHP 5.6 in TravisCI

### Fixed

- Fix orchestra/testbench for Laravel 5.4
- Fix mockery package dependency

## v2.1.1 (2017-01-24)

### Fixed

- Invalid composer.json

## v2.1.0 (2017-01-24)

### Added

- Add support for uppercase token route parameters
- Added token route pattern
- Laravel 5.4 support
- PHP 7 support

### Changed

- Locked down composer package constraints more vigorously
- Removed unnecessary package requirements

### Removed

- PHP 5.6 support

## v2.0.4 (2016-08-18)

### Changed

- Updated Travis CI configuration to reflect Laravel 5.3 changes and added PHP 7.1 support 

## v2.0.3 (2016-08-18)

### Fixed

- Used correct Laravel 5.3 and package dependencies

## v2.0.0 (2016-08-18)

### Added

- Detect use of the Dingo/Api package and auto-assign it's API router for ease-of-use

### Changed

- Renamed `BaseRouter` to `Router`
- Renamed router test accordingly
- Uniform formatting
- Lock minimum Laravel version at 5.3

### Removed

- Removed the `Router` interface

### Fixed

- Removed an unused imported class (ExtendedRouter) in the base router
- Updated the router service provider to reflect Laravel 5.3 changes
