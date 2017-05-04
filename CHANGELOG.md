# Change Log for Reset Points

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](http://semver.org/) 
and [Keep a CHANGELOG](http://keepachangelog.com/).

## [1.2.1] - 2016-12-07

### Security

- The date picker was translated using remote scripts instead of natively through 
WordPress. Because the scripts were being pulled from a remote location insecurely, 
this could have had security implications, from man-in-the-middle attacks. Thanks 
goes to @e3amn2l for responsibly disclosing this issue, though it is very unlikely 
that this was exploited. #18

## [1.2.0] - 2015-04-20

### Added

- POT translation file. #3
- Support for updates from WordPoints.org. #2

### Changed

- Minor improvements to the admin screen. #11
- The date picker to grey out past dates. #14

### Fixed

- Not all strings being translatable. #3
- The date picker not being translated. #12

## [1.1.1] - 2014-09-06

### Fixed

- User points not actually being reset.

## [1.1.0] - 2014-09-04

### Added

- The option to have the reset value be something other than 0.

### Fixed

- A PHP "undefined index" notice when the date isn't set for a points type.

## [1.0.0] - 2014-09-04

### Added

- Ability points of all users to be reset to 0 on demand or on a scheduled date.

[unreleased]: https://github.com/WordPoints/reset-points/compare/master...HEAD
[1.2.1]: https://github.com/WordPoints/reset-points/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/WordPoints/reset-points/compare/1.1.1...1.2.0
[1.1.1]: https://github.com/WordPoints/reset-points/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/WordPoints/reset-points/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/WordPoints/reset-points/compare/...1.0.0
