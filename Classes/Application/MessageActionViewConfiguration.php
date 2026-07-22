<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\Application;

use PackageFactory\OPGM\Domain\NodeType\NodeTypeConfigurationInterface;

#[\Attribute(\Attribute::TARGET_CLASS)]
final readonly class MessageActionViewConfiguration implements NodeTypeConfigurationInterface
{
    /**
     * @return array<string,mixed>
     */
    public function getNodeTypeConfiguration(): array
    {
        return [
            'ui' => [
                'inspector' => [
                    'views' => [
                        'messageActionEditor' => array_filter([
                            'label' => 'Sitegeist.PaperTiger.CPX:NodeTypes.Action.Message:views.messageActionEditor.label',
                            'group' => 'actions-message',
                            'position' => 'end',
                            'view' => 'Sitegeist.PaperTiger.CPX/Inspector/Views/MessageActionEditor',
                        ])
                    ]
                ]
            ]
        ];
    }
}
