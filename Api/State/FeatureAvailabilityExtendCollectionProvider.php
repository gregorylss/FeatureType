<?php

namespace FeatureType\Api\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use FeatureType\Model\Map\FeatureFeatureTypeTableMap;
use FeatureType\Model\Map\FeatureTypeTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Thelia\Model\FeatureAvQuery;
use Thelia\Model\Map\FeatureAvTableMap;

class FeatureAvailabilityExtendCollectionProvider implements ProviderInterface
{
    public function provide(Operation $operation, array|object|null $uriVariables = null, array $context = []): array|null|object
    {
        $request = $context['request'] ?? null;
        if (null === $request) {
            return [];
        }

        $query = FeatureAvQuery::create();

        $locale = $request->getLocale();
        $query->joinWithI18n($locale, Criteria::LEFT_JOIN);

        $query->withColumn('feature_av_i18n.title', 'i18n_TITLE');
        $query->withColumn('feature_av_i18n.chapo', 'i18n_CHAPO');
        $query->withColumn('feature_av_i18n.description', 'i18n_DESCRIPTION');
        $query->withColumn('feature_av_i18n.postscriptum', 'i18n_POSTSCRIPTUM');

        $query->withColumn(
            'CASE WHEN feature_av_i18n.id IS NOT NULL THEN 1 ELSE 0 END',
            'IS_TRANSLATED'
        );

        $feature = $request->query->get('feature');
        if ($feature !== null) {
            $query->filterByFeatureId(is_array($feature) ? $feature : [$feature]);
        }

        $excludeFeature = $request->query->get('exclude_feature');
        if ($excludeFeature !== null) {
            $query->filterByFeatureId(is_array($excludeFeature) ? $excludeFeature : [$excludeFeature], Criteria::NOT_IN);
        }

        $featureTypeSlug = $request->query->get('feature_type_slug');
        if (null !== $featureTypeSlug) {
            $slugs = explode(',', $featureTypeSlug);
            $slugs = array_map(function($value) {
                return "'" . addslashes(trim($value)) . "'";
            }, $slugs);

            $join = new Join();
            $join->addExplicitCondition(
                FeatureAvTableMap::TABLE_NAME,
                'FEATURE_ID',
                null,
                FeatureFeatureTypeTableMap::TABLE_NAME,
                'FEATURE_ID',
                null
            );

            $join2 = new Join();
            $join2->addExplicitCondition(
                FeatureFeatureTypeTableMap::TABLE_NAME,
                'FEATURE_TYPE_ID',
                null,
                FeatureTypeTableMap::TABLE_NAME,
                'ID',
                null
            );

            $join->setJoinType(Criteria::JOIN);
            $join2->setJoinType(Criteria::JOIN);

            $query
                ->addJoinObject($join, 'feature_feature_type_join')
                ->addJoinObject($join2, 'feature_type_join')
                ->addJoinCondition(
                    'feature_type_join',
                    '`feature_type`.`slug` IN ('.implode(',', $slugs).')'
                );
        }

        $featureTypeId = $request->query->get('feature_type_id');
        if (null !== $featureTypeId) {
            if (!is_array($featureTypeId)) {
                $featureTypeId = [$featureTypeId];
            }

            $join = new Join();
            $join->addExplicitCondition(
                FeatureAvTableMap::TABLE_NAME,
                'FEATURE_ID',
                null,
                FeatureFeatureTypeTableMap::TABLE_NAME,
                'FEATURE_ID',
                null
            );

            $join->setJoinType(Criteria::JOIN);

            $query
                ->addJoinObject($join, 'feature_type_join')
                ->addJoinCondition(
                    'feature_type_join',
                    '`feature_feature_type`.`feature_type_id` IN (?)',
                    implode(',', $featureTypeId),
                    null,
                    \PDO::PARAM_INT
                );
        }

        $orderParams = $request->query->get('order');
        if (null === $orderParams) {
            $orderParams = ['manual'];
        }
        if (!is_array($orderParams)) {
            $orderParams = [$orderParams];
        }

        foreach ($orderParams as $order) {
            switch ($order) {
                case 'id':
                    $query->orderById();
                    break;
                case 'id-reverse':
                    $query->orderById(Criteria::DESC);
                    break;
               case 'alpha':
                    $query->addAscendingOrderByColumn('i18n_TITLE');
                    break;
                case 'alpha-reverse':
                    $query->addDescendingOrderByColumn('i18n_TITLE');
                    break;
                case 'manual':
                    $query->orderByPosition();
                    break;
                case 'manual-reverse':
                    $query->orderByPosition(Criteria::DESC);
                    break;
            }
        }

        return $query->find();
    }
}
