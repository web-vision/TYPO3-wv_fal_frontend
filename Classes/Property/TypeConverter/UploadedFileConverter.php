<?php
namespace WebVision\WvFalFrontend\Property\TypeConverter;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Error\Error;
use TYPO3\CMS\Extbase\Property\Exception\TypeConverterException;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface;
use TYPO3\CMS\Extbase\Property\TypeConverter\AbstractTypeConverter;
use TYPO3\Flow\Utility\Files;

/**
 * TypeConverter to convert uploaded file to Resource File.
 *
 * @author Helmut Hummel 2014
 * @author Daniel Siepmann <d.siepmann@web-vision.de>
 */
class UploadedFileConverter extends AbstractTypeConverter
{
    /**
     * Folder where the file upload should go to (including storage).
     */
    const CONF_UPLOAD_FOLDER = 1;

    /**
     * How to handle a upload when the name of the uploaded file conflicts.
     */
    const CONF_UPLOAD_CONFLICT_MODE = 2;

    /**
     * Whether to replace an already present resource.
     * Useful for "maxitems = 1" fields and properties
     * with no ObjectStorage annotation.
     */
    const CONF_ALLOWED_FILE_EXTENSIONS = 4;

    /**
     * Array key (field) that contains error for uploaded files.
     */
    const PHP_FILE_UPLOAD_ERROR_FIELD = 'error';

    /**
     * @var string
     */
    protected $uploadFolder = '1:/user_upload/';

    /**
     * One of 'cancel', 'replace', 'changeName'
     *
     * @var string
     */
    protected $conflictMode = 'changeName';

    /**
     * @var array<string>
     */
    protected $sourceTypes = array('array');

    /**
     * @var string
     */
    protected $targetType = 'TYPO3\CMS\Core\Resource\File';

    /**
     * Take precedence over the available FileReferenceConverter
     *
     * @var int
     */
    protected $priority = 2;

    /**
     * @var \TYPO3\CMS\Core\Resource\ResourceFactory
     * @inject
     */
    protected $resourceFactory;

    /**
     * Process the convert process.
     *
     * @param array $source
     * @param string $targetType
     * @param array $convertedChildProperties
     * @param PropertyMappingConfigurationInterface $configuration
     *
     * @return \TYPO3\CMS\Extbase\Domain\Model\AbstractFileFolder
     */
    public function convertFrom($source, $targetType, array $convertedChildProperties = array(), PropertyMappingConfigurationInterface $configuration = null)
    {
        try {
            $resource = $this->checkForError($source)
                ->checkFileExtension($source, $configuration)
                ->importUploadedResource($source, $configuration);
        } catch (\Exception $e) {
            return new Error($e->getMessage(), $e->getCode());
        }

        return $resource;
    }

    /**
     * Check for upload error.
     *
     * If error occured during upload, throw exception.
     *
     * @param array $uploadInfo
     *
     * @return UploadedFileConverter
     */
    protected function checkForError(array $uploadInfo)
    {
        $nativeErrors = [\UPLOAD_ERR_INI_SIZE, \UPLOAD_ERR_FORM_SIZE, \UPLOAD_ERR_PARTIAL];
        if ($uploadInfo[static::PHP_FILE_UPLOAD_ERROR_FIELD] !== \UPLOAD_ERR_OK) {
            if(in_array($uploadInfo[static::PHP_FILE_UPLOAD_ERROR_FIELD], $nativeErrors)) {
                throw new TypeConverterException(
                    Files::getUploadErrorMessage($uploadInfo[static::PHP_FILE_UPLOAD_ERROR_FIELD]),
                    1264440823
                );
            }

            throw new TypeConverterException(
                'An error occurred while uploading. Please try again or contact the administrator if the problem remains',
                1340193849
            );
        }

        return $this;
    }

    /**
     * Check whether extension is allowed for file upload.
     *
     * Will throw exceptions if extension is invalid.
     *
     * @param array $uploadInfo
     * @param PropertyMappingConfigurationInterface $configuration
     *
     * @return UploadedFileConverter
     */
    protected function checkFileExtension(array $uploadInfo, PropertyMappingConfigurationInterface $configuration)
    {
        if (!GeneralUtility::verifyFilenameAgainstDenyPattern($uploadInfo['name'])) {
            throw new TypeConverterException('Uploading files with the given file extension is not allowed!', 1399312430);
        }

        $allowedExtensions = $configuration->getConfigurationValue(
            'WebVision\\WvFalFrontend\\Property\\TypeConverter\\UploadedFileReferenceConverter',
            static::CONF_ALLOWED_FILE_EXTENSIONS
        );

        if ($allowedExtensions !== null) {
            $filePathInfo = PathUtility::pathinfo($uploadInfo['name']);
            if (!GeneralUtility::inList($allowedExtensions, strtolower($filePathInfo['extension']))) {
                throw new TypeConverterException('File extension is not allowed!', 1399312430);
            }
        }

        return $this;
    }

    /**
     * Import resource.
     *
     * Persist in file system and index.
     *
     * @param array $uploadInfo
     * @param PropertyMappingConfigurationInterface $configuration
     *
     * @return \TYPO3\CMS\Core\Resource\File
     */
    protected function importUploadedResource(array $uploadInfo, PropertyMappingConfigurationInterface $configuration)
    {
        $uploadFolderId = $configuration->getConfigurationValue(__class__, static::CONF_UPLOAD_FOLDER) ?: $this->uploadFolder;
        $conflictMode = $configuration->getConfigurationValue(__class__, static::CONF_UPLOAD_CONFLICT_MODE) ?: $this->conflictMode;

        $uploadFolder = $this->resourceFactory->retrieveFileOrFolderObject($uploadFolderId);

        return $uploadFolder->addUploadedFile($uploadInfo, $conflictMode);
    }
}
