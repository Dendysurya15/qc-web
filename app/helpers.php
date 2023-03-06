<?php
// Important functions

if (!function_exists('count_percent')) {
    function count_percent($skor1, $skor2)
    {
        return $skor2 == 0 ? $skor1 : round(($skor1 / $skor2) * 100, 2);
    }
}

if (!function_exists('skor_brd_tinggal')) {
    function skor_brd_tinggal($skor)
    {
        if ($skor <= 3) {
            return 10;
        } else if ($skor >= 3 && $skor <= 5) {
            return 8;
        } else if ($skor >= 5 && $skor <= 7) {
            return 6;
        } else if ($skor >= 7 && $skor <= 9) {
            return 4;
        } else if ($skor >= 9 && $skor <= 11) {
            return 2;
        } else if ($skor >= 11) {
            return 0;
        }
    }
}

if (!function_exists('skor_buah_tinggal')) {
    function skor_buah_tinggal($skor)
    {
        if ($skor <= 0.0) {
            return 10;
        } else if ($skor >= 0.0 && $skor <= 0.5) {
            return 8;
        } else if ($skor >= 0.5 && $skor <= 1) {
            return 6;
        } else if ($skor >= 1.0 && $skor <= 1.5) {
            return 4;
        } else if ($skor >= 1.5 && $skor <= 2.0) {
            return 2;
        } else if ($skor >= 2.0 && $skor <= 2.5) {
            return 0;
        } else if ($skor >= 2.5 && $skor <= 3.0) {
            return -2;
        } else if ($skor >= 3.0 && $skor <= 3.5) {
            return -4;
        } else if ($skor >= 3.5 && $skor <= 4.0) {
            return -6;
        } else if ($skor >= 4.0) {
            return -8;
        }
    }
}

if (!function_exists('skor_buah_mentah_mb')) {
    function skor_buah_mentah_mb($skor)
    {
        if ($skor <= 1.0) {
            return 10;
        } else if ($skor >= 1.0 && $skor <= 2.0) {
            return 8;
        } else if ($skor >= 2.0 && $skor <= 3.0) {
            return 6;
        } else if ($skor >= 3.0 && $skor <= 4.0) {
            return 4;
        } else if ($skor >= 4.0 && $skor <= 5.0) {
            return 2;
        } else if ($skor >= 5.0) {
            return 0;
        }
    }
}

if (!function_exists('skor_buah_masak_mb')) {
    function skor_buah_masak_mb($skor)
    {
        if ($skor <= 75.0) {
            return 0;
        } else if ($skor >= 75.0 && $skor <= 80.0) {
            return 1;
        } else if ($skor >= 80.0 && $skor <= 85.0) {
            return 2;
        } else if ($skor >= 85.0 && $skor <= 90.0) {
            return 3;
        } else if ($skor >= 90.0 && $skor <= 95.0) {
            return 4;
        } else if ($skor >= 95.0) {
            return 5;
        }
    }
}

if (!function_exists('skor_buah_over_mb')) {
    function skor_buah_over_mb($skor)
    {
        if ($skor <= 2.0) {
            return 5;
        } else if ($skor >= 2.0 && $skor <= 4.0) {
            return 4;
        } else if ($skor >= 4.0 && $skor <= 6.0) {
            return 3;
        } else if ($skor >= 6.0 && $skor <= 8.0) {
            return 2;
        } else if ($skor >= 8.0 && $skor <= 10.0) {
            return 1;
        } else if ($skor >= 10.0) {
            return 0;
        }
    }
}

if (!function_exists('skor_jangkos_mb')) {
    function skor_jangkos_mb($skor)
    {
        if ($skor <= 1.0) {
            return 5;
        } else if ($skor >= 1.0 && $skor <= 2.0) {
            return 4;
        } else if ($skor >= 2.0 && $skor <= 3.0) {
            return 3;
        } else if ($skor >= 3.0 && $skor <= 4.0) {
            return 2;
        } else if ($skor >= 4.0 && $skor <= 5.0) {
            return 1;
        } else if ($skor >= 5.0) {
            return 0;
        }
    }
}

