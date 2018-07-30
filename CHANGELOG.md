# Changelog

All notable changes to `laravel-router` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## Unreleased

## 5.0.0 (2018-07-30)

### Changed

- Added return types (breaking change)
- Use strict array checking

## 4.0.0 (2018-03-06)

### Added

- Add testing environment for Laravel 5.6
- Test PHP 7.2 on Travis

### Changed

- Change token route parameter length from 100 to 60

### Removed

- Dropped support for PHP 7
- Dropped support for Laravel 5.4

## 3.1.1 (2017-10-27)

### Fixed

- Upgraded Mockery to 1.0 which fixes tests running under PHP 7.2

## 3.1.0 (2017-07-10)

### Added

- Add GitHub issue template
- Add GitHub pull request template

### Changed

- Updated readme
- Rewrite code of conduct
- Rewrite contribution guidelines

## 3.0.0 (2017-06-24)

### Added

- Added Laravel 5.5 auto-discovery

### Changed

- Removed the custom kernel in favor of a trait to register routers
- Renamed and made the bootstrap pattern router optional (now called `RegisterRoutePatterns` to better reflect what it does)
- Added and refactored tests and test environment
- Rewritten readme

### Removed

- Dropped support for Laravel 5.1, 5.2, and 5.3
- Removed the extended router and all middleware functionality (to be moved to another package)
- Removed namespace functionality (didn't add any real value)
- Removed API router check in base router

## 2.1.2 (2017-01-24)

### Changed

- Stop testing PHP 5.6 in TravisCI

### Fixed

- Fix orchestra/testbench for Laravel 5.4
- Fix mockery package dependency

## 2.1.1 (2017-01-24)

### Fixed

- Invalid composer.json

## 2.1.0 (2017-01-24)

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

## 2.0.4 (2016-08-18)

### Changed

- Updated Travis CI configuration to reflect Laravel 5.3 changes and added PHP 7.1 support

## 2.0.3 (2016-08-18)

### Fixed

- Used correct Laravel 5.3 and package dependencies

## 2.0.0 (2016-08-18)

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
