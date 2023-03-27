<?php
/**
 * This file is a part of "furqansiddiqui/bip39-mnemonics-php" package.
 * https://github.com/furqansiddiqui/bip39-mnemonics-php
 *
 * Copyright (c) 2019 Furqan A. Siddiqui <hello@furqansiddiqui.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code or visit following link:
 * https://github.com/furqansiddiqui/bip39-mnemonics-php/blob/master/LICENSE
 */

declare(strict_types=1);

namespace BIP;

/**
 * Class Mnemonic
 * @package BIP
 */
class Mnemonic
{
    /** @var int */
    public int $wordsCount;
    /** @var array */
    public array $words;
    /** @var array */
    public array $wordsIndex;

    /**
     * @param \BIP\WordList $lang
     * @param string $entropy
     * @param array $binaryChunks
     */
    public function __construct(WordList $lang, public string $entropy, public array $binaryChunks)
    {
        $words = [];
        $wordsIndex = [];
        $wordsCount = 0;
        foreach ($this->binaryChunks as $chunk) {
            $index = bindec($chunk);
            $wordsIndex[] = $index;
            $words[] = $lang->words[$index];
            $wordsCount++;
        }

        $this->wordsCount = $wordsCount;
        $this->wordsIndex = $wordsIndex;
        $this->words = $words;
    }

    /**
     * @param string $passphrase
     * @param int $bytes
     * @return string
     */
    public function generateSeed(string $passphrase = "", int $bytes = 0): string
    {
        return hash_pbkdf2(
            "sha512",
            implode(" ", $this->words),
            "mnemonic" . $passphrase,
            2048,
            $bytes,
            true
        );
    }
}
