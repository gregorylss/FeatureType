<?php

namespace FeatureType\Api\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use FeatureType\Api\State\FeatureAvailabilityExtendCollectionProvider;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Thelia\Api\Resource\PropelResourceInterface;
use Thelia\Api\Resource\PropelResourceTrait;
use Thelia\Model\Map\FeatureAvTableMap;
use Propel\Runtime\Map\TableMap;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/admin/feature-availabilities-extended',
            openapiContext: [
                'parameters' => [
                    [
                        'name' => 'feature_type_id',
                        'in' => 'query',
                        'description' => 'Filter by feature type Id(s)',
                        'required' => false,
                        'schema' => ['type' => 'array', 'items' => ['type' => 'integer']],
                    ],
                    [
                        'name' => 'feature_type_slug',
                        'in' => 'query',
                        'description' => 'Filter by feature type slug(s)',
                        'required' => false,
                        'schema' => ['type' => 'string'],
                    ],
                    [
                        'name' => 'feature',
                        'in' => 'query',
                        'description' => 'Filter by feature Id(s)',
                        'required' => false,
                        'schema' => ['type' => 'array', 'items' => ['type' => 'integer']],
                    ],
                    [
                        'name' => 'exclude_feature',
                        'in' => 'query',
                        'description' => 'Exclude feature Id(s)',
                        'required' => false,
                        'schema' => ['type' => 'array', 'items' => ['type' => 'integer']],
                    ],
                    [
                        'name' => 'order',
                        'in' => 'query',
                        'description' => 'Order by field',
                        'required' => false,
                        'schema' => ['type' => 'string', 'enum' => ['id', 'id-reverse', 'alpha', 'alpha-reverse', 'manual', 'manual-reverse']],
                    ],
                ],
            ],
            paginationEnabled: true,
            provider: FeatureAvailabilityExtendCollectionProvider::class
        ),
    ],
    normalizationContext: ['groups' => [FeatureAvailabilityExtend::GROUP_ADMIN_READ]]
)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/front/feature-availabilities-extended',
            openapiContext: [
                'parameters' => [
                    [
                        'name' => 'feature_type_id',
                        'in' => 'query',
                        'description' => 'Filter by feature type Id(s)',
                        'required' => false,
                        'schema' => ['type' => 'array', 'items' => ['type' => 'integer']],
                    ],
                    [
                        'name' => 'feature_type_slug',
                        'in' => 'query',
                        'description' => 'Filter by feature type slug(s)',
                        'required' => false,
                        'schema' => ['type' => 'string'],
                    ],
                    [
                        'name' => 'feature',
                        'in' => 'query',
                        'description' => 'Filter by feature Id(s)',
                        'required' => false,
                        'schema' => ['type' => 'array', 'items' => ['type' => 'integer']],
                    ],
                    [
                        'name' => 'exclude_feature',
                        'in' => 'query',
                        'description' => 'Exclude feature Id(s)',
                        'required' => false,
                        'schema' => ['type' => 'array', 'items' => ['type' => 'integer']],
                    ],
                    [
                        'name' => 'order',
                        'in' => 'query',
                        'description' => 'Order by field',
                        'required' => false,
                        'schema' => ['type' => 'string', 'enum' => ['id', 'id-reverse', 'alpha', 'alpha-reverse', 'manual', 'manual-reverse']],
                    ],
                ],
            ],
            paginationEnabled: true,
            provider: FeatureAvailabilityExtendCollectionProvider::class
        ),
    ],
    normalizationContext: ['groups' => [FeatureAvailabilityExtend::GROUP_FRONT_READ]]
)]
class FeatureAvailabilityExtend implements PropelResourceInterface
{
    use PropelResourceTrait;

    public const GROUP_ADMIN_READ = 'admin:feature_availability_extend:read';
    public const GROUP_FRONT_READ = 'front:feature_availability_extend:read';

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?int $id = null;

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?int $featureId = null;

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?bool $isTranslated = null;

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?string $locale = null;

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?string $title = null;

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?string $chapo = null;

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?string $description = null;

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?string $postscriptum = null;

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public ?int $position = null;

    #[Groups([self::GROUP_ADMIN_READ, self::GROUP_FRONT_READ])]
    public array $featureTypeMeta = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): FeatureAvailabilityExtend
    {
        $this->id = $id;
        return $this;
    }

    public function getFeatureId(): ?int
    {
        return $this->featureId;
    }

    public function setFeatureId(?int $featureId): FeatureAvailabilityExtend
    {
        $this->featureId = $featureId;
        return $this;
    }

    public function getIsTranslated(): ?bool
    {
        return $this->isTranslated;
    }

    public function setIsTranslated(?bool $isTranslated): FeatureAvailabilityExtend
    {
        $this->isTranslated = $isTranslated;
        return $this;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): FeatureAvailabilityExtend
    {
        $this->locale = $locale;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): FeatureAvailabilityExtend
    {
        $this->title = $title;
        return $this;
    }

    public function getChapo(): ?string
    {
        return $this->chapo;
    }

    public function setChapo(?string $chapo): FeatureAvailabilityExtend
    {
        $this->chapo = $chapo;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): FeatureAvailabilityExtend
    {
        $this->description = $description;
        return $this;
    }

    public function getPostscriptum(): ?string
    {
        return $this->postscriptum;
    }

    public function setPostscriptum(?string $postscriptum): FeatureAvailabilityExtend
    {
        $this->postscriptum = $postscriptum;
        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): FeatureAvailabilityExtend
    {
        $this->position = $position;
        return $this;
    }

    public function getFeatureTypeMeta(): array
    {
        return $this->featureTypeMeta;
    }

    public function setFeatureTypeMeta(array $featureTypeMeta): FeatureAvailabilityExtend
    {
        $this->featureTypeMeta = $featureTypeMeta;
        return $this;
    }

    /**
     * @throws PropelException
     */
    #[Ignore]
    public static function getPropelRelatedTableMap(): ?TableMap
    {
        return FeatureAvTableMap::getTableMap();
    }
}
