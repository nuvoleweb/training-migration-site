<?php

namespace Drupal\Tests\nuvole_migrations\ExistingSite;

use weitzman\DrupalTestTraits\ExistingSiteBase;

/**
 * Tests the blog migrations.
 */
class BlogMigrationsTest extends ExistingSiteBase {

  /**
   * Runs through the imported blog posts and checks they have correct values.
   */
  public function testBlogMigrations() {
    /** @var \Drupal\migrate\Plugin\MigrationInterface $migration */
    $migration = $this->container->get('plugin.manager.migration')->createInstance('blog');
    $map = $migration->getIdMap();
    /** @var \Drupal\node\NodeInterface[] $posts */
    $posts = $this->container->get('entity_type.manager')->getStorage('node')->loadByProperties(['type' => 'blog_post']);
    foreach ($posts as $post) {
      $row = $map->getRowByDestination(['nid' => $post->id()]);
      $source_id = $row['sourceid1'];
      $path = $migration->getSourceConfiguration()['urls'][0];
      $json = json_decode(file_get_contents($path . '/' . $source_id. '.json'));
      $title = $json->data->attributes->title[0];
      $subtitle = $json->data->attributes->subtitle[0];
      $body = $json->data->attributes->body_value[0];
      $this->assertEquals($title, $post->label());
      $this->assertEquals($subtitle, $post->get('field_subtitle')->value);
      $this->assertEquals($body, $post->get('body')->value);
    }
  }

}
