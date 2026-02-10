# Changelog

## [3.9.0-beta.1] - 2026-02-10
### Added
- Emoji handling: Emojis and miscellaneous symbols are now automatically removed from URLs.

### Changed
- Refactored `normalize` method for better performance and readability.
- Removed redundant uppercase replacements since strings are lowercased beforehand.
- Moved replacement table to separated `getReplacements()` method.
