<?php

declare(strict_types=1);

return [
    'sys_file_storage' => [
        [
            'uid' => '1',
            'name' => 'fileadmin',
            'description' => '',
            'driver' => 'Local',
            'configuration' => '
                <?xml version="1.0" encoding="utf-8" standalone="yes" ?>
                <T3FlexForms>
                    <data>
                        <sheet index="sDEF">
                            <language index="lDEF">
                                <field index="basePath">
                                    <value index="vDEF">fileadmin</value>
                                </field>
                                <field index="pathType">
                                    <value index="vDEF">relative</value>
                                </field>
                                <field index="caseSensitive">
                                    <value index="vDEF">1</value>
                                </field>
                            </language>
                        </sheet>
                    </data>
                </T3FlexForms>
            ',
            'is_browsable' => '1',
            'is_public' => '1',
            'is_writable' => '1',
            'is_online' => '1',
            'processingfolder' => '__processed',
            'is_default' => '1',
            'auto_extract_metadata' => '1',
        ],
    ],
    'sys_file' => [
        [
            'uid' => '1',
            'storage' => '1',
            'folder_hash' => '8adf212d4ac4eb2349b8eca3db06bb16494ddead',
            'sha1' => '7a146ba180118e76af17acb9cb828bd60ea0ce91',
            'extension' => 'srf',
        ],
    ],
];
