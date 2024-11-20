<?php

namespace Drupal\test_render\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\test_render\Controller\TestRenderController;

/**
 * Defines a test render block.
 */
#[Block(
  id: "test_render",
  admin_label: new TranslatableMarkup("Test render"),
)]
class TestRenderBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    return [
      '#cache' => [
        'max-age' => 0,
      ],
      'content' => TestRenderController::doLazyBuild(FALSE),
    ];
  }

}
