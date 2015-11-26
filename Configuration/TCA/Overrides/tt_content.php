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

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'WebVision.wv_fal_frontend',
    'FalFrontend',
    'FAL Frontend'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['wvfalfrontend_falfrontend'] = 'recursive,select_key,pages';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['wvfalfrontend_falfrontend'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'wvfalfrontend_falfrontend',
    'FILE:EXT:wv_fal_frontend/Configuration/Flexform.xml'
);
