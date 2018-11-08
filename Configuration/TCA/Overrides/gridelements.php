<?php

/**
 * Set the default frame to none for gridelements
 *
 * @todo Doesn't work, might be a core bug, Currently solved with ContentFormDataProvider.
 */
$columnsOverrides = [];
$columnsOverrides['frame_class']['config'] = [
    'default' => 'none',
];
$GLOBALS['TCA']['tt_content']['types']['gridelements_pi1'] = array_merge(
    $GLOBALS['TCA']['tt_content']['types']['gridelements_pi1'],
    ['columnsOverrides' => $columnsOverrides]
);
