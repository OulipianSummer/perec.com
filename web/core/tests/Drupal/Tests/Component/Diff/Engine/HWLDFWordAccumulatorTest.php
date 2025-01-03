<?php

declare(strict_types=1);

namespace Drupal\Tests\Component\Diff\Engine;

// cspell:ignore HWLDFWordAccumulator

use Drupal\Component\Diff\Engine\HWLDFWordAccumulator;
use PHPUnit\Framework\TestCase;

// cspell:ignore wordword

/**
 * Test HWLDFWordAccumulator.
 *
 * @coversDefaultClass \Drupal\Component\Diff\Engine\HWLDFWordAccumulator
 *
 * @group Diff
 */
class HWLDFWordAccumulatorTest extends TestCase {

  /**
   * Verify that we only get back a NBSP from an empty accumulator.
   *
   * @covers ::getLines
   *
   * @see Drupal\Component\Diff\Engine\HWLDFWordAccumulator::NBSP
   */
  public function testGetLinesEmpty(): void {
    $acc = new HWLDFWordAccumulator();
    $this->assertEquals(['&#160;'], $acc->getLines());
  }

  /**
   * @return array
   *   - Expected array of lines from getLines().
   *   - Array of strings for the $words parameter to addWords().
   *   - String tag for the $tag parameter to addWords().
   */
  public static function provideAddWords() {
    return [
      [['wordword2'], ['word', 'word2'], 'tag'],
      [['word', 'word2'], ['word', "\nword2"], 'tag'],
      [['&#160;', 'word2'], ['', "\nword2"], 'tag'],
    ];
  }

  /**
   * @covers ::addWords
   * @dataProvider provideAddWords
   */
  public function testAddWords($expected, $words, $tag): void {
    $acc = new HWLDFWordAccumulator();
    $acc->addWords($words, $tag);
    $this->assertEquals($expected, $acc->getLines());
  }

}
