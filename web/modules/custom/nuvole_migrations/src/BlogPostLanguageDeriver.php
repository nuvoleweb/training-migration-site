<?php

namespace Drupal\nuvole_migrations;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Deriver to create blog migrations for each language.
 */
class BlogPostLanguageDeriver extends DeriverBase implements ContainerDeriverInterface {

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * BlogPostLanguageDeriver constructor.
   *
   * @param \Drupal\Core\Language\LanguageManagerInterface $languageManager
   *   The language manager.
   */
  public function __construct(LanguageManagerInterface $languageManager) {
    $this->languageManager = $languageManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('language_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $languages = $this->languageManager->getLanguages();
    foreach ($languages as $language) {
      // We skip EN as that is the original language.
      if ($language->getId() === 'en') {
        continue;
      }

      $derivative = $this->getDerivativeValues($base_plugin_definition, $language);
      $this->derivatives[$language->getId()] = $derivative;
    }

    return $this->derivatives;
  }

  /**
   * Creates a derivative definition for each available language.
   *
   * @param array $base_plugin_definition
   *   The base plugin definition.
   * @param LanguageInterface $language
   *   The language for which to make a derivative.
   *
   * @return array
   */
  protected function getDerivativeValues(array $base_plugin_definition, LanguageInterface $language) {
    // Tell the migration which folder contains the data for this language.
    $path = drupal_get_path('module', 'nuvole_migrations') . '/data/translations/node/blog/';
    $url = $path . $language->getId();
    $base_plugin_definition['source']['urls'][] = $url;

    // Map the langcode.
    $base_plugin_definition['process']['langcode'] = [
      'plugin' => 'default_value',
      'default_value' => $language->getId(),
    ];

    return $base_plugin_definition;
  }

}
