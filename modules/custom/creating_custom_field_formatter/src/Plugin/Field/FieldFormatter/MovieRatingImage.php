<?php

namespace Drupal\creating_custom_field_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Annotation\Translation;
use Drupal\Core\Field\Annotation\FieldFormatter;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\DecimalFormatter;

/**
 * Class MovieRatingImage
 *
 * @package Drupal\creating_custom_field_formatter\Plugin\Field\FieldFormatter
 *
 * @FieldFormatter(
 *   id = "movie_rating_image",
 *   label = @Translation("Movie Rating Image"),
 *   description = "Rating image for a movie in the site.",
 *   field_types = {
 *    "decimal"
 *   }
 * )
 */
class MovieRatingImage extends DecimalFormatter {

  /**
   * @inheritDoc
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);

    // Add theme suggestion and library.
    foreach ($elements as $index => $element) {
      if (is_array($element)) {
        $element += [
          '#theme' => 'movie_rating_formatter',
          '#attached' => [
            'library' => [
              'creating_custom_field_formatter/movie_rating_formatter'
            ]
          ],
          // Change rating to percentage, and expose to template.
          '#context' => [
            'rating' => $element['#markup']/5*100,
          ]
        ];
        $elements[$index] = $element;
      }
    }

    return $elements;
  }

}
