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

$EM_CONF[$_EXTKEY] = [
    'title' => 'FAL Frontend',
    'description' => 'Frontend features for FAL.',
    'category' => 'plugin',
    'version' => '1.0.0',
    'state' => 'beta',
    'author' => 'Daniel Siepmann',
    'author_email' => 'd.siepmann@web-vision.de',
    'author_company' => 'web-vision GmbH',
    'constraints' => [
        'depends' => [
            'php' => '5.5',
            'typo3' => '6.2',
        ],
    ],
];
