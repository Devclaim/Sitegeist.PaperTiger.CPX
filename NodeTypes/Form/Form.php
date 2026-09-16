<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\CPX\NodeTypes\Form;

use Neos\Flow\Annotations as Flow;
use Neos\Neos\Domain\Link\Link;
use Neos\Neos\Domain\Property\EditableText;
use Neos\Neos\NodeTypes\Content;
use Neos\Neos\NodeTypes\ContentCollection;
use Neos\Neos\NodeTypes\ContentProperties;
use PackageFactory\OPGM\Domain\NodeType\ChildRelationDeclaration;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeConstraintsDeclaration;
use PackageFactory\OPGM\Domain\NodeType\NodeTypeDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\InspectorGroupDeclaration;
use PackageFactory\OPGM\NeosAdapter\NodeTypeDeclaration\NodeTypeUiConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\Editor\SelectBoxEditor\EnumSelectBoxEditorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\InspectorConfiguration;
use PackageFactory\OPGM\NeosAdapter\PropertyDeclaration\PropertyUiConfiguration;
use Sitegeist\PaperTiger\CPX\Components\Form\ActionType;
use Sitegeist\PaperTiger\CPX\Components\Form\FormMode;
use Sitegeist\PaperTiger\CPX\NodeTypes\Actions\Actions;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldCollection;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FieldConstraint;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormField;
use Sitegeist\PaperTiger\CPX\NodeTypes\Field\FormFields;
use Vendor\WheelInventor\NodeTypes\Content\ContentProperties;

#[NodeTypeDeclaration(
    new NodeTypeConstraintsDeclaration(fqns: [
        FieldConstraint::class => true,
    ]),
)]
#[NodeTypeUiConfiguration(
    label: 'i18n',
    icon: 'wpforms',
    group: 'form.elements',
    position: 10,
)]
#[InspectorGroupDeclaration(
    name: 'form',
    label: 'i18n',
    icon: 'wpforms',
    position: '10',
)]
#[Flow\Proxy(false)]
readonly class Form extends ContentCollection implements Content, Actions
{
    use ContentProperties;

    public function __construct(
        #[ChildRelationDeclaration]
        public FormFields $fields,
        public EditableText $message,
        #[EnumSelectBoxEditorConfiguration(
            internationalize: true,
        )]
        #[PropertyUiConfiguration(
            label: 'Form mode',
            reloadIfChanged: true,
        )]
        #[InspectorConfiguration(
            group: 'form',
            position: 10,
        )]
        public FormMode $formMode = FormMode::FORM_MODE_STANDARD,
        public ActionType $actionType = ActionType::MESSAGE,
        /**
         * @todo what is this
         * @var array<mixed>
         */
        public array $emailAction = [],
        public ?Link $redirectAction = null,
    ) {
    }

    /**
     * @return FormField[]
     */
    public function findFieldsRecursively(): array
    {
        $result = [];
        foreach ($this->fields as $field) {
            if ($field instanceof FormField) {
                $result[] = $field;
            }
            if ($field instanceof FieldCollection) {
                $result = array_merge($result, $field->findFieldsRecursively());
            }
        }

        return $result;
    }
}
