<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Actions;

use Neos\Neos\Domain\Link\Link;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\InspectorGroupDeclaration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\Editor\AssetLinkOptions;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\Editor\DocumentLinkOptions;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\Editor\FormattingOptions;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\Editor\InlineEditor\InlineEditorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\Editor\LinkEditor\LinkEditorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\Editor\LinkTypes;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\Editor\LinkingOptions;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\Editor\MailToLinkOptions;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\Editor\PhoneLinkOptions;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\Editor\SelectBoxEditor\EnumSelectBoxEditorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\Editor\WebLinkOptions;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\InspectorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\PropertyUiConfiguration;
use Sitegeist\PaperTiger\CPX\Application\EmailActionEditorConfiguration;
use Sitegeist\PaperTiger\CPX\Application\MessageActionViewConfiguration;
use Sitegeist\PaperTiger\CPX\Application\PaperTigerFieldTokensConfiguration;
use Sitegeist\PaperTiger\CPX\Application\VisibilityConfiguration;
use Sitegeist\PaperTiger\CPX\Components\Form\ActionType;

#[NodeTypeDeclaration]
#[InspectorGroupDeclaration(
    name: 'actions-message',
    label: 'Sitegeist.PaperTiger.CPX:NodeTypes.Action:groups.message',
    icon: 'commenting-o',
    position: '20',
)]
#[InspectorGroupDeclaration(
    name: 'actions-email',
    label: 'Sitegeist.PaperTiger.CPX:NodeTypes.Action:groups.email',
    icon: 'icon-envelope-o',
    position: '30',
)]
#[MessageActionViewConfiguration]
interface Actions
{
    #[EnumSelectBoxEditorConfiguration(
        internationalize: true,
    )]
    #[PropertyUiConfiguration(
        label: 'Sitegeist.PaperTiger.CPX:NodeTypes.Action:properties.actionType',
        reloadIfChanged: true,
    )]
    #[InspectorConfiguration(
        group: 'actions-message',
        position: 'start',
    )]
    public ActionType $actionType {get;}
    #[InlineEditorConfiguration(
        placeholder: 'Sitegeist.PaperTiger.CPX:NodeTypes.Action.Message:properties.messagePlaceholder',
        autoparagraph: true,
        linking: new LinkingOptions(
            anchor: true,
            title: true,
            relNofollow: true,
            targetBlank: true,
        ),
        formatting: new FormattingOptions(
            strong: true,
            em: true,
            sub: true,
            sup: true,
            p: true,
            h1: false,
            h2: false,
            h3: true,
            h4: true,
            h5: false,
            h6: false,
            underline: true,
            removeFormat: true,
            table: false,
            ol: true,
            ul: true,
            a: true,
        )
    )]
    #[PaperTigerFieldTokensConfiguration]
    public ?string $message {get;}
    #[PropertyUiConfiguration(
        label: 'Sitegeist.PaperTiger.CPX:NodeTypes.Action:builtInTypes.email',
        reloadIfChanged: true,
    )]
    #[InspectorConfiguration(group: 'actions-email', position: 10)]
    #[EmailActionEditorConfiguration]
    /** @var Sitegeist\PaperTiger\CPX\Domain\Action\Specification\EmailActionSpecification[] */
    public array $emailAction {get;}
    #[PropertyUiConfiguration(
        label: 'Sitegeist.PaperTiger.CPX:NodeTypes.Action:builtInTypes.redirect',
        reloadIfChanged: true,
    )]
    #[InspectorConfiguration(group: 'actions-message')]
    #[VisibilityConfiguration(hidden: 'ClientEval:node.properties.actionType !== "redirect"')]
    #[LinkEditorConfiguration(
        linkTypes: new LinkTypes(
            web: new WebLinkOptions(enabled: true),
            document: new DocumentLinkOptions(enabled: true),
            asset: new AssetLinkOptions(enabled: false),
            mailTo: new MailToLinkOptions(enabled: false),
            phone: new PhoneLinkOptions(enabled: false),
        ),
    )]
    public ?Link $redirectAction {get;}
}
