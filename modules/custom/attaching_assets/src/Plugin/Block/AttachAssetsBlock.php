<?php

namespace Drupal\attaching_assets\Plugin\Block;

use Drupal\Core\Annotation\Translation;
use Drupal\Core\Block\BlockBase;

/**
 * Class AttachAssetsBlock
 *
 * @package Drupal\attaching_assets\Plugin\Block
 *
 * @Block(
 *    id = "attach_assets_block",
 *    admin_label=@Translation("attaching assets in a block")
 * )
 */
class AttachAssetsBlock extends BlockBase {

  /**
   * @inheritDoc
   */
  public function build() {
    return [
      '#markup' => $this->t('<marquee>Block with attached assets in this module.</marquee>'),
      '#attached' => [
        'library' => [
          'attaching_assets/all_tables',
        ],
      ],
    ];
  }

}
