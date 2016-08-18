# Changelog

All Notable changes to `laravel-router` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## Unreleased

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