if (!function_exists('skor_abr_mb')) {
    function skor_abr_mb($skor)
    {
        if ($skor >= 100) {
            return 5;
        } else if ($skor >= 90 && $skor <= 100) {
            return 4;
        } else if ($skor >= 80 && $skor <= 90) {
            return 3;
        } else if ($skor >= 70 && $skor <= 80) {
            return 2;
        } else if ($skor >= 60 && $skor <= 70) {
            return 1;
        } else if ($skor <= 60) {
            return 0;
        }
    }
}

if (!function_exists('skor_brd_ma')) {
    function skor_brd_ma($skor)
    {
        if ($skor <= 1.0) {
            return 20;
        } else if ($skor >= 1 && $skor <= 1.5) {
            return 16;
        } else if ($skor >= 1.5 && $skor <= 2.0) {
            return 12;
        } else if ($skor >= 2.0 && $skor <= 2.5) {
            return 8;
        } else if ($skor >= 2.5 && $skor <= 3.0) {
            return 4;
        } else if ($skor >= 3.0 && $skor <= 3.5) {
            return 0;
        } else if ($skor >= 3.5 && $skor <= 4.0) {
            return -4;
        } else if ($skor >= 4.0 && $skor <= 4.5) {
            return -8;
        } else if ($skor >=  4.5 && $skor <= 5.0) {
            return -12;
        } else if ($skor >=  5.0) {
            return -16;
        }
    }
}

if (!function_exists('skor_buah_Ma')) {
    function skor_buah_Ma($skor)
    {
        if ($skor <=  0.0) {
            return 20;
        } else if ($skor >=  0.0 && $skor <= 1.0) {
            return 18;
        } else if ($skor >= 1 && $skor <= 1.5) {
            return 16;
        } else if ($skor >= 1.5 && $skor <= 2.0) {
            return 12;
        } else if ($skor >= 2.0 && $skor <= 2.5) {
            return 8;
        } else if ($skor >= 2.5 && $skor <= 3.0) {
            return 4;
        } else if ($skor >= 3.0 && $skor <= 3.5) {
            return 0;
        } else if ($skor >= 3.5 && $skor <= 4.0) {
            return -4;
        } else if ($skor >= 4.0 && $skor <= 4.5) {
            return -8;
        } else if ($skor >= 4.5 && $skor <= 5.0) {
            return -12;
        } else if ($skor >= 5.0) {
            return -16;
        }
    }
}

if (!function_exists('skor_palepah_ma')) {
    function skor_palepah_ma($skor)
    {
        if ($skor <=  0.5) {
            return  5;
        } else if ($skor >=  0.5 && $skor <= 1.0) {
            return  4;
        } else if ($skor >= 1.0 && $skor <= 1.5) {
            return  3;
        } else if ($skor >= 1.5 && $skor <= 2.0) {
            return  2;
        } else if ($skor >= 2.0 && $skor <= 2.5) {
            return  1;
        } else if ($skor >= 2.5) {
            return  0;
        }
    }
}

if (!function_exists('skor_kategori_akhir')) {
    function skor_kategori_akhir($skor)
    {
        if ($skor >= 95.0 && $skor <= 100.0) {
            $color = "darkblue";
            $text = "EXCELLENT";
            return array($color, $text);
        } else if ($skor >= 85.0 && $skor < 95.0) {
            $color = "#00ff2e";
            $text = "GOOD";
            return array($color, $text);
        } else if ($skor >= 75.0 && $skor < 85.0) {
            $color = "yellow";
            $text = "SATISFACTORY";
            return array($color, $text);
        } else if ($skor >= 65.0 && $skor < 75.0) {
            $color = "orange";
            $text = "FAIR";
            return array($color, $text);
        } else if ($skor < 65.0) {
            $color = "red";
            $text = "POOR";
            return array($color, $text);
        }
    }
}

if (!function_exists('check_array')) {
    function check_array($key, $value)
    {
        if (array_key_exists($key, $value)) {
            return $value[$key];
        } else {
            return 0;
        }
    }
}
