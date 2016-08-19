# Changelog

All Notable changes to `laravel-router` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## Unreleased

### Added
- Added token route pattern

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