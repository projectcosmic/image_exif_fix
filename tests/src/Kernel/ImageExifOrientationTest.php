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
   */
  public function testOperations() {
    file_unmanaged_copy(__DIR__ . '/../../fixtures/image.jpg', 'public://image.jpg');
    File::create(['uri' => 'public://image.jpg'])->save();

    $image = imagecreatefromjpeg('public://image.jpg');
    $pixel = imagecolorat($image, 0, 0);
    $this->assertGreaterThan(240, $pixel & 0xFF, 'Image has been rotated.');
  }

}
