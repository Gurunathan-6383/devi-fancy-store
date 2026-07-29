<?php
$announcements = $announcements ?? [];
if (empty($announcements)) return;

$typeEmojis = [
    'discount' => '🏷️',
    'festival' => '🎉',
    'flash_sale' => '⚡',
    'new_arrival' => '✨',
    'free_shipping' => '🚚',
    'general' => '📢',
];

$first = $announcements[0];
$bgColor = $first['bg_color'] ?? '#e04a6f';
$textColor = $first['text_color'] ?? '#ffffff';
$singleSetWidth = count($announcements) * 350;
$duplicated = array_merge($announcements, $announcements, $announcements);
?>
<div
    class="relative w-full overflow-hidden"
    style="background-color: <?= htmlspecialchars($bgColor) ?>; color: <?= htmlspecialchars($textColor) ?>"
    onmouseenter="this.querySelector('.marquee-track').style.animationPlayState='paused'"
    onmouseleave="this.querySelector('.marquee-track').style.animationPlayState='running'"
>
    <div class="marquee-track flex items-center h-10 md:h-11" style="animation: marquee-scroll <?= count($announcements) * 15 ?>s linear infinite; width: max-content;">
        <?php foreach ($duplicated as $a): ?>
            <?php
            $emoji = $typeEmojis[$a['type']] ?? '📢';
            $text = '<span class="flex items-center space-x-2 whitespace-nowrap"><span>' . $emoji . '</span><span class="font-semibold">' . htmlspecialchars($a['title']) . '</span><span class="opacity-80">—</span><span>' . htmlspecialchars($a['message']) . '</span></span>';
            $wrapperClass = 'flex items-center space-x-6 px-6 shrink-0';
            if (!empty($a['redirect_url'])): ?>
                <a href="<?= htmlspecialchars($a['redirect_url']) ?>" target="_blank" rel="noopener noreferrer" class="<?= $wrapperClass ?>"><?= $text ?></a>
            <?php else: ?>
                <span class="<?= $wrapperClass ?>"><?= $text ?></span>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <style>
        @keyframes marquee-scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-<?= $singleSetWidth ?>px); }
        }
    </style>
</div>
