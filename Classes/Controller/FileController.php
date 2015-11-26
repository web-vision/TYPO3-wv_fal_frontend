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
     * Upload the given file into FAL.
     *
     * @TODO: Use initialize action to prepare file upload
     *        Upload file with FAL.
     *        Use configuration to determine target folder.
     *        Think about way to provide upload form.
     *
     * @return void
     */
    public function uploadAction()
    {
        throw new \Exception('Not implemented yet!');
    }
}
