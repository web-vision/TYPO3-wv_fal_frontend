# web-vision FAL Frontend

Enables some features of FAL for Frontend

## Features

- Upload of files in frontend
- List of files in folders

## Configuration

Just insert the plugin and configure it via the provided flexform.

The configured folder will be used for index and upload.

### Upload

Files are validated against ``$GLOBALS['TYPO3_CONF_VARS']['BE']['fileDenyPattern']``.
