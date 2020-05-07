Changelog
=========

1.3.0
-----

### Added:
- Protected method `LogExceptionTrait::setChaining()` can be used to disable or enable exception chaining for the
class, which uses `\WebArch\LogTools\Traits\LogExceptionTrait`, but not for single `LogExceptionTrait::logException()`
call

### Changed:
- Chaining is enabled by default
- Trace string in the context is replaced by the array of strings.

### Removed:
- Parameter `$enableChaining` is removed from `logException()` method


1.2.0
-----

### Added:
- Exception chaining support in `\WebArch\LogTools\Traits\LogExceptionTrait::logException()`
- Unit-test & Travis CI
- Codestyle via PHP CS Fixer
- This changelog file

### Changed:
- Licence has changed to BSD-3-Clause

### Removed:
- PHP 7.0, 7.1 support removed
