# Change Log for Reset Points

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](http://semver.org/) 
and [Keep a CHANGELOG](http://keepachangelog.com/).

## [1.3.0] - 2017-05-04

### Added

- This changelog file.
- `'wordpoints_reset_points_before'` and `'wordpoints_reset_points'` action hooks
 before and after all user points are reset, respectively. #23
- Minified versions of CSS and JS files, to be used automatically unless
 `SCRIPT_DEBUG` is enabled. #22
- Uninstaller class to delete the module's data on uninstall. #19
- The option for the user to specify a time for the reset to take place, in addition 
 to the date. Previously the reset would just take place at 12:00 midnight, now the 
 user can choose the hour and minute. The seconds will automatically be supplied as
 `:00`. #10
 
### Changed

- Added the `.striped` class to the settings table on the admins screen. #24
- Prefix used in the module to be consistently `reset_points`. #25
  - Textdomain to be `wordpoints-reset-points` instead of `wordpoints-points-reset`.
  - Dialog class to be `wordpoints-reset-points-dialog` instead of 
   `wordpoints-points-reset-dialog`.
  - Points reset function to be `wordpoints_reset_points_type()` instead of 
   `wordpoints_points_reset_type()`.
  - Points reset on date function to be `wordpoints_points_reset_on_date()` instead
   of `wordpoints_reset_points_on_date()`.
  - JQuery UI datepicker style slug to be 
   `wordpoints-reset-points-jquery-ui-datepicker` instead of 
   `wordpoints-points-reset-jquery-ui-datepicker`.
- Moved the functions out of the main file and into `includes/functions.php`. #26
- Reset date timestamps to be calculated without an offset, using just `time()`
 instead of `current_time( 'timestamp' )`. Any existing timestamps will be updated
 to match the new pattern when the module is updated.
  
### Deprecated

- `wordpoints_points_reset_load_textdomain()` as it is no longer necessary to 
 manually load the module's textdomain.
- `wordpoints_points_reset_type()` in favor of `wordpoints_reset_points_type()`.
- `wordpoints_points_reset_on_date()` in favor of `wordpoints_reset_points_on_date()`.

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
[1.3.0]: https://github.com/WordPoints/reset-points/compare/1.2.1...1.3.0
[1.2.1]: https://github.com/WordPoints/reset-points/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/WordPoints/reset-points/compare/1.1.1...1.2.0
[1.1.1]: https://github.com/WordPoints/reset-points/compare/1.1.0...1.1.1
[1.1.0]: https://github.com/WordPoints/reset-points/compare/1.0.0...1.1.0
[1.0.0]: https://github.com/WordPoints/reset-points/compare/...1.0.0
