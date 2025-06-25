<?php

namespace FeatureType\Api\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use FeatureType\Api\State\FeatureTypeCollectionProvider;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Thelia\Api\Resource\PropelResourceInterface;
use Thelia\Api\Resource\PropelResourceTrait;
use FeatureType\Model\Map\FeatureTypeTableMap;
use Propel\Runtime\Map\TableMap;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/admin/feature-types',
            openapiContext: [
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'query',
                        'description' => 'Filter by feature type Id',
                        'required' => false,
                        'schema' => ['type' => 'integer'],
                    ],
                    [
                        'name' => 'exclude_id',
                        'in' => 'query',
                        'description' => 'Exclude feature type Id(s)',
                        'required' => false,
                        'schema' => ['type' => 'array', 'items' => ['type' => 'integer']],
                    ],
                    [
                        'name' => 'slug',
                        'in' => 'query',
                        'description' => 'Filter by slug',
                        'required' => false,
                        'schema' => ['type' => 'string'],
                    ],
                    [
                        'name' => 'feature_id',
                        'in' => 'query',
                        'description' => 'Filter by linked feature Id(s)',
                        'required' => false,
                        'schema' => ['type' => 'array', 'items' => ['type' => 'integer']],
                    ],
                    [
                        'name' => 'order',
                        'in' => 'query',
                        'description' => 'Order by field',
                        'required' => false,
                        'schema' => ['type' => 'string', 'enum' => ['id', 'id-reverse', 'slug', 'slug-reverse']],
                    ],
                ],
            ],
            paginationEnabled: true,
            provider: FeatureTypeCollectionProvider::class
        ),
    ],
    normalizationContext: ['groups' => [FeatureType::GROUP_ADMIN_READ]]
)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/front/feature-types',
            openapiContext: [
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'query',
                        'description' => 'Filter by feature type Id',
                        'required' => false,
                        'schema' => ['type' => 'integer'],
                    ],
                    [
                        'name' => 'exclude_id',
                        'in' => 'query',
                        'description' => 'Exclude feature type Id(s)',
                        'required' => false,
                        'schema' => ['type' => 'array', 'items' => ['type' => 'integer']],
                    ],
                    [
                        'name' => 'slug',
                        'in' => 'query',
                        'description' => 'Filter by slug',
                        'required' => false,
                        'schema' => ['type' => 'string'],
                    ],
                    [
                        'name' => 'feature_id',
                        'in' => 'query',
                        'description' => 'Filter by linked feature Id(s)',
                        'required' => false,
                        'schema' => ['type' => 'array', 'items' => ['type' => 'integer']],
                    ],
                    [
                        'name' => 'order',
                        'in' => 'query',
                        'description' => 'Order by field',
                        'required' => false,
                        'schema' => ['type' => 'string', 'enum' => ['id', 'id-reverse', 'slug', 'slug-reverse']],
                    ],
                ],
            ],
            paginationEnabled: true,
            provider: FeatureTypeCollectionProvider::class
        ),
    ],
    normalizationContext: ['groups' => [FeatureType::GROUP_FRONT_READ]]
)]
class FeatureType implements PropelResourceInterface
{
    use PropelResourceTrait;

    public const GROUP_ADMIN_READ = 'admin:feature_type:read';
    public const GROUP_FRONT_READ = 'front:feature_type:read';

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?int $id = null;

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?string $slug = null;

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?string $title = null;

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?string $description = null;

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?string $cssClass = null;

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?string $pattern = null;

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?string $inputType = null;

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?int $min = null;

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?int $max = null;

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?int $step = null;

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?int $imageMaxWidth = null;

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?int $imageMaxHeight = null;

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?float $imageRatio = null;

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?bool $isMultilingualFeatureAvValue = null;

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?bool $hasFeatureAvValue = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): FeatureType
    {
        $this->id = $id;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): FeatureType
    {
        $this->slug = $slug;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): FeatureType
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): FeatureType
    {
        $this->description = $description;
        return $this;
    }

    public function getCssClass(): ?string
    {
        return $this->cssClass;
    }

    public function setCssClass(?string $cssClass): FeatureType
    {
        $this->cssClass = $cssClass;
        return $this;
    }

    public function getPattern(): ?string
    {
        return $this->pattern;
    }

    public function setPattern(?string $pattern): FeatureType
    {
        $this->pattern = $pattern;
        return $this;
    }

    public function getInputType(): ?string
    {
        return $this->inputType;
    }

    public function setInputType(?string $inputType): FeatureType
    {
        $this->inputType = $inputType;
        return $this;
    }

    public function getMin(): ?int
    {
        return $this->min;
    }

    public function setMin(?int $min): FeatureType
    {
        $this->min = $min;
        return $this;
    }

    public function getMax(): ?int
    {
        return $this->max;
    }

    public function setMax(?int $max): FeatureType
    {
        $this->max = $max;
        return $this;
    }

    public function getStep(): ?int
    {
        return $this->step;
    }

    public function setStep(?int $step): FeatureType
    {
        $this->step = $step;
        return $this;
    }

    public function getImageMaxWidth(): ?int
    {
        return $this->imageMaxWidth;
    }

    public function setImageMaxWidth(?int $imageMaxWidth): FeatureType
    {
        $this->imageMaxWidth = $imageMaxWidth;
        return $this;
    }

    public function getImageMaxHeight(): ?int
    {
        return $this->imageMaxHeight;
    }

    public function setImageMaxHeight(?int $imageMaxHeight): FeatureType
    {
        $this->imageMaxHeight = $imageMaxHeight;
        return $this;
    }

    public function getImageRatio(): ?float
    {
        return $this->imageRatio;
    }

    public function setImageRatio(?float $imageRatio): FeatureType
    {
        $this->imageRatio = $imageRatio;
        return $this;
    }

    public function getIsMultilingualFeatureAvValue(): ?bool
    {
        return $this->isMultilingualFeatureAvValue;
    }

    public function setIsMultilingualFeatureAvValue(?bool $isMultilingualFeatureAvValue): FeatureType
    {
        $this->isMultilingualFeatureAvValue = $isMultilingualFeatureAvValue;
        return $this;
    }

    public function getHasFeatureAvValue(): ?bool
    {
        return $this->hasFeatureAvValue;
    }

    public function setHasFeatureAvValue(?bool $hasFeatureAvValue): FeatureType
    {
        $this->hasFeatureAvValue = $hasFeatureAvValue;
        return $this;
    }

    /**
     * @throws PropelException
     */
    #[Ignore]
    public static function getPropelRelatedTableMap(): ?TableMap
    {
        return FeatureTypeTableMap::getTableMap();
    }
}
