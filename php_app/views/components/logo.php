<?php
$size = $size ?? 'md';
$sizes = [
    'sm' => ['width' => 140, 'height' => 40, 'icon' => 32, 'text' => 'text-lg', 'sub' => 'text-[8px]'],
    'md' => ['width' => 200, 'height' => 52, 'icon' => 40, 'text' => 'text-xl', 'sub' => 'text-[9px]'],
    'lg' => ['width' => 280, 'height' => 72, 'icon' => 56, 'text' => 'text-3xl', 'sub' => 'text-xs'],
];
$s = $sizes[$size] ?? $sizes['md'];
?>
<div class="flex items-center gap-3 select-none">
    <svg width="<?= $s['icon'] ?>" height="<?= $s['icon'] ?>" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="logoGrad1" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#e04a6f" />
                <stop offset="50%" stop-color="#b8234a" />
                <stop offset="100%" stop-color="#7c3aed" />
            </linearGradient>
            <linearGradient id="logoGrad2" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#ffc800" />
                <stop offset="100%" stop-color="#e0a800" />
            </linearGradient>
            <filter id="glow">
                <feGaussianBlur stdDeviation="1.5" result="blur" />
                <feMerge>
                    <feMergeNode in="blur" />
                    <feMergeNode in="SourceGraphic" />
                </feMerge>
            </filter>
        </defs>
        <circle cx="50" cy="50" r="48" fill="url(#logoGrad1)" />
        <circle cx="50" cy="50" r="44" fill="none" stroke="url(#logoGrad2)" stroke-width="1.5" opacity="0.8" />
        <circle cx="50" cy="50" r="38" fill="none" stroke="url(#logoGrad2)" stroke-width="0.5" opacity="0.4" />
        <text x="50" y="62" text-anchor="middle" font-family="Playfair Display, serif" font-size="42" font-weight="700" fill="url(#logoGrad2)" filter="url(#glow)">D</text>
        <path d="M25 28 Q50 18 75 28" fill="none" stroke="url(#logoGrad2)" stroke-width="1" opacity="0.6" />
        <path d="M25 72 Q50 82 75 72" fill="none" stroke="url(#logoGrad2)" stroke-width="1" opacity="0.6" />
        <circle cx="50" cy="18" r="2" fill="#ffc800" opacity="0.7" />
        <circle cx="50" cy="82" r="2" fill="#ffc800" opacity="0.7" />
        <circle cx="18" cy="50" r="2" fill="#ffc800" opacity="0.7" />
        <circle cx="82" cy="50" r="2" fill="#ffc800" opacity="0.7" />
    </svg>
    <div class="flex flex-col leading-none">
        <span class="<?= $s['text'] ?> font-heading font-bold bg-gradient-to-r from-primary-600 via-primary-700 to-secondary-600 bg-clip-text text-transparent">
            Devi Fancy Store
        </span>
        <span class="<?= $s['sub'] ?> tracking-[0.25em] uppercase text-gray-400 font-medium mt-0.5">
            Accessories & More
        </span>
    </div>
</div>
