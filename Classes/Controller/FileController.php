<?php
namespace WebVision\WvFalFrontend\Controller;

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

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Core\Resource\File;
use WebVision\WvFalFrontend\Property\TypeConverter\UploadedFileConverter;

/**
 * Handle all file related tasks like allow upload of files.
 *
 * @author Daniel Siepmann <d.siepmann@web-vision.de>
 */
class FileController extends ActionController
{
    /**
     * Show a single file.
     *
     * @param string $file The file to show, as combined identifier.
     *
     * @return void
     */
    public function showAction($file)
    {
        $this->view->assign(
            'file',
            $this->objectManager->get('TYPO3\CMS\Core\Resource\ResourceFactory')
                ->getFileObjectFromCombinedIdentifier($file)
        );
    }

    /**
     * Configure upload path.
     *
     * @return FileController
     */
    protected function initializeUploadAction()
    {
        // Configure allowed file extensions (from install tool settings only images).
        // Configure folder for uploads from plugin settings.
        $this->arguments['file']
            ->getPropertyMappingConfiguration()
            ->setTypeConverterOptions(
                'WebVision\WvFalFrontend\Property\TypeConverter\UploadedFileConverter',
                array(
                    UploadedFileConverter::CONF_ALLOWED_FILE_EXTENSIONS => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],
                    UploadedFileConverter::CONF_UPLOAD_FOLDER => $this->settings['folderToUse'],
                )
            );

        return $this;
    }

    /**
     * Upload the given file into FAL.
     *
     * @param TYPO3\CMS\Core\Resource\File $file
     *
     * @return void
     */
    public function uploadAction(File $file)
    {
        $this->addFlashMessage(
            LocalizationUtility::translate('flashMessage.fileUploaded.body', $this->extensionName, array($file->getName())),
            LocalizationUtility::translate('flashMessage.fileUploaded.title', $this->extensionName, array($file->getName()))
        );
        $this->redirect('show', null, null, array('file' => $file->getCombinedIdentifier()));
    }
}
