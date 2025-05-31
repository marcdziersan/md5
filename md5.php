<?php
class MD5Hasher {
    private array $s = [
        7,12,17,22, 7,12,17,22, 7,12,17,22, 7,12,17,22,
        5,9,14,20, 5,9,14,20, 5,9,14,20, 5,9,14,20,
        4,11,16,23, 4,11,16,23, 4,11,16,23, 4,11,16,23,
        6,10,15,21, 6,10,15,21, 6,10,15,21, 6,10,15,21
    ];

    private array $k;

    public function __construct() {
        for ($i = 0; $i < 64; $i++) {
            $this->k[$i] = (int) (4294967296 * abs(sin($i + 1)));
        }
    }

    public function hash(string $message): string {
        $messageLenBits = strlen($message) * 8;
        $message .= chr(0x80);
        while ((strlen($message) * 8) % 512 !== 448) {
            $message .= chr(0x00);
        }

        for ($i = 0; $i < 8; $i++) {
            $message .= chr(($messageLenBits >> ($i * 8)) & 0xff);
        }

        $a0 = 0x67452301;
        $b0 = 0xefcdab89;
        $c0 = 0x98badcfe;
        $d0 = 0x10325476;

        $chunks = str_split($message, 64);
        foreach ($chunks as $chunk) {
            $m = [];
            for ($i = 0; $i < 64; $i += 4) {
                $m[] = ord($chunk[$i]) |
                       (ord($chunk[$i + 1]) << 8) |
                       (ord($chunk[$i + 2]) << 16) |
                       (ord($chunk[$i + 3]) << 24);
            }

            $A = $a0;
            $B = $b0;
            $C = $c0;
            $D = $d0;

            for ($i = 0; $i < 64; $i++) {
                if ($i < 16) {
                    $F = ($B & $C) | ((~$B) & $D);
                    $g = $i;
                } elseif ($i < 32) {
                    $F = ($D & $B) | ((~$D) & $C);
                    $g = (5 * $i + 1) % 16;
                } elseif ($i < 48) {
                    $F = $B ^ $C ^ $D;
                    $g = (3 * $i + 5) % 16;
                } else {
                    $F = $C ^ ($B | (~$D));
                    $g = (7 * $i) % 16;
                }

                $F = $F + $A + $this->k[$i] + $m[$g];
                $A = $D;
                $D = $C;
                $C = $B;
                $B = $B + self::leftRotate($F, $this->s[$i]);
            }

            $a0 = ($a0 + $A) & 0xffffffff;
            $b0 = ($b0 + $B) & 0xffffffff;
            $c0 = ($c0 + $C) & 0xffffffff;
            $d0 = ($d0 + $D) & 0xffffffff;
        }

        return sprintf('%08x%08x%08x%08x', $a0, $b0, $c0, $d0);
    }

    private static function leftRotate(int $x, int $c): int {
        return (($x << $c) | ($x >> (32 - $c))) & 0xffffffff;
    }
}
?>
