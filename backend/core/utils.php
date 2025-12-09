<?php

function number_to_word($number) {
    $map = [
        1 => 'first',
        2 => 'second',
        3 => 'third',
        4 => 'fourth',
        5 => 'fifth',
        6 => 'sixth',
        7 => 'seventh',
        8 => 'eighth',
        9 => 'ninth'
    ];

    return $map[$number] ?? null;
}

function compress_image($source, $destination, $quality) {
    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
        imagejpeg($image, $destination, $quality);  // compress JPEG
    }

    elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
        imagepng($image, $destination, 9); // PNG uses level 0-9
    }

    elseif ($info['mime'] == 'image/webp') {
        $image = imagecreatefromwebp($source);
        imagewebp($image, $destination, $quality);
    }

    return $destination;
}

