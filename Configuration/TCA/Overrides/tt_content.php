<?php

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
call_user_func(function () {
    $extensionKey = 'wv_fal_frontend';
    $pluginKey = 'wvfalfrontend_falfrontend';

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        'WebVision.' . $extensionKey,
        'FalFrontend',
        'FAL Frontend'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginKey] = 'recursive,select_key,pages';
    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginKey] = 'pi_flexform';
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
        $pluginKey,
        'FILE:EXT:' . $extensionKey . '/Configuration/Flexform.xml'
    );
});
