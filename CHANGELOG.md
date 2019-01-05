# Changelog
All notable changes to this project will be documented in this file.

## [1.0.11] - 2019-01-06

##Changed
- Code optimizations in List.html and NewsList.html template files

### Added
- Bulk saving and generating Page slugs finally works!
- Table row and buttons for bulk editing of Page slugs


## [1.0.10] - 2019-01-05

### Changed
- Removed some files that are no longer in use
- General Optimization of the slug saving functions

### Removed
- Removed Ajax Route 'getPageInfo' and Action in 'AjaxController.php' because it is no longer needed at the moment
- Removed function 'googlePreview' from 'AjaxController.php'
- Removed function 'getNewsRecordInfo' from 'AjaxController.php' 

### Added
- Possibiliy to disable the 'additional record info' button in Pages list in global Extension configuration
- Dynamic select menu options including translation of all labels into German and Spanish
- More stable check for the News module. First checks if module is active, then if News table exists
- Comparison of original News slug and freshly generated News slug
- Notification if the freshly generated slug is still the same like before
- Switch statement in List.html template to show different partials for record previews in the Pages list
- Language labels for record previews in the Pages list
- Section 'Known Problems' to the README.md file
- Input fields for news record slugs are now also highlighted when changed, analogous to the page records
- News icon for the button in the top right corner


## [1.0.9] - 2019-01-04

### Added
- Small bugfixes
- Basic documentation skeleton added, will be continued soon


## [1.0.8] - 2019-01-03

### Changed
- Changed extension settings type for 'newsMaxEntries' from type string to options
- Changed extension settings type for 'newsOrderBy' from type string to options
- Changed sorting of extension settings for 'News' tab
- Extended dropdown menu 'Entries per page' according to the values of the extension settings option select
- Replaced standard HTML selects of the filter form with Fluid ViewHelpers of type 'f:form.select'
- Several clean-ups of all PHP classes
- Cleaned up JavaScript codes, removed unnecessary lines of code
- Removed way too strict warnings when saving slugs
- Corrected validation and fixed some bugs in validation

### Added
- Dynamic page URL in Pages list added. When user changes the text of the slug input, the URL will change in realtime, too
- Bulk saving and generating functionality added to News view! (this will follow soon for pages, too)
- Added translations to buttons in Partials/GlobalHeader.html
- Added labels for select menus in filter, but still not implemented in the frontend
- Added sorting property 'is_siteroot' to the filter form for pages
- Added full page URL when page rootpage has a configured site, if not just shows the slug below the title


## [1.0.7] - 2019-01-01

### Changed 
- Initial release of the beta version after some testing

### Added
- Created changelog file and let's go on from here, happy new year!