<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['group']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['group']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $socialLinks = [
        'website' => [
            'url' => $group->website,
            'icon' => 'ph-duotone ph-globe',
            'label' => 'Sito Web',
            'color' => 'btn-outline-primary'
        ],
        'social_facebook' => [
            'url' => $group->social_facebook,
            'icon' => 'ph-duotone ph-facebook-logo',
            'label' => 'Facebook',
            'color' => 'btn-facebook'
        ],
        'social_instagram' => [
            'url' => $group->social_instagram,
            'icon' => 'ph-duotone ph-instagram-logo',
            'label' => 'Instagram',
            'color' => 'btn-instagram'
        ],
        'social_youtube' => [
            'url' => $group->social_youtube,
            'icon' => 'ph-duotone ph-youtube-logo',
            'label' => 'YouTube',
            'color' => 'btn-youtube'
        ],
        'social_twitter' => [
            'url' => $group->social_twitter,
            'icon' => 'ph-duotone ph-twitter-logo',
            'label' => 'Twitter',
            'color' => 'btn-twitter'
        ],
        'social_tiktok' => [
            'url' => $group->social_tiktok,
            'icon' => 'ph-duotone ph-tiktok-logo',
            'label' => 'TikTok',
            'color' => 'btn-tiktok'
        ],
        'social_linkedin' => [
            'url' => $group->social_linkedin,
            'icon' => 'ph-duotone ph-linkedin-logo',
            'label' => 'LinkedIn',
            'color' => 'btn-linkedin'
        ]
    ];

    $availableLinks = array_filter($socialLinks, function($link) {
        return !empty($link['url']);
    });
?>

<?php if(count($availableLinks) > 0): ?>
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0">
            <i class="ph-duotone ph-share-network me-2 text-primary"></i>
            Segui sui social
        </h6>
    </div>
    <div class="card-body">
        <?php if(count($availableLinks) <= 4): ?>
            <!-- Layout a griglia per pochi social -->
            <div class="row g-2">
                <?php $__currentLoopData = $availableLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform => $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $url = $link['url'];
                        // Assicurati che l'URL abbia il protocollo
                        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
                            $url = 'https://' . $url;
                        }
                    ?>
                    <div class="col-6">
                        <a href="<?php echo e($url); ?>" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="btn <?php echo e($link['color']); ?> btn-sm w-100 d-flex align-items-center justify-content-center"
                           title="<?php echo e($link['label']); ?>"
                           style="border-radius: 8px; padding: 8px 12px; transition: all 0.2s ease;">
                            <i class="<?php echo e($link['icon']); ?> f-s-16 me-2"></i>
                            <span class="small"><?php echo e($link['label']); ?></span>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <!-- Layout compatto per molti social -->
            <div class="d-flex flex-wrap gap-2">
                <?php $__currentLoopData = $availableLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform => $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $url = $link['url'];
                        // Assicurati che l'URL abbia il protocollo
                        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
                            $url = 'https://' . $url;
                        }
                    ?>
                    <a href="<?php echo e($url); ?>" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="btn <?php echo e($link['color']); ?> btn-sm d-flex align-items-center"
                       title="<?php echo e($link['label']); ?>"
                       style="border-radius: 20px; padding: 6px 12px; transition: all 0.2s ease;">
                        <i class="<?php echo e($link['icon']); ?> f-s-14 me-1"></i>
                        <span class="small"><?php echo e($link['label']); ?></span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.group-social-links .btn {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.group-social-links .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.group-social-links .btn-facebook {
    background: linear-gradient(135deg, #1877f2, #42a5f5);
    color: white;
}

.group-social-links .btn-instagram {
    background: linear-gradient(135deg, #e4405f, #fd1d1d, #fcb045);
    color: white;
}

.group-social-links .btn-youtube {
    background: linear-gradient(135deg, #ff0000, #ff4444);
    color: white;
}

.group-social-links .btn-twitter {
    background: linear-gradient(135deg, #1da1f2, #0d8bd9);
    color: white;
}

.group-social-links .btn-tiktok {
    background: linear-gradient(135deg, #000000, #333333);
    color: white;
}

.group-social-links .btn-linkedin {
    background: linear-gradient(135deg, #0077b5, #005885);
    color: white;
}
</style>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\slamin\resources\views/components/group-social-links.blade.php ENDPATH**/ ?>