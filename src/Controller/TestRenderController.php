<?php

declare(strict_types=1);

namespace Drupal\test_render\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\PageCache\ResponsePolicy\KillSwitch;
use Drupal\Core\Security\TrustedCallbackInterface;

/**
 * Test render controller class.
 */
class TestRenderController extends ControllerBase implements TrustedCallbackInterface {

  /**
   * TestRenderController constructor.
   */
  public function __construct(
    private readonly KillSwitch $pageCacheKillSwitch,
  ) {
  }

  /**
   * Slow render controller.
   */
  public function slowRender(): array {
    // Disable dynamic page cache.
    if ($this->currentUser()->isAuthenticated()) {
      $this->pageCacheKillSwitch->trigger();
    }

    $build = [
      '#cache' => [
        'keys' => ['test-slow-render'],
      ],
    ];
    return static::doRenderContent($build);
  }

  /**
   * PreRender controller.
   */
  public function preRender(): array {
    // Disable dynamic page cache.
    if ($this->currentUser()->isAuthenticated()) {
      $this->pageCacheKillSwitch->trigger();
    }

    return [
      '#cache' => [
        'keys' => ['test-pre-render'],
      ],
      '#pre_render' => [
        static::class . '::doRenderContent',
      ],
    ];
  }

  /**
   * PreRender callback.
   */
  public static function doRenderContent(array $element): array {
    // Simulate a slow rendering operation.
    sleep(3);

    $element['content'] = [
      '#markup' => '<div>Slow rendering element</div>',
    ];

    return $element;
  }

  /**
   * LazyBuilder controller.
   */
  public function lazyBuilder(): array {
    // Disable page cache.
    if ($this->currentUser()->isAnonymous()) {
      $this->pageCacheKillSwitch->trigger();
    }

    return [
      '#lazy_builder' => [
        static::class . '::doLazyBuild',
        // Callback arguments.
        [
          TRUE,
        ],
      ],
      '#create_placeholder' => TRUE,
    ];
  }

  /**
   * LazyBuilder callback.
   */
  public static function doLazyBuild(bool $cached): array {
    $element = [
      'content' => [
        '#pre_render' => [
          static::class . '::doRenderContent',
        ],
      ],
    ];


    $element += [
      'date' => [
        '#markup' => '<div>' . date('Y-m-d H:i:s') . '</div>',
      ],
    ];

    if ($cached) {
      $element['content']['#cache'] = [
        'keys' => ['test-lazy-builder'],
      ];
    }

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public static function trustedCallbacks(): array {
    return [
      'doRenderContent',
      'doLazyBuild',
    ];
  }

}
