<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Fieldset;

use Neos\Flow\Annotations as Flow;
use Neos\Neos\NodeTypes\Content;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\NodeTypeUiConfiguration;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldCollection;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldConstraint;
use Sitegeist\PaperTiger\CPX\NodeTypes\Mixin\LabelProvider;
use Vendor\WheelInventor\NodeTypes\Content\ContentProperties;

#[NodeTypeDeclaration]
#[NodeTypeUiConfiguration(
    label: 'i18n',
    icon: 'window-maximize',
    group: 'form.elements',
    position: 1200,
)]
#[Flow\Proxy(false)]
final readonly class Fieldset extends FieldCollection implements
    Content,
    LabelProvider,
    FieldConstraint
{
    use ContentProperties;

    public function __construct(
        public string $label,
    ) {
    }
}
