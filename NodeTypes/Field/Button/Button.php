<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Field\Button;

use Neos\Flow\Annotations as Flow;
use Neos\Neos\NodeTypes\Content;
use Neos\Neos\NodeTypes\ContentProperties;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\NodeTypeUiConfiguration;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldConstraint;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\LabelProvider;

#[NodeTypeDeclaration]
#[NodeTypeUiConfiguration(
    label: 'i18n',
    icon: 'square',
    group: 'form.elements',
    position: 1100,
)]
#[Flow\Proxy(false)]
readonly class Button implements Content, FieldConstraint, LabelProvider
{
    use ContentProperties;

    public function __construct(
        public string $label,
    ) {
    }
}
