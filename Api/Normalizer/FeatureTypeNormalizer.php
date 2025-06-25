<?php

namespace FeatureType\Api\Normalizer;

use FeatureType\Model\FeatureType;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FeatureTypeNormalizer implements NormalizerInterface
{
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof FeatureType;
    }

    /**
     * @param FeatureType $object
     * @throws PropelException
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            'id' => $object->getId(),
            'slug' => $object->getSlug(),
            'title' => $object->getTitle(),
            'i18n_title' => $object->getVirtualColumn('i18n_TITLE'),
            'description' => $object->getDescription(),
            'i18n_description' => $object->getVirtualColumn('i18n_DESCRIPTION'),
            'css_class' => $object->getCssClass(),
            'pattern' => $object->getPattern(),
            'input_type' => $object->getInputType(),
            'min' => $object->getMin(),
            'max' => $object->getMax(),
            'step' => $object->getStep(),
            'image_max_width' => $object->getImageMaxWidth(),
            'image_max_height' => $object->getImageMaxHeight(),
            'image_ratio' => $object->getImageRatio(),
            'is_multilingual_feature_av_value' => $object->getIsMultilingualFeatureAvValue(),
            'has_feature_av_value' => $object->getHasFeatureAvValue(),
        ];
    }
}
