<?php

namespace FeatureType\Api\Normalizer;

use FeatureType\Model\FeatureFeatureTypeQuery;
use FeatureType\Model\Map\FeatureFeatureTypeTableMap;
use FeatureType\Model\Map\FeatureTypeTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Thelia\Model\Feature;

class FeatureExtendNormalizer implements NormalizerInterface
{
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Feature;
    }

    /**
     * @param Feature $object
     * @throws PropelException
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $locale = $context['locale'] ?? 'en_US';

        $featureTypes = $this->getFeaturesType([$object]);

        $featureTypesData = [];
        foreach ($featureTypes as $featureType) {
            if ($featureType->getFeatureId() === $object->getId()) {
                $slug = $this->formatSlug($featureType->getVirtualColumn('SLUG'));
                $featureTypesData[$slug] = true;
            }
        }

        return [
            'id' => $object->getId(),
            'is_translated' => $object->getVirtualColumn('IS_TRANSLATED') ?? false,
            'locale' => $locale,
            'title' => $object->getTitle(),
            'i18n_title' => $object->getVirtualColumn('i18n_TITLE') ?? null,
            'chapo' => $object->getChapo(),
            'i18n_chapo' => $object->getVirtualColumn('i18n_CHAPO') ?? null,
            'description' => $object->getDescription(),
            'i18n_description' => $object->getVirtualColumn('i18n_DESCRIPTION') ?? null,
            'postscriptum' => $object->getPostscriptum(),
            'i18n_postscriptum' => $object->getVirtualColumn('i18n_POSTSCRIPTUM') ?? null,
            'position' => $object->getPosition(),
            'feature_types' => $featureTypesData,
        ];
    }

    /**
     * Récupérer les types de features
     */
    private function getFeaturesType(array $features): array
    {
        $featureIds = [];
        foreach ($features as $feature) {
            $featureIds[] = $feature->getId();
        }

        if (empty($featureIds)) {
            return [];
        }

        $join = new Join();
        $join->addExplicitCondition(
            FeatureFeatureTypeTableMap::TABLE_NAME,
            'FEATURE_TYPE_ID',
            null,
            FeatureTypeTableMap::TABLE_NAME,
            'ID',
            null
        );
        $join->setJoinType(Criteria::INNER_JOIN);

        $query = FeatureFeatureTypeQuery::create()
            ->filterByFeatureId($featureIds, Criteria::IN)
            ->addJoinObject($join);

        $query->withColumn('`feature_type`.`SLUG`', 'SLUG');

        return iterator_to_array($query->find());
    }

    /**
     * Formater le slug
     */
    private function formatSlug(string $slug): string
    {
        return strtoupper(str_replace('-', '_', $slug));
    }
}
