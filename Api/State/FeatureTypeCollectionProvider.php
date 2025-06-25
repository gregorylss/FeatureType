<?php

namespace FeatureType\Api\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use FeatureType\Model\FeatureTypeQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;

class FeatureTypeCollectionProvider implements ProviderInterface
{
    /**
     * @param Operation $operation
     * @param array|object|null $uriVariables
     * @param array $context
     * @return array|object|null
     */
    public function provide(Operation $operation, array|object|null $uriVariables = null, array $context = []): array|null|object
    {
        $request = $context['request'] ?? null;
        if (null === $request) {
            return [];
        }

        $query = FeatureTypeQuery::create();

        $locale = $request->getLocale() ;
        $query->joinWithI18n($locale, Criteria::LEFT_JOIN);

        $query->withColumn('feature_type_i18n.title', 'i18n_TITLE');
        $query->withColumn('feature_type_i18n.description', 'i18n_DESCRIPTION');

        $id = $request->query->get('id');
        if ($id !== null) {
            $query->filterById(is_array($id) ? $id : [$id]);
        }

        $excludeId = $request->query->get('exclude_id');
        if ($excludeId !== null) {
            $query->filterById(is_array($excludeId) ? $excludeId : [$excludeId], Criteria::NOT_IN);
        }

        $slug = $request->query->get('slug');
        if (null !== $slug) {
            $query->filterBySlug($slug);
        }

        $featureIds = $request->query->get('feature_id');
        if (null !== $featureIds) {
            if (!is_array($featureIds)) {
                $featureIds = [$featureIds];
            }

            $join = new Join();
            $join->addExplicitCondition(
                'feature_type',
                'id',
                null,
                'feature_feature_type',
                'feature_type_id',
                null
            );
            $join->setJoinType(Criteria::INNER_JOIN);

            $query->addJoinObject($join, 'feature_type_join');
            $query->addJoinCondition(
                'feature_type_join',
                '`feature_feature_type`.`feature_id` IN (?)',
                implode(',', $featureIds),
                null,
                \PDO::PARAM_INT
            );
        }

        $orderParams = $request->query->get('order');

        if (null === $orderParams) {
            $orderParams = ['id'];
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
                case 'slug':
                    $query->orderBySlug();
                    break;
                case 'slug-reverse':
                    $query->orderBySlug(Criteria::DESC);
                    break;
            }
        }

        return $query->find();
    }
}
