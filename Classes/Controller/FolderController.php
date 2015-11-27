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
use TYPO3\CMS\Core\Resource\ResourceFactory;

/**
 * Handle all folder related tasks, like delivering index of a folder content.
 *
 * @author Daniel Siepmann <d.siepmann@web-vision.de>
 */
class FolderController extends ActionController
{
    /**
     * Deliver index of contents for a folder.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->view->assign(
            'folder',
            ResourceFactory::getInstance()->retrieveFileOrFolderObject(
                $this->settings['folderToUse']
            )
        );
    }
}
