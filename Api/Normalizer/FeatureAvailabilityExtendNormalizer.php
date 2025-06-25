<?php

namespace FeatureType\Api\Normalizer;

use FeatureType\Model\FeatureTypeAvMeta;
use FeatureType\Model\FeatureTypeAvMetaQuery;
use FeatureType\Model\Map\FeatureFeatureTypeTableMap;
use FeatureType\Model\Map\FeatureTypeAvMetaTableMap;
use FeatureType\Model\Map\FeatureTypeTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Thelia\Model\FeatureAv;

class FeatureAvailabilityExtendNormalizer implements NormalizerInterface
{
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof FeatureAv;
    }

    /**
     * @param FeatureAv $object
     * @throws PropelException
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $locale = $context['locale'];

        return [
            'id' => $object->getId(),
            'feature_id' => $object->getFeatureId(),
            'is_translated' => $object->getVirtualColumn('IS_TRANSLATED') ?? false,
            'locale' => $locale,
            'title' => $object->getVirtualColumn('i18n_TITLE') ?? null,
            'chapo' => $object->getVirtualColumn('i18n_CHAPO') ?? null,
            'description' => $object->getVirtualColumn('i18n_DESCRIPTION') ?? null,
            'postscriptum' => $object->getVirtualColumn('i18n_POSTSCRIPTUM') ?? null,
            'position' => $object->getPosition(),
        ];
    }

    /**
     * Récupérer les métadonnées des feature types
     */
    private function getFeaturesMeta(array $featureAvs, string $locale): array
    {
        $featureAvIds = [];
        foreach ($featureAvs as $featureAv) {
            $featureAvIds[] = $featureAv->getId();
        }

        if (empty($featureAvIds)) {
            return [];
        }

        $joinFeatureFeatureType = new Join();
        $joinFeatureFeatureType->addExplicitCondition(
            FeatureTypeAvMetaTableMap::TABLE_NAME,
            'FEATURE_FEATURE_TYPE_ID',
            null,
            FeatureFeatureTypeTableMap::TABLE_NAME,
            'ID',
            null
        );
        $joinFeatureFeatureType->setJoinType(Criteria::INNER_JOIN);

        $joinFeatureType = new Join();
        $joinFeatureType->addExplicitCondition(
            FeatureFeatureTypeTableMap::TABLE_NAME,
            'FEATURE_TYPE_ID',
            null,
            FeatureTypeTableMap::TABLE_NAME,
            'ID',
            null
        );
        $joinFeatureType->setJoinType(Criteria::INNER_JOIN);

        $query = FeatureTypeAvMetaQuery::create()
            ->filterByLocale($locale)
            ->filterByFeatureAvId($featureAvIds, Criteria::IN)
            ->addJoinObject($joinFeatureFeatureType)
            ->addJoinObject($joinFeatureType);

        $query->withColumn('`feature_type`.`SLUG`', 'SLUG');

        return $query->find()->toArray();
    }

    /**
     * Formater le slug
     */
    private function formatSlug(string $slug): string
    {
        return strtoupper(str_replace('-', '_', $slug));
    }
}
