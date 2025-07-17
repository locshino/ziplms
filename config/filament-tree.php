<?php

declare(strict_types=1);

return [
    // You can restrict to delete nodes having children items.
    'allow-delete-parent' => false,

    /*
     * You can restrict to delete root nodes,
     * even if 'allow-delete-parent' is true.
     */
    'allow-delete-root' => false,

    /*
     * If you want to see edit form as compact one,
     * you able to remove parent's select from it.
     * You still can drag'n'drop the nodes.
     */
    'show-parent-select-while-edit' => true,
];
