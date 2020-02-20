<?php

namespace Drupal\Tests\image_exif_fix\Kernel;

use Drupal\file\Entity\File;
use Drupal\KernelTests\KernelTestBase;

/**
 * Tests images with the EXIF orientation tag.
 *
 * @group image_exif_fix
 */
class ImageExifOrientationTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'image_exif_fix',
    'system',
    'file',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->installConfig(['system']);
    $this->installEntitySchema('file');
    $this->installSchema('file', ['file_usage']);
  }

  /**
   * Tests images are rotated.
   *
   * @dataProvider operationImageProvider
   */
  public function testOperations($file) {
    $uri = "public://$file";
    $this->container
      ->get('file_system')
      ->copy(__DIR__ . "/../../fixtures/$file", $uri);
    File::create(['uri' => $uri])->save();

    $image = imagecreatefromjpeg($uri);
    $pixel = imagecolorat($image, 0, 0);
    $this->assertGreaterThan(240, $pixel & 0xFF, 'Image has been rotated.');
  }

  /**
   * Provides test image cases for ::testOperations().
   */
  public function operationImageProvider() {
    return [
      ['image90.jpg'],
      ['image180.jpg'],
      ['image270.jpg'],
    ];
  }

}